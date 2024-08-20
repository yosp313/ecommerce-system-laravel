<?php

namespace App\Jobs;

use App\Exports\AdminReport;
use App\Mail\DailyOrdersReportMail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class DailyOrdersReportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = Order::with(['orderItems', 'address', 'paymentMethod', 'orderItems.product:id,name,price'])->whereDate('created_at', Carbon::yesterday())->get();

        $fileName = "daily_orders_report_" . Carbon::now()->format('Y-m-d') . ".csv";
        Excel::store(new AdminReport($orders), $fileName);

        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total');

        $admins = User::where('is_admin', 1)->get();

        foreach ($admins as $admin) {
            Mail::to($admin)->send(new DailyOrdersReportMail($fileName, $totalOrders, $totalSales));
        }
    }
}
