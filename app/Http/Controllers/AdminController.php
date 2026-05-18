<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get all statistics for dashboard
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $activeUsers = User::where('role', '!=', 'admin')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Get recent orders with user info
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Get popular menu items (most ordered)
        $popularMenus = Order::with('user')
            ->selectRaw("JSON_EXTRACT(items, '$[*].name') as menu_names, COUNT(*) as order_count")
            ->groupBy('menu_names')
            ->orderByDesc('order_count')
            ->limit(4)
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'activeUsers' => $activeUsers,
            'pendingOrders' => $pendingOrders,
            'recentOrders' => $recentOrders,
            'popularMenus' => $popularMenus,
        ]);
    }
}
