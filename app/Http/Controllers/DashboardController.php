<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $chartData = $this->dashboardService->getChartData();

        return view('admin.dashboard', compact('stats', 'chartData'));
    }
}
