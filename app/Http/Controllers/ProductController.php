<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');
        
        $query = Product::query();
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        $products = $query->latest()->paginate($perPage);
        
        // Handle AJAX request
        if ($request->ajax()) {
            $view = view('products.partials.product-table', compact('products'))->render();
            
            return response()->json([
                'html' => $view,
                'pagination' => $products->links()->toHtml(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'total' => $products->total()
            ]);
        }
        
        return view('products.index', compact('products'));
    }

    /**
     * Export products to Excel
     */
    public function exportExcel(Request $request)
    {
        $search = $request->input('search', '');
        return Excel::download(new ProductsExport($search), 'products.xlsx');
    }

    /**
     * Export products to PDF
     */
    public function exportPdf(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = Product::query();
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        $products = $query->latest()->get();
        
        $pdf = PDF::loadView('products.pdf', compact('products'));
        return $pdf->download('products.pdf');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('products.import');
    }
    
    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_barang.xlsx"',
        ];
        
        // Create a simple export class for the template
        $export = new class implements FromArray, WithHeadings {
            public function array(): array
            {
                return [
                    [
                        'Contoh Barang 1',
                        'Deskripsi barang 1',
                        10000,
                        50,
                    ],
                    [
                        'Contoh Barang 2',
                        'Deskripsi barang 2',
                        15000,
                        25,
                    ]
                ];
            }
            
            public function headings(): array
            {
                return [
                    'nama_barang',
                    'deskripsi',
                    'harga',
                    'stok',
                ];
            }
        };
        
        return Excel::download($export, 'template_import_barang.xlsx');
    }
    
    /**
     * Import products from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        
        try {
            Excel::import(new ProductsImport, $request->file('file'));
            
            return redirect()->route('products.index')
                ->with('success', 'Data barang berhasil diimport.');
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
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        
        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);
        
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
