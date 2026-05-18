<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Hulahup</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-midnight { background-color: #122C4F; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-midnight text-white py-8 px-6 shrink-0 shadow-lg fixed h-screen overflow-y-auto">
            <div class="mb-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" class="w-10 h-10">
                    <div>
                        <h1 class="text-xl font-black italic text-[#FBF9E4]">Hulahup.</h1>
                        <p class="text-[8px] opacity-50 tracking-widest font-bold">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="space-y-3 mb-8">
                <a href="/admin/dashboard" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/menus" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                    <i class="fa-solid fa-utensils"></i>
                    <span>Menu Management</span>
                </a>
                <a href="/admin/users" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                    <i class="fa-solid fa-users"></i>
                    <span>Users Management</span>
                </a>
                <a href="/admin/vouchers" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                    <i class="fa-solid fa-ticket"></i>
                    <span>Vouchers Management</span>
                </a>
            </nav>

            <hr class="opacity-20 mb-6">

            <div class="space-y-3">
                <a href="/home" class="flex items-center gap-4 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">
                    <i class="fa-solid fa-home"></i>
                    <span>Kembali ke Home</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="flex items-center gap-4 px-4 py-3 rounded-lg text-red-300 hover:bg-red-500/20 hover:text-red-200 transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 overflow-y-auto">
            <!-- Header -->
            <div class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-bold text-[#122C4F]">@yield('title', 'Admin Panel')</h2>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-slate-600 text-sm">{{ Auth::user()->name }}</span>
                    <div class="w-10 h-10 bg-[#122C4F] text-white rounded-full flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-0">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
