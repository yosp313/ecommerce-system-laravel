<?php

namespace App\Console\Commands;

use App\Exports\AdminReport;
use App\Mail\DailyOrdersReportMail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class DailyOrdersReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-orders-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get the orders from the past 24 hours only
        $orders = Order::with(['orderItems', 'address', 'paymentMethod', 'orderItems.product:id,name,price'])->whereDate('created_at', Carbon::yesterday())->get();

        $fileName = "daily_orders_report_" . Carbon::now()->format('Y-m-d') . ".csv";
        Excel::store(new AdminReport($orders), $fileName);

        $totalOrders = $orders->count();
        $totalSales = $orders->sum('total');

        $admins = User::where('is_admin', 1)->get();

        foreach ($admins as $admin) {
            Mail::to($admin)->send(new DailyOrdersReportMail($fileName, $totalOrders, $totalSales));
        }

        $this->info('Daily orders report has been sent to all admins');
    }
}
