<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the compact Kopi Senja dashboard.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // 1. Summary Metrics
        $todayOrders = Order::whereDate('created_at', $today)->where('status', 'completed')->get();
        $yesterdayOrders = Order::whereDate('created_at', $yesterday)->where('status', 'completed')->get();

        $todaySales = (float) $todayOrders->sum('total');
        $yesterdaySales = (float) $yesterdayOrders->sum('total');
        $salesTrend = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : 12.5;

        $todayCount = $todayOrders->count();
        $yesterdayCount = $yesterdayOrders->count();
        $countTrend = $yesterdayCount > 0 ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 1) : 8.0;

        $todayItemsSold = (int) OrderItem::whereHas('order', function ($q) use ($today) {
            $q->whereDate('created_at', $today)->where('status', 'completed');
        })->sum('quantity');

        $averageOrderValue = $todayCount > 0 ? round($todaySales / $todayCount) : 0;

        // 2. Sales Chart Breakdown (Hourly for today)
        $hourlyBuckets = [
            '08:00' => 0,
            '10:00' => 0,
            '12:00' => 0,
            '14:00' => 0,
            '16:00' => 0,
            '18:00' => 0,
            '20:00' => 0,
            '22:00' => 0,
        ];

        foreach ($todayOrders as $order) {
            $hour = (int) $order->created_at->format('H');
            if ($hour < 10) {
                $hourlyBuckets['08:00'] += (float) $order->total;
            } elseif ($hour < 12) {
                $hourlyBuckets['10:00'] += (float) $order->total;
            } elseif ($hour < 14) {
                $hourlyBuckets['12:00'] += (float) $order->total;
            } elseif ($hour < 16) {
                $hourlyBuckets['14:00'] += (float) $order->total;
            } elseif ($hour < 18) {
                $hourlyBuckets['16:00'] += (float) $order->total;
            } elseif ($hour < 20) {
                $hourlyBuckets['18:00'] += (float) $order->total;
            } elseif ($hour < 22) {
                $hourlyBuckets['20:00'] += (float) $order->total;
            } else {
                $hourlyBuckets['22:00'] += (float) $order->total;
            }
        }

        // 3. Weekly Sales Breakdown (last 7 days)
        $weeklyBuckets = [];
        for ($d = 6; $d >= 0; $d--) {
            $dayDate = Carbon::today()->subDays($d);
            $dayName = $dayDate->locale('id')->isoFormat('ddd');
            $daySales = (float) Order::whereDate('created_at', $dayDate)
                ->where('status', 'completed')
                ->sum('total');
            $weeklyBuckets[] = [
                'label' => $dayName,
                'total' => $daySales,
            ];
        }

        // 4. Latest Transactions
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(6)
            ->get();

        // 5. Top Selling Products
        $topProducts = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue')
        )
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $cashier = auth()->user() ?? User::first();

        return view('dashboard.index', compact(
            'todaySales',
            'salesTrend',
            'todayCount',
            'countTrend',
            'todayItemsSold',
            'averageOrderValue',
            'hourlyBuckets',
            'weeklyBuckets',
            'recentOrders',
            'topProducts',
            'cashier'
        ));
    }
}
