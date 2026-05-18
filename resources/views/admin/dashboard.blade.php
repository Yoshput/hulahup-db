<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hulahup Kantin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .bg-midnight { background-color: #122C4F; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-72 bg-midnight text-white flex flex-col py-9 px-7 shrink-0 shadow-2xl">
        <div class="mb-10 px-2">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-black italic text-[#FBF9E4] tracking-tighter leading-none">Hulahup.</h1>
                    <p class="text-[9px] opacity-50 tracking-[0.2em] font-bold uppercase">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 space-y-3">
            <a href="#" class="bg-[#FBF9E4] text-[#122C4F] flex items-center gap-4 px-5 py-4 rounded-[22px] font-bold transition-all">
                <i class="fa-solid fa-chart-line text-lg"></i> 
                <span>Dashboard</span>
            </a>
            <hr class="opacity-20">
            <a href="/admin/menus" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-utensils text-lg"></i> 
                <span>Menu Management</span>
            </a>
            <a href="/admin/users" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-users text-lg"></i> 
                <span>Users Management</span>
            </a>
            <a href="/admin/vouchers" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-ticket text-lg"></i> 
                <span>Vouchers Management</span>
            </a>
            <hr class="opacity-20">
            <a href="/home" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-utensils text-lg"></i> 
                <span>Kembali ke Home</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="flex items-center gap-4 text-red-500 font-bold px-4 py-3 rounded-2xl hover:bg-red-50/20 transition">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </nav>

        <div class="p-4 bg-[#1a3a5a] rounded-[20px]">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-[#4A7292] text-white flex items-center justify-center rounded-full text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                    <p class="text-xs text-gray-400">👑 Admin</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 h-screen overflow-y-auto p-10 bg-slate-50">
        <!-- Header -->
        <header class="mb-10">
            <h1 class="text-4xl font-black text-[#122C4F]">Dashboard Admin 👑</h1>
            <p class="text-slate-500 font-medium text-sm mt-2">Pantau semua aktivitas kantin Hulahup secara real-time</p>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <!-- Total Orders -->
            <div class="stat-card bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Total Pesanan</p>
                        <h3 class="text-3xl font-black text-[#122C4F]">{{ $totalOrders }}</h3>
                        <p class="text-xs text-green-600 font-bold mt-2">✅ Real-time data</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-2xl">
                        📦
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="stat-card bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Pendapatan</p>
                        <h3 class="text-3xl font-black text-[#122C4F]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <p class="text-xs text-green-600 font-bold mt-2">✅ Updated realtime</p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-2xl">
                        💰
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="stat-card bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Pengguna Aktif</p>
                        <h3 class="text-3xl font-black text-[#122C4F]">{{ $activeUsers }}</h3>
                        <p class="text-xs text-blue-600 font-bold mt-2">{{ $activeUsers }} terdaftar</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-2xl">
                        👥
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="stat-card bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium mb-1">Pesanan Pending</p>
                        <h3 class="text-3xl font-black text-orange-600">{{ $pendingOrders }}</h3>
                        <p class="text-xs text-orange-600 font-bold mt-2">Perlu diproses</p>
                    </div>
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-2xl">
                        ⏳
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table & Chart -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Recent Orders -->
            <div class="xl:col-span-2 bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-[#122C4F]">Pesanan Terbaru</h2>
                    <select class="px-4 py-2 border border-slate-200 rounded-lg text-sm text-slate-600">
                        <option>Semua Status</option>
                        <option>Pending</option>
                        <option>Diproses</option>
                        <option>Selesai</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-bold">
                                <th class="text-left py-3 px-4">Order ID</th>
                                <th class="text-left py-3 px-4">Pelanggan</th>
                                <th class="text-left py-3 px-4">Item</th>
                                <th class="text-left py-3 px-4">Total</th>
                                <th class="text-left py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Display real orders from database --}}
                            @forelse($recentOrders as $order)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="py-4 px-4"><span class="font-bold text-[#122C4F]">{{ $order->order_number }}</span></td>
                                <td class="py-4 px-4">{{ $order->user->name }}</td>
                                <td class="py-4 px-4">
                                    @foreach($order->items as $item)
                                        {{ $item['name'] }}@if(!$loop->last)<br>@endif
                                    @endforeach
                                </td>
                                <td class="py-4 px-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-4">
                                    @if($order->status === 'pending')
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                            ⏳ Pending
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                            🔄 Diproses
                                        </span>
                                    @elseif($order->status === 'completed')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                            ✅ Selesai
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                            ❌ Dibatalkan
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 px-4 text-center text-slate-500">
                                    <i class="fa-solid fa-inbox text-3xl mb-3 block opacity-50"></i>
                                    Belum ada pesanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <button class="mt-6 w-full py-3 bg-slate-100 text-slate-600 rounded-lg font-bold hover:bg-slate-200 transition">
                    Lihat Semua Pesanan →
                </button>
            </div>

            <!-- Chart & Summary -->
            <div class="space-y-6">
                <!-- Menu Populer -->
                <div class="bg-white rounded-[25px] p-6 shadow-sm border border-slate-100">
                    <h2 class="text-lg font-bold text-[#122C4F] mb-6">Menu Paling Populer</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-slate-700">Mie Ayam Bakso</p>
                                <p class="text-xs text-slate-500">156 pesanan</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                🍜
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-slate-700">Seblak Jeletot</p>
                                <p class="text-xs text-slate-500">142 pesanan</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                🌶️
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-slate-700">Ramen Shoyu</p>
                                <p class="text-xs text-slate-500">128 pesanan</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                🍲
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-slate-700">Es Teh Manis</p>
                                <p class="text-xs text-slate-500">115 pesanan</p>
                            </div>
                            <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center">
                                🧋
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-[#5B88B2] to-[#122C4F] rounded-[25px] p-6 text-white shadow-sm">
                    <h2 class="text-lg font-bold mb-4">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <button class="w-full bg-white/20 hover:bg-white/30 py-2 rounded-lg font-bold transition">
                            📊 Export Laporan
                        </button>
                        <button class="w-full bg-white/20 hover:bg-white/30 py-2 rounded-lg font-bold transition">
                            📧 Email Notifikasi
                        </button>
                        <button class="w-full bg-white/20 hover:bg-white/30 py-2 rounded-lg font-bold transition">
                            ⚙️ Pengaturan Sistem
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 text-center text-slate-500 text-sm">
            <p>Hulahup Admin Dashboard • Last updated: <span id="lastUpdate"></span></p>
        </footer>
    </main>

    <script>
        // Update last updated time
        function updateTime() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('lastUpdate').textContent = time;
        }
        
        updateTime();
        setInterval(updateTime, 1000);
    </script>

</body>
</html>
