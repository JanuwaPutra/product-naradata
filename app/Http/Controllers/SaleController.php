<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('product')->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);
        
        // Get the product
        $product = Product::findOrFail($validated['product_id']);
        
        // Check if enough stock
        if ($product->stock < $validated['quantity']) {
            return back()->withInput()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }
        
        // Calculate prices
        $price_per_item = $product->price;
        $total_price = $price_per_item * $validated['quantity'];
        
        try {
            // Simpan stok awal untuk logging
            $initialStock = $product->stock;
            
            DB::beginTransaction();
            
            // Create sale record
            $sale = Sale::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price_per_item' => $price_per_item,
                'total_price' => $total_price,
                'sale_date' => $validated['sale_date'],
            ]);
            
            // Update product stock - pastikan stok berkurang
            $product->stock = $product->stock - $validated['quantity'];
            $product->save();
            
            // Log perubahan stok untuk debugging
            \Log::info('Penjualan berhasil: ID Produk=' . $product->id . 
                       ', Nama=' . $product->name . 
                       ', Stok Awal=' . $initialStock . 
                       ', Jumlah Terjual=' . $validated['quantity'] . 
                       ', Stok Akhir=' . $product->stock);
            
            DB::commit();
            
            return redirect()->route('sales.index')
                ->with('success', 'Penjualan berhasil dicatat dan stok produk telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat mencatat penjualan: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat mencatat penjualan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);
        
        $originalQuantity = $sale->quantity;
        $originalProductId = $sale->product_id;
        
        // Get the new product
        $newProduct = Product::findOrFail($validated['product_id']);
        
        try {
            DB::beginTransaction();
            
            // Simpan stok awal untuk logging
            $originalProduct = Product::findOrFail($originalProductId);
            $originalProductStock = $originalProduct->stock;
            $newProductStock = $newProduct->stock;
            
            // Restore stock to original product if different
            if ($originalProductId != $validated['product_id']) {
                // Kembalikan stok ke produk asli
                $originalProduct->stock = $originalProduct->stock + $originalQuantity;
                $originalProduct->save();
                
                \Log::info('Mengembalikan stok ke produk asli: ID=' . $originalProductId . 
                           ', Stok Awal=' . $originalProductStock . 
                           ', Jumlah Dikembalikan=' . $originalQuantity . 
                           ', Stok Akhir=' . $originalProduct->stock);
                
                // Check if enough stock in new product
                if ($newProduct->stock < $validated['quantity']) {
                    DB::rollBack();
                    throw new \Exception('Stok tidak mencukupi pada produk baru.');
                }
                
                // Deduct from new product
                $newProduct->stock = $newProduct->stock - $validated['quantity'];
                $newProduct->save();
                
                \Log::info('Mengurangi stok produk baru: ID=' . $newProduct->id . 
                           ', Stok Awal=' . $newProductStock . 
                           ', Jumlah Terjual=' . $validated['quantity'] . 
                           ', Stok Akhir=' . $newProduct->stock);
            } else {
                // Same product, adjust stock based on quantity difference
                $quantityDifference = $validated['quantity'] - $originalQuantity;
                
                if ($quantityDifference > 0) {
                    // Need more stock
                    if ($newProduct->stock < $quantityDifference) {
                        DB::rollBack();
                        throw new \Exception('Stok tambahan tidak mencukupi.');
                    }
                    
                    $newProduct->stock = $newProduct->stock - $quantityDifference;
                    $newProduct->save();
                    
                    \Log::info('Mengurangi stok tambahan: ID=' . $newProduct->id . 
                               ', Stok Awal=' . $newProductStock . 
                               ', Jumlah Tambahan=' . $quantityDifference . 
                               ', Stok Akhir=' . $newProduct->stock);
                } elseif ($quantityDifference < 0) {
                    // Returning stock
                    $newProduct->stock = $newProduct->stock + abs($quantityDifference);
                    $newProduct->save();
                    
                    \Log::info('Mengembalikan sebagian stok: ID=' . $newProduct->id . 
                               ', Stok Awal=' . $newProductStock . 
                               ', Jumlah Dikembalikan=' . abs($quantityDifference) . 
                               ', Stok Akhir=' . $newProduct->stock);
                }
            }
            
            // Calculate prices
            $price_per_item = $newProduct->price;
            $total_price = $price_per_item * $validated['quantity'];
            
            // Update sale record
            $sale->update([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price_per_item' => $price_per_item,
                'total_price' => $total_price,
                'sale_date' => $validated['sale_date'],
            ]);
            
            DB::commit();
            
            return redirect()->route('sales.show', $sale)
                ->with('success', 'Penjualan berhasil diperbarui dan stok produk telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat memperbarui penjualan: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            
            // Restore stock to product
            $product = Product::findOrFail($sale->product_id);
            $initialStock = $product->stock;
            
            $product->stock = $product->stock + $sale->quantity;
            $product->save();
            
            \Log::info('Mengembalikan stok karena penjualan dihapus: ID Produk=' . $product->id . 
                       ', Nama=' . $product->name . 
                       ', Stok Awal=' . $initialStock . 
                       ', Jumlah Dikembalikan=' . $sale->quantity . 
                       ', Stok Akhir=' . $product->stock);
            
            // Delete sale record
            $sale->delete();
            
            DB::commit();
            
            return redirect()->route('sales.index')
                ->with('success', 'Penjualan berhasil dihapus dan stok produk telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat menghapus penjualan: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus penjualan: ' . $e->getMessage()]);
        }
    }
}
