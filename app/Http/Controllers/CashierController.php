<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashierController extends Controller
{
    /**
     * Display the cashier interface
     */
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('cashier.index', compact('products'));
    }

    /**
     * Process a new sale transaction
     */
    public function processSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashier_name' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create sale record
            $sale = new Sale();
            $sale->cashier_name = $request->cashier_name;
            $sale->customer_name = $request->customer_name ?? 'Guest';
            $sale->transaction_date = now();
            $sale->total_amount = 0;
            $sale->save();

            $totalAmount = 0;

            // Process each product in the sale
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Check if enough stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                // Calculate item total
                $itemTotal = $product->price * $item['quantity'];
                $totalAmount += $itemTotal;
                
                // Update product stock
                $product->stock -= $item['quantity'];
                $product->save();
                
                // Create sale detail
                $sale->saleDetails()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemTotal
                ]);
            }
            
            // Update sale with total amount
            $sale->total_amount = $totalAmount;
            $sale->save();
            
            DB::commit();
            
            return redirect()->route('cashier.receipt', $sale->id)
                ->with('success', 'Transaction completed successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transaction failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the receipt for a completed sale
     */
    public function showReceipt($id)
    {
        $sale = Sale::with('saleDetails.product')->findOrFail($id);
        return view('cashier.receipt', compact('sale'));
    }
} 