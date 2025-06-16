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
        $query = Sale::with('product');
        
        // Apply date range filter if provided
        if (!empty($this->startDate)) {
            $query->whereDate('sale_date', '>=', $this->startDate);
        }
        
        if (!empty($this->endDate)) {
            $query->whereDate('sale_date', '<=', $this->endDate);
        }
        
        // Apply search filter if provided
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('product', function($subQ) {
                    $subQ->where('name', 'like', "%{$this->search}%");
                })
                ->orWhere('id', 'like', "%{$this->search}%")
                ->orWhere('total_price', 'like', "%{$this->search}%");
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
            'Nama Barang',
            'Jumlah',
            'Harga Satuan',
            'Total',
        ];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->sale_date->format('d/m/Y'),
            $row->product->name,
            $row->quantity,
            'Rp ' . number_format($row->price_per_item, 0, ',', '.'),
            'Rp ' . number_format($row->total_price, 0, ',', '.'),
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