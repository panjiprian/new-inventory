<?php


namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductSupplies;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class ReportController extends Controller
{
    public function index()
    {

        $report = ProductSupplies::with(['product', 'supplier', 'user'])
        ->orderBy('date', 'desc') // Urutkan berdasarkan tanggal terbaru
        ->get();

        return view('dashboard.report.index', compact('report'));
    }
    public function downloadReport()
    {
        // Ambil data report
        $report = ProductSupplies::with(['product', 'supplier', 'user'])->orderBy('date', 'desc')->get();

        // Render view menjadi PDF
        $pdf = FacadePdf::loadView('dashboard.report.reportPdf', compact('report'))->setPaper('a4', 'landscape');

        // Unduh PDF
        return $pdf->download('report.pdf');
    }

    public function viewReport()
{
    // Ambil data report
    $report = ProductSupplies::with(['product', 'supplier', 'user'])->orderBy('date', 'desc')->get();

    // Render view menjadi PDF
    $pdf = FacadePdf::loadView('dashboard.report.reportPdf', compact('report'))->setPaper('a4', 'landscape');

    // Stream PDF ke browser
    return $pdf->stream('report.pdf');
}

}
