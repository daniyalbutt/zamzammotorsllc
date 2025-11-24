<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $stats = [
            'total_sales' => Invoice::where('status', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_revenue' => Invoice::where('status', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('vehicle_price'),
            'pending_invoices' => Invoice::where('status', '!=', 'Paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'vehicles_sold' => Vehicle::where('availability', 'Sold Out')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->count(),
        ];

        $sales_by_agent = Invoice::with(['customer.assignedAgent'])
            ->where('status', 'Paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($invoice) {
                return $invoice->customer->assignedAgent->name ?? 'Unassigned';
            })
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'revenue' => $group->sum('vehicle_price')
                ];
            });

        return view('reports.sales', compact('stats', 'sales_by_agent', 'startDate', 'endDate'));
    }

    public function hr(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $stats = [
            'total_employees' => Employee::count(),
            'total_attendance' => Attendance::whereBetween('date', [$startDate, $endDate])->count(),
            'present_count' => Attendance::where('status', 'Present')
                ->whereBetween('date', [$startDate, $endDate])
                ->count(),
            'late_count' => Attendance::where('status', 'Late')
                ->whereBetween('date', [$startDate, $endDate])
                ->count(),
            'pending_leaves' => Leave::where('status', 'Pending')->count(),
        ];

        return view('reports.hr', compact('stats', 'startDate', 'endDate'));
    }
}
