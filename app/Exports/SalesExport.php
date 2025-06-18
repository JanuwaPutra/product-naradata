<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $search;
    protected $startDate;
    protected $endDate;
    
    public function __construct($search = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Sale::with('saleDetails.product');
        
        // Apply date range filter if provided
        if (!empty($this->startDate)) {
            $query->whereDate('transaction_date', '>=', $this->startDate);
        }
        
        if (!empty($this->endDate)) {
            $query->whereDate('transaction_date', '<=', $this->endDate);
        }
        
        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('saleDetails.product', function($subQ) {
                    $subQ->where('name', 'like', "%{$this->search}%");
                })
                ->orWhere('id', 'like', "%{$this->search}%")
                ->orWhere('total_amount', 'like', "%{$this->search}%")
                ->orWhere('cashier_name', 'like', "%{$this->search}%")
                ->orWhere('customer_name', 'like', "%{$this->search}%");
            });
        }
        
        return $query->latest()->get();
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tanggal',
            'Kasir',
            'Pelanggan',
            'Produk',
            'Jumlah Item',
            'Total',
        ];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // Get product names
        $products = $row->saleDetails->map(function($detail) {
            return $detail->product->name . ' (' . $detail->quantity . ')';
        })->join(', ');
        
        // Get total items
        $totalItems = $row->saleDetails->sum('quantity');
        
        return [
            $row->id,
            $row->transaction_date->format('d/m/Y'),
            $row->cashier_name,
            $row->customer_name,
            $products,
            $totalItems,
            'Rp ' . number_format($row->total_amount, 0, ',', '.'),
        ];
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 