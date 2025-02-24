<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupplies;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $countProducts = Product::count();
        $countProductIncome = ProductSupplies::where('type', 'income')->count();
        $countProductOutcome = ProductSupplies::where('type', 'outcome')->count();

        // Ambil tanggal filter (default: 7 hari terakhir)
        $startDate = $request->input('start_date', Carbon::now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Query Stock Trends berdasarkan tanggal
        $stockTrends = ProductSupplies::selectRaw('DATE(date) as date,
                SUM(CASE WHEN type = "income" THEN quantity ELSE 0 END) as total_in,
                SUM(CASE WHEN type = "outcome" THEN quantity ELSE 0 END) as total_out')
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('dashboard.overview.index', [
            'countProducts' => $countProducts,
            'countProductIncome' => $countProductIncome,
            'countProductOutcome' => $countProductOutcome,
            'stockTrends' => $stockTrends,
        ]);
    }
}
