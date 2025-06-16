<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total products
        $totalProducts = Product::count();
        
        // Low stock products (less than 10 in stock)
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        
        // Total sales today
        $todaySales = Sale::whereDate('sale_date', Carbon::today())->sum('total_price');
        
        // Total sales this month
        $thisMonthSales = Sale::whereYear('sale_date', Carbon::now()->year)
                             ->whereMonth('sale_date', Carbon::now()->month)
                             ->sum('total_price');
        
        // Top selling products
        $topSellingProducts = Product::select('products.id', 'products.name', DB::raw('SUM(sales.quantity) as total_sold'))
                                    ->leftJoin('sales', 'products.id', '=', 'sales.product_id')
                                    ->groupBy('products.id', 'products.name')
                                    ->orderBy('total_sold', 'desc')
                                    ->take(5)
                                    ->get();
        
        // Recent sales
        $recentSales = Sale::with('product')->latest()->take(5)->get();
        
        // Weekly sales data for chart (last 7 days)
        $weeklySalesData = $this->getWeeklySalesData();
        
        // Monthly sales data for chart (last 12 months)
        $monthlySalesData = $this->getMonthlySalesData();
        
        // Stock levels for top products
        $stockLevelsData = $this->getStockLevelsData();
        
        return view('dashboard', compact(
            'totalProducts', 
            'lowStockProducts',
            'todaySales', 
            'thisMonthSales', 
            'topSellingProducts', 
            'recentSales',
            'weeklySalesData',
            'monthlySalesData',
            'stockLevelsData'
        ));
    }
    
    /**
     * Get weekly sales data for the last 7 days
     */
    private function getWeeklySalesData()
    {
        $days = collect(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
        
        // Get sales for the last 7 days
        $salesData = Sale::selectRaw('DATE(sale_date) as date, SUM(total_price) as total')
            ->where('sale_date', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
            });
            
        // Map to Indonesian day names and ensure all days have values
        $result = $days->map(function ($dayName, $index) use ($salesData) {
            // Adjust index to match Carbon's day of week (0=Sunday, 1=Monday, etc.)
            $adjustedIndex = ($index + 1) % 7;
            
            return [
                'x' => $dayName,
                'y' => $salesData->get($adjustedIndex) ? $salesData->get($adjustedIndex)->total : 0
            ];
        });
        
        return $result->values();
    }
    
    /**
     * Get monthly sales data for the last 12 months
     */
    private function getMonthlySalesData()
    {
        $months = collect(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']);
        
        // Get sales for the last 12 months
        $salesData = Sale::selectRaw('MONTH(sale_date) as month, YEAR(sale_date) as year, SUM(total_price) as total')
            ->where('sale_date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->month - 1; // Convert to 0-based index
            });
            
        // Ensure all months have values
        $result = $months->map(function ($monthName, $index) use ($salesData) {
            return [
                'x' => $monthName,
                'y' => $salesData->get($index) ? $salesData->get($index)->total : 0
            ];
        });
        
        return $result->values();
    }
    
    /**
     * Get stock levels data for top products
     */
    private function getStockLevelsData()
    {
        // Get top 10 products by sales
        $topProducts = Product::select('id', 'name', 'stock')
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();
            
        $productNames = $topProducts->pluck('name')->toArray();
        $stockLevels = $topProducts->pluck('stock')->toArray();
        
        // Define stock threshold colors
        $colors = $topProducts->map(function($product) {
            if ($product->stock <= 5) {
                return '#e63946'; // Red for critical stock
            } elseif ($product->stock <= 20) {
                return '#f72585'; // Pink for low stock
            } else {
                return '#4cc9f0'; // Blue for good stock
            }
        })->toArray();
        
        return [
            'products' => $productNames,
            'stocks' => $stockLevels,
            'colors' => $colors
        ];
    }
}
