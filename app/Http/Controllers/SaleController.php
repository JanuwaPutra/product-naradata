<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Exports\SalesExport;
use App\Imports\SalesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Sale::with('saleDetails.product');
        
        // Apply date range filter if provided
        if (!empty($startDate)) {
            $query->whereDate('transaction_date', '>=', $startDate);
        }
        
        if (!empty($endDate)) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('saleDetails.product', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('total_amount', 'like', "%{$search}%")
                ->orWhereRaw("DATE_FORMAT(transaction_date, '%d %b %Y') LIKE ?", ["%{$search}%"])
                ->orWhere('cashier_name', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        
        $sales = $query->latest()->paginate($perPage);
        
        // If it's an AJAX request, append the query parameters
        if ($request->ajax()) {
            $sales->appends([
                'search' => $search,
                'per_page' => $perPage,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            
            $view = view('sales.partials.sale-table', compact('sales'))->render();
            
            return response()->json([
                'html' => $view,
                'pagination' => $sales->links()->toHtml(),
                'from' => $sales->firstItem(),
                'to' => $sales->lastItem(),
                'total' => $sales->total()
            ]);
        }
        
        return view('sales.index', compact('sales'));
    }
    
    /**
     * Export sales to Excel
     */
    public function exportExcel(Request $request)
    {
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $filename = 'sales';
        if ($startDate && $endDate) {
            $filename = "sales_{$startDate}_to_{$endDate}";
        } elseif ($startDate) {
            $filename = "sales_from_{$startDate}";
        } elseif ($endDate) {
            $filename = "sales_until_{$endDate}";
        }
        
        return Excel::download(new SalesExport($search, $startDate, $endDate), $filename . '.xlsx');
    }

    /**
     * Export sales to PDF
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search', '');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = Sale::with('saleDetails.product');
        
        // Apply date range filter if provided
        if (!empty($startDate)) {
            $query->whereDate('transaction_date', '>=', $startDate);
        }
        
        if (!empty($endDate)) {
            $query->whereDate('transaction_date', '<=', $endDate);
        }
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('saleDetails.product', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhere('total_amount', 'like', "%{$search}%")
                ->orWhere('cashier_name', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        
        $sales = $query->latest()->get();
        
        $filename = 'sales';
        if ($startDate && $endDate) {
            $filename = "sales_{$startDate}_to_{$endDate}";
        } elseif ($startDate) {
            $filename = "sales_from_{$startDate}";
        } elseif ($endDate) {
            $filename = "sales_until_{$endDate}";
        }
        
        $pdf = PDF::loadView('sales.pdf', compact('sales', 'startDate', 'endDate'));
        return $pdf->download($filename . '.pdf');
    }
    
    /**
     * Show import form
     */
    public function importForm()
    {
        return view('sales.import');
    }
    
    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        // Get available products for reference
        $products = Product::where('stock', '>', 0)->pluck('name')->take(2)->toArray();
        $productNames = !empty($products) ? $products : ['Contoh Barang 1', 'Contoh Barang 2'];
        
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_penjualan.xlsx"',
        ];
        
        // Create a simple export class for the template
        $export = new class($productNames, $today, $yesterday) implements FromArray, WithHeadings {
            protected $productNames;
            protected $today;
            protected $yesterday;
            
            public function __construct($productNames, $today, $yesterday)
            {
                $this->productNames = $productNames;
                $this->today = $today;
                $this->yesterday = $yesterday;
            }
            
            public function array(): array
            {
                return [
                    [
                        $this->productNames[0],
                        2,
                        $this->today,
                    ],
                    [
                        $this->productNames[1] ?? $this->productNames[0],
                        1,
                        $this->yesterday,
                    ]
                ];
            }
            
            public function headings(): array
            {
                return [
                    'nama_barang',
                    'jumlah',
                    'tanggal',
                ];
            }
        };
        
        return Excel::download($export, 'template_import_penjualan.xlsx');
    }
    
    /**
     * Import sales from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        
        try {
            Excel::import(new SalesImport, $request->file('file'));
            
            return redirect()->route('sales.index')
                ->with('success', 'Data penjualan berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = 'Baris ke-' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            
            return back()->withErrors($errors);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
            'transaction_date' => 'required|date',
            'cashier_name' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
        ]);
        
        // Get the product
        $product = Product::findOrFail($validated['product_id']);
        
        // Check if enough stock
        if ($product->stock < $validated['quantity']) {
            return back()->withInput()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }
        
        // Calculate prices
        $price = $product->price;
        $subtotal = $price * $validated['quantity'];
        
        try {
            // Simpan stok awal untuk logging
            $initialStock = $product->stock;
            
            DB::beginTransaction();
            
            // Create sale record - ensure cashier_name and customer_name are strings
            $sale = Sale::create([
                'cashier_name' => (string)$validated['cashier_name'],
                'customer_name' => !empty($validated['customer_name']) ? (string)$validated['customer_name'] : 'Guest',
                'transaction_date' => $validated['transaction_date'],
                'total_amount' => $subtotal,
            ]);
            
            // Create sale detail
            $sale->saleDetails()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'subtotal' => $subtotal,
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
        $sale->load('saleDetails.product');
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale->load('saleDetails.product');
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
            'transaction_date' => 'required|date',
            'cashier_name' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
        ]);
        
        // For simplicity, we'll assume only one product per sale in this update method
        $saleDetail = $sale->saleDetails->first();
        
        if (!$saleDetail) {
            return back()->withInput()->withErrors(['error' => 'Detail penjualan tidak ditemukan.']);
        }
        
        $originalQuantity = $saleDetail->quantity;
        $originalProductId = $saleDetail->product_id;
        
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
            $price = $newProduct->price;
            $subtotal = $price * $validated['quantity'];
            
            // Update sale detail
            $saleDetail->update([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'subtotal' => $subtotal,
            ]);
            
            // Update sale record - ensure cashier_name and customer_name are strings
            $sale->update([
                'cashier_name' => (string)$validated['cashier_name'],
                'customer_name' => !empty($validated['customer_name']) ? (string)$validated['customer_name'] : 'Guest',
                'transaction_date' => $validated['transaction_date'],
                'total_amount' => $subtotal,
            ]);
            
            DB::commit();
            
            return redirect()->route('sales.show', $sale)
                ->with('success', 'Data penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat memperbarui penjualan: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui penjualan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            
            // Return stock for each sale detail
            foreach ($sale->saleDetails as $detail) {
                $product = Product::findOrFail($detail->product_id);
                $product->stock += $detail->quantity;
                $product->save();
                
                \Log::info('Mengembalikan stok saat hapus penjualan: ID Produk=' . $product->id . 
                           ', Jumlah Dikembalikan=' . $detail->quantity . 
                           ', Stok Akhir=' . $product->stock);
            }
            
            // Delete the sale (will cascade to sale details)
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
