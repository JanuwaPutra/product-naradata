<?php

namespace App\Imports;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;
    
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $row) {
                // Find the product
                $product = Product::where('name', $row['nama_barang'])->first();
                
                if (!$product) {
                    // Skip this row if product not found
                    continue;
                }
                
                // Check if enough stock
                if ($product->stock < $row['jumlah']) {
                    // Skip this row if not enough stock
                    continue;
                }
                
                // Calculate price
                $price = $product->price;
                $subtotal = $price * $row['jumlah'];
                
                // Create sale record
                $sale = Sale::create([
                    'cashier_name' => 'Import',
                    'customer_name' => 'Import',
                    'transaction_date' => Carbon::parse($row['tanggal'])->format('Y-m-d'),
                    'total_amount' => $subtotal,
                ]);
                
                // Create sale detail
                $saleDetail = SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $row['jumlah'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                
                // Update product stock
                $product->stock -= $row['jumlah'];
                $product->save();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ];
    }
    
    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nama_barang.required' => 'Kolom nama barang wajib diisi',
            'nama_barang.max' => 'Nama barang maksimal 255 karakter',
            'jumlah.required' => 'Kolom jumlah wajib diisi',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal.required' => 'Kolom tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
        ];
    }
    
    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
} 