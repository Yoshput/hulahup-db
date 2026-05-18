<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hulahup Desktop - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FBF9E4; } /* Pearl Perfect */
        .sidebar-active { background-color: #FBF9E4; color: #122C4F !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .food-card:hover img { transform: scale(1.1); }
        
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .cart-item-anim { animation: bounce-in 0.4s ease-out; }
        .bg-midnight { background-color: #122C4F; }
        .text-pearl { color: #FBF9E4; }

        /* Tambahkan ini di dalam tag <style> kamu */
        main {
            max-width: 1400px; /* Membatasi agar tidak terlalu lebar di monitor besar */
            margin: 0 auto;
        }

        /* Biar kartu makanan ukurannya konsisten */
        .food-card img {
            transition: all 0.3s ease;
        }

        /* Tambahkan ini di dalam tag <style> kamu */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.4s ease forwards;
        }
    </style>
</head>
<body class="h-screen flex overflow-hidden">

    <aside class="w-72 bg-midnight text-white flex flex-col py-9 px-7 shrink-0 shadow-2xl z-20">
        <div class="mb-10 px-2">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-2xl font-black italic text-[#FBF9E4] tracking-tighter leading-none">Hulahup.</h1>
                    <p class="text-[9px] opacity-50 tracking-[0.2em] font-bold uppercase">Kantin Tel-U</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 space-y-3">
            <a href="javascript:void(0)" class="sidebar-active flex items-center gap-4 px-5 py-4 rounded-[22px] font-bold transition-all duration-300">
                <i class="fa-solid fa-house text-lg"></i> 
                <span>Dashboard</span>
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="/admin/dashboard" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-amber-300 hover:bg-amber-500/20 hover:text-amber-200 transition-all border-l-4 border-amber-400">
                <i class="fa-solid fa-crown text-lg"></i> 
                <span>Admin Dashboard</span>
            </a>
            @endif
            <a href="#menuSection" class="flex items-center gap-4 px-5 py-4 rounded-[22px] font-medium text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-utensils text-lg"></i> 
                <span>Menu Kantin</span>
            </a>
            <a href="/history" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-blue-50 hover:text-blue-900 rounded-xl transition">
                <i class="fas fa-history"></i>
                <span>Riwayat Pesan</span>
            </a>
            <a href="/topup" class="flex items-center gap-3 p-3 text-gray-400 hover:bg-blue-50 hover:text-blue-900 rounded-xl transition">
                <i class="fas fa-wallet"></i>
                <span>Saldo TyU-Pay</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
               class="flex items-center gap-4 text-red-500 font-bold px-4 py-3 rounded-2xl hover:bg-red-50 transition">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </a>
            <a href="javascript:void(0)" onclick="openVoucherModal()" class="bg-white text-blue-600 px-6 py-2 rounded-full font-bold text-center">
            PAKAI VOUCHER
            </a>
        </nav>

        <div class="mt-auto p-4 bg-[#122C4F] rounded-[30px] cursor-pointer hover:bg-[#1a3a5a] transition" onclick="openProfileModal()">
            <div class="flex items-center space-x-3 p-3 bg-white bg-opacity-5 rounded-2xl">
                <div class="relative flex-shrink-0">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-blue-400">
                    @else
                        <div class="w-10 h-10 bg-[#4A7292] text-white flex items-center justify-center rounded-full text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                
                <div class="flex flex-col justify-center overflow-hidden min-h-[40px]">
                    <h4 class="text-sm font-bold text-white truncate max-w-[130px] leading-tight mb-0.5">
                        {{ Auth::user()->name }}
                    </h4>
                    <div class="flex items-center text-[11px] text-gray-400 font-medium leading-none">
                        @if(Auth::user()->role === 'mahasiswa')
                            <span class="text-amber-400 mr-1">✨</span> Mahasiswa
                        @elseif(Auth::user()->role === 'admin')
                            <span class="text-purple-400 mr-1">👑</span> Admin
                        @else
                            <span class="text-gray-400 mr-1">👤</span> User Biasa
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden mt-4">
                @csrf
                <button type="submit" class="w-full text-center py-2 bg-red-400/20 hover:bg-red-400/40 text-red-100 rounded-xl text-[11px] font-bold transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> SIGN OUT
                </button>
            </form>
    </aside>

    <main class="flex-1 h-screen overflow-y-auto p-10 no-scrollbar">
        <header class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-foodtyu.png') }}" class="w-10 h-10 object-contain shadow-sm rounded-lg bg-white p-1">
                <div>
                    <h2 class="text-3xl font-black text-[#122C4F]">Hi, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-slate-500 font-medium text-sm">Laper ngerjain tugas? Pesan makan yuk!</p>
                </div>
            </div>
        </header>

        <section class="bg-gradient-to-r from-[#5B88B2] to-[#122C4F] rounded-[45px] p-12 text-white flex justify-between items-center mb-12 shadow-xl relative overflow-hidden group">
            <div class="relative z-10 max-w-md">
                @if(Auth::user()->role === 'mahasiswa')
                    {{-- Banner Khusus Mahasiswa --}}
                    <span class="bg-[#FBF9E4] text-[#122C4F] px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">🎓 Promo Student</span>
                    <h3 class="text-4xl font-black mt-5 mb-3 italic tracking-tighter text-[#FBF9E4]">Diskon Acak Hari Ini!</h3>
                    <p class="text-lg opacity-90 font-medium leading-relaxed mb-6">Khusus buat kamu mahasiswa Tel-U yang lagi ngerjain tugas. Ambil voucher terbaik dan hemat belanja di kantin.</p>
                    <button onclick="openVoucherModal()" class="bg-green-400 hover:bg-green-500 text-gray-900 px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg">
                        VOUCHER TERPASANG ✓
                    </button>
                @else
                    {{-- Banner untuk User Biasa --}}
                    <span class="bg-[#FBF9E4] text-[#122C4F] px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">👤 User Member</span>
                    <h3 class="text-4xl font-black mt-5 mb-3 italic tracking-tighter text-[#FBF9E4]">Selamat Datang!</h3>
                    <p class="text-lg opacity-90 font-medium leading-relaxed mb-6">Nikmati hidangan lezat kantin Tel-U dengan pemesanan praktis tanpa antre. Cepat, mudah, dan terjangkau.</p>
                    <button onclick="scrollToMenu()" class="bg-blue-400 hover:bg-blue-500 text-gray-900 px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg">
                        LIHAT MENU SEKARANG →
                    </button>
                @endif
            </div>
            <i class="fa-solid fa-ticket text-[250px] absolute -right-10 -bottom-10 opacity-10 rotate-12 group-hover:rotate-0 transition-all duration-700"></i>
        </section>

        <div class="mb-6">
            <div class="relative max-w-2xl mx-auto">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="searchInput" 
                       placeholder="Mau makan seblak atau apa hari ini?" 
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-blue-300 outline-none transition-all"
                       autocomplete="off">
                
                <div id="searchPopup" class="hidden absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto">
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-4 mb-8">
            <div class="flex flex-wrap gap-3">
                <button onclick="filterMenu('all')" id="btn-all" class="filter-btn px-8 py-3 bg-midnight text-white rounded-full text-xs font-bold shadow-md transition-all">All</button>
                <button onclick="filterMenu('heavy')" id="btn-heavy" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Makanan Berat</button>
                <button onclick="filterMenu('beverage')" id="btn-beverage" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Minuman</button>
                <button onclick="filterMenu('snack')" id="btn-snack" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Cemilan</button>
                <button onclick="openKantinModal()" class="px-8 py-3 bg-slate-900 text-white rounded-full text-xs font-bold shadow-md transition-all hover:bg-slate-700">Kategori Kantin</button>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 ml-auto">
                <p class="text-[10px] uppercase font-bold text-slate-400">Saldo TyU-Pay</p>
                <p class="text-lg font-black text-midnight">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
            </div>
        </div>

        <div id="menuSection" class="scroll-mt-24">
            <h2 class="text-lg font-bold mb-4" id="sectionTitle">Menu Kantin</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="foodContainer">
            
            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Mie Ayam Bakso" data-base-price="18000">
                <img src="{{ asset('images/Chicken Noodle with Meatball.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Mie Ayam Bakso</h3>
                        <p class="text-xs text-gray-500">Kenyang & Lezat</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.8</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 18.000</span>
                    <button onclick="addToCart('Mie Ayam Bakso', 18000, '{{ asset('images/Chicken Noodle with Meatball.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Seblak Jeletot" data-base-price="15000">
                <img src="{{ asset('images/seblak.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Seblak Jeletot</h3>
                        <p class="text-xs text-gray-500">Pedas Mantap</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.9</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 15.000</span>
                    <button onclick="addToCart('Seblak Jeletot', 15000, '{{ asset('images/seblak.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Chicken Katsu" data-base-price="22000">
                <img src="{{ asset('images/katsu.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Chicken Katsu</h3>
                        <p class="text-xs text-gray-500">Kenyang & Crunchy</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.7</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 22.000</span>
                    <button onclick="addToCart('Chicken Katsu', 22000, '{{ asset('images/katsu.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Sate Taichan" data-base-price="20000">
                <img src="{{ asset('images/taichan.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Sate Taichan</h3>
                        <p class="text-xs text-gray-500">Gurih & Pedas</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.8</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 20.000</span>
                    <button onclick="addToCart('Sate Taichan', 20000, '{{ asset('images/taichan.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Siomay Bandung" data-base-price="12000">
                <img src="{{ asset('images/siomay.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Siomay Bandung</h3>
                        <p class="text-xs text-gray-500">Ikan Tenggiri Asli</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.6</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 12.000</span>
                    <button onclick="addToCart('Siomay Bandung', 12000, '{{ asset('images/siomay.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[30px] shadow-sm hover:shadow-xl transition-all border border-slate-50 food-card" data-name="Nasi Goreng Spesial" data-base-price="15000">
                <div class="h-44 overflow-hidden rounded-[25px] mb-4">
                    <img src="https://images.unsplash.com/photo-1603133872878-684f208fb84b?q=80&w=1925&auto=format&fit=crop" 
                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" 
                         alt="Nasi Goreng">
                </div>
                <div class="px-2">
                    <div class="flex justify-between items-center">
                        <h4 class="menu-name font-bold text-[#122C4F] text-lg uppercase tracking-tight">Nasi Goreng Spesial</h4>
                        <span class="text-xs font-bold text-yellow-500 bg-yellow-50 px-2 py-1 rounded-full">
                            <i class="fa-solid fa-star"></i> 4.9
                        </span>
                    </div>
                    <p class="text-slate-400 text-[11px] mt-1 font-medium italic">Nasi Goreng Telur & Kerupuk</p>
                    <div class="flex justify-between items-center mt-6">
                        <span class="text-[#5B88B2] font-black text-xl product-price">RP 15.000</span>
                        <button onclick="addToCart('Nasi Goreng Spesial', 15000, 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?q=80&w=1925&auto=format&fit=crop'); toggleCart()" class="bg-[#122C4F] text-white w-12 h-12 rounded-[18px] hover:bg-[#5B88B2] transition">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div data-category="beverage" data-canton="beverage" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Ice Americano" data-base-price="12000">
                <img src="{{ asset('images/ice-americano.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Ice Americano</h3>
                        <p class="text-xs text-gray-500">Segar & Melek</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.5</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 12.000</span>
                    <button onclick="addToCart('Ice Americano', 12000, '{{ asset('images/ice-americano.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="beverage" data-canton="beverage" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Es Teh Manis" data-base-price="8000">
                <img src="{{ asset('images/esteh.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Es Teh Manis</h3>
                        <p class="text-xs text-gray-500">Manis Alami</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.5</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 8.000</span>
                    <button onclick="addToCart('Es Teh Manis', 8000, '{{ asset('images/esteh.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Ramen Shoyu" data-base-price="25000">
                <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?q=80&w=500" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Ramen Shoyu</h3>
                        <p class="text-xs text-gray-500">Kuah Kaldu Gurih</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.9</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 25.000</span>
                    <button onclick="addToCart('Ramen Shoyu', 25000, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?q=80&w=500'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Bakso Malang" data-base-price="19000">
                <img src="{{ asset('images/baksomalang.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Bakso Malang</h3>
                        <p class="text-xs text-gray-500">Kuah Hangat Gurih</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.8</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 19.000</span>
                    <button onclick="addToCart('Bakso Malang', 19000, '{{ asset('images/baksomalang.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Soto Ayam" data-base-price="17000">
                <img src="{{ asset('images/sotoayam.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Soto Ayam</h3>
                        <p class="text-xs text-gray-500">Tradisional Nusantara</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.7</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 17.000</span>
                    <button onclick="addToCart('Soto Ayam', 17000, '{{ asset('images/sotoayam.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="heavy" data-canton="heavy" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Lumpia Goreng" data-base-price="11000">
                <img src="{{ asset('images/lumpiagoreng.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Lumpia Goreng</h3>
                        <p class="text-xs text-gray-500">5 Pcs Crispy</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.6</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 11.000</span>
                    <button onclick="addToCart('Lumpia Goreng', 11000, '{{ asset('images/lumpiagoreng.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="beverage" data-canton="beverage" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Strawberry Smoothie" data-base-price="14000">
                <img src="{{ asset('images/strawberrysmoothie.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Strawberry Smoothie</h3>
                        <p class="text-xs text-gray-500">Fresh & Creamy</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.7</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 14.000</span>
                    <button onclick="addToCart('Strawberry Smoothie', 14000, '{{ asset('images/strawberrysmoothie.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="beverage" data-canton="beverage" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Caramel Frappuccino" data-base-price="20000">
                <img src="{{ asset('images/caramelfrappuccino.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Caramel Frappuccino</h3>
                        <p class="text-xs text-gray-500">Cold & Sweet</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.9</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 20.000</span>
                    <button onclick="addToCart('Caramel Frappuccino', 20000, '{{ asset('images/caramelfrappuccino.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="snack" data-canton="snack" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Roti Bakar Coklat" data-base-price="9000">
                <img src="{{ asset('images/rotibakarcoklat.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Roti Bakar Coklat</h3>
                        <p class="text-xs text-gray-500">Toasted & Melted</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.6</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 9.000</span>
                    <button onclick="addToCart('Roti Bakar Coklat', 9000, '{{ asset('images/rotibakarcoklat.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="snack" data-canton="snack" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Donat Coklat" data-base-price="10000">
                <img src="{{ asset('images/donatcoklat.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Donat Coklat</h3>
                        <p class="text-xs text-gray-500">3 Pcs Fluffy</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.8</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 10.000</span>
                    <button onclick="addToCart('Donat Coklat', 10000, '{{ asset('images/donatcoklat.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

            <div data-category="snack" data-canton="snack" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="Tahu Goreng" data-base-price="7000">
                <img src="{{ asset('images/tahugoreng.png') }}" class="w-full h-44 object-cover rounded-[24px] mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="menu-name font-bold text-gray-800 text-lg">Tahu Goreng</h3>
                        <p class="text-xs text-gray-500">6 Pcs Golden</p>
                    </div>
                    <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ 4.5</span>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <span class="font-extrabold text-[#122C4F] product-price">RP 7.000</span>
                    <button onclick="addToCart('Tahu Goreng', 7000, '{{ asset('images/tahugoreng.png') }}'); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                </div>
            </div>

        </div>

        <div id="recommendationSection" class="hidden mt-12 pt-8 border-t-2 border-dashed border-gray-200">
            <h2 class="text-lg font-bold mb-4 text-gray-400">Rekomendasi Minuman Segar 🥤</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-2xl shadow-sm opacity-80">
                    <img src="{{ asset('images/esteh.png') }}" class="rounded-xl w-full">
                    <h3 class="font-bold mt-2">Es Teh Manis</h3>
                    <p class="text-blue-500 font-bold">RP 5.000</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('.food-item');
            let container = document.getElementById('foodContainer');
            let recSection = document.getElementById('recommendationSection');
            let title = document.getElementById('sectionTitle');
            let hasMatch = false;

            items.forEach(item => {
                let text = item.getAttribute('data-name').toLowerCase();
                if (text.includes(filter)) {
                    item.style.display = ""; // Tampilkan
                    item.classList.add('animate-fade-up'); // Opsional: tambah animasi naik
                    hasMatch = true;
                } else {
                    item.style.display = "none"; // Sembunyikan
                }
            });

            // Logika ketika user mengetik (Munculkan Rekomendasi)
            if (filter.length > 0) {
                title.innerText = "Hasil Pencarian untuk: " + this.value;
                recSection.classList.remove('hidden');
                // Smooth scroll sedikit agar user sadar ada konten baru di bawah
            } else {
                title.innerText = "Menu Kantin";
                recSection.classList.add('hidden');
            }
        });
    </script>

    <aside id="cartSidebar" class="fixed right-0 top-0 h-full w-[350px] bg-white shadow-2xl z-[60] transition-transform duration-500 translate-x-full border-l border-slate-100 flex flex-col p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-[#122C4F]">Keranjang Saya</h3>
            <button onclick="toggleCart()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <span id="cartCount" class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-xs font-bold mb-6 inline-block">0 Items</span>
        
        <div id="cartItems" class="flex-1 space-y-8 overflow-y-auto no-scrollbar pr-2">
            <div class="text-center py-20 opacity-20">
                <i class="fa-solid fa-basket-shopping text-6xl mb-4"></i>
                <p class="text-sm font-bold uppercase tracking-widest">Belum ada pesanan</p>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t-2 border-dashed border-slate-100">
            <div class="space-y-4 mb-8">
                <div class="flex justify-between text-[13px] text-slate-500 font-bold uppercase tracking-wider">
                    <span>Subtotal</span>
                    <span id="subtotal" class="text-midnight">RP 0</span>
                </div>
                <div class="flex justify-between items-end pt-4 border-t border-slate-50">
                    <span class="text-lg font-black text-midnight italic">Total</span>
                    <span id="totalPrice" class="text-3xl font-black text-[#5B88B2] tracking-tighter">RP 0</span>
                </div>
            </div>
            
            <div class="mt-6">
                <p class="font-bold text-gray-800 mb-3">Metode Pembayaran</p>
                <div class="grid grid-cols-1 gap-2">
                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="qris" checked>
                            <span>QRIS</span>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-4">
                    </label>

                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="ewallet">
                            <span>E-Wallet (OVO/Dana/GoPay)</span>
                        </div>
                    </label>

                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="bank">
                            <span>Transfer Bank</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <button onclick="openPaymentModal()" class="w-full bg-[#5B88B2] text-white py-5 rounded-[25px] font-black text-sm shadow-xl shadow-blue-100 hover:bg-midnight transition-all uppercase tracking-widest active:scale-95">
                Lanjut ke Pembayaran
            </button>
        </div>
    </aside>

    <div id="paymentModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[35px] shadow-2xl p-8 animate-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-[#122C4F]">Metode Pembayaran</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- PAYMENT METHOD OPTIONS -->
            <div id="method-options" class="space-y-4">
                <!-- QRIS METHOD -->
                <div onclick="showPaymentDetail('qris')" class="p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-[#5B88B2] cursor-pointer transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-qrcode text-2xl text-[#5B88B2]"></i>
                        <div>
                            <span class="font-bold text-slate-700 block">QRIS</span>
                            <p class="text-xs text-slate-400">Gopay • Dana • OVO</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-[#5B88B2]"></i>
                </div>

                <!-- E-WALLET METHOD -->
                <div onclick="showPaymentDetail('ewallet')" class="p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-[#5B88B2] cursor-pointer transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-wallet text-2xl text-[#5B88B2]"></i>
                        <div>
                            <span class="font-bold text-slate-700 block">E-Wallet</span>
                            <p class="text-xs text-slate-400">Shopee • GoPay • Dana</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-[#5B88B2]"></i>
                </div>

                <!-- SALDO TYUPAY METHOD -->
                <div onclick="showPaymentDetail('saldo')" class="p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-green-400 cursor-pointer transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-piggy-bank text-2xl text-green-500"></i>
                        <div>
                            <span class="font-bold text-slate-700 block">Saldo TyU-Pay</span>
                            <p class="text-xs text-slate-400" id="balance-indicator">Cek saldo kamu</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-green-400"></i>
                </div>
            </div>

            <!-- PAYMENT DETAIL CONTAINER -->
            <div id="payment-detail-container" class="hidden animate-in fade-in duration-500">
                <div id="detail-content" class="bg-slate-50 p-6 rounded-[30px] text-center mb-6">
                </div>
                <button onclick="processFinalPayment()" id="confirm-payment-btn" class="w-full bg-[#122C4F] text-white py-4 rounded-2xl font-black shadow-lg hover:bg-[#5B88B2] transition">
                    KONFIRMASI PEMBAYARAN
                </button>
                <button onclick="backToMethods()" class="w-full mt-3 text-slate-400 text-xs font-bold uppercase tracking-widest">Kembali pilih metode</button>
            </div>
        </div>
    </div>

    <div id="kantinModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[40px] p-8 text-center">
            <h3 class="text-xl font-black text-[#122C4F] mb-6">Pilih Kategori Kantin</h3>
            <div class="grid grid-cols-2 gap-4">
                <button onclick="filterMenu('heavy'); closeKantinModal()" class="p-6 bg-orange-50 rounded-3xl hover:bg-orange-100 transition">
                    <i class="fa-solid fa-bowl-food text-2xl text-orange-500 mb-2"></i>
                    <p class="text-xs font-bold">Makanan Berat</p>
                </button>
                <button onclick="filterMenu('beverage'); closeKantinModal()" class="p-6 bg-blue-50 rounded-3xl hover:bg-blue-100 transition">
                    <i class="fa-solid fa-wine-glass text-2xl text-blue-500 mb-2"></i>
                    <p class="text-xs font-bold">Minuman Segar</p>
                </button>
                <button onclick="filterMenu('snack'); closeKantinModal()" class="p-6 bg-yellow-50 rounded-3xl hover:bg-yellow-100 transition">
                    <i class="fa-solid fa-cookie text-2xl text-yellow-500 mb-2"></i>
                    <p class="text-xs font-bold">Cemilan/Snack</p>
                </button>
                <button onclick="filterMenu('all'); closeKantinModal()" class="p-6 bg-slate-50 rounded-3xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-border-all text-2xl text-slate-500 mb-2"></i>
                    <p class="text-xs font-bold">Semua Menu</p>
                </button>
            </div>
        </div>
    </div>

    <!-- Avatar Upload Form (Hidden) -->
    <form id="avatarForm" action="{{ route('profile.upload.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="submitAvatarForm()">
    </form>

    <!-- Profile Modal -->
    <div id="profileModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[35px] shadow-2xl p-8 animate-in zoom-in duration-300">
            <!-- Close Button -->
            <div class="flex justify-end mb-6">
                <button onclick="closeProfileModal()" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <!-- Profile Avatar -->
            <div class="flex flex-col items-center justify-center mb-6">
                <div class="relative group cursor-pointer" onclick="triggerUpload()">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 group-hover:opacity-80 transition-all">
                    @else
                        <div class="w-24 h-24 bg-[#4A7292] text-white flex items-center justify-center rounded-full text-2xl font-bold group-hover:bg-opacity-80 transition-all">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <div class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md text-gray-600 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <span class="text-xs text-gray-400 mt-2">Klik foto untuk mengubah</span>
            </div>

            <!-- Profile Info -->
            <div class="space-y-4 mb-8">
                <!-- Nama -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Nama Lengkap</p>
                    <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->name }}</p>
                </div>

                <!-- Username -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Username</p>
                    <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->username }}</p>
                </div>

                <!-- Email -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Email</p>
                    <p class="text-xs font-medium text-[#122C4F] break-all">{{ Auth::user()->email }}</p>
                </div>

                <!-- Phone -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">No. Telepon</p>
                    <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->phone }}</p>
                </div>

                <!-- NIM -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">NIM</p>
                    <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->nim ?? 'Tidak ada' }}</p>
                </div>

                <!-- Status -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-2">Status</p>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-[#5B88B2] font-bold rounded-full text-xs capitalize">
                        {{ Auth::user()->role ?? 'User' }}
                    </span>
                </div>

                <!-- Alamat -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-1">Alamat</p>
                    <p class="text-xs text-[#122C4F] leading-relaxed">{{ Auth::user()->address ?? 'Alamat tidak tersedia' }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="handleEditProfile()" class="w-full bg-[#5B88B2] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#122C4F] transition">
                    <i class="fa-solid fa-pen mr-2"></i> Edit Profil
                </button>
                <button onclick="closeProfileModal()" class="w-full bg-slate-100 text-slate-600 py-3 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[35px] shadow-2xl p-8 text-center animate-in zoom-in duration-300">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-[#122C4F] mb-2">Pesanan Berhasil!</h3>
            <p class="text-slate-500 mb-8">Pesananmu sedang diproses oleh kantin. Silakan cek menu Riwayat.</p>
            
            <button onclick="location.reload()" class="w-full bg-[#122C4F] text-white py-4 rounded-2xl font-black">
                KEMBALI KE HOME
            </button>
        </div>
    </div>

    <div id="voucherModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[35px] shadow-2xl p-8 animate-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-[#122C4F]">Pilih Voucher Hari Ini</h3>
                <button onclick="closeVoucherModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div id="modalVoucherList" class="space-y-3 max-h-96 overflow-y-auto">
            </div>
            
            <button onclick="closeVoucherModal()" class="w-full mt-6 text-slate-400 text-xs font-bold uppercase tracking-widest py-3">
                Tutup
            </button>
        </div>
    </div>

    <script>
        let cart = [];

        // Profile Modal Functions
        function openProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Fungsi untuk memicu klik pada input file tersembunyi
        function triggerUpload() {
            document.getElementById('avatarInput').click();
        }

        // Fungsi otomatis submit form begitu file selesai dipilih
        function submitAvatarForm() {
            const input = document.getElementById('avatarInput');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validasi file di client-side dulu
                if (!file.type.startsWith('image/')) {
                    alert('❌ File harus berupa gambar! (jpg, png, gif)');
                    input.value = ''; // Reset input
                    return;
                }
                
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert(`❌ Ukuran file terlalu besar! (Max: 2MB, Anda: ${(file.size / 1024 / 1024).toFixed(2)}MB)`);
                    input.value = ''; // Reset input
                    return;
                }
                
                console.log('Uploading file:', file.name, 'Size:', file.size, 'Type:', file.type);
                document.getElementById('avatarForm').submit();
            }
        }

        function handleEditProfile() {
            alert('Edit profil sedang dikembangkan! ✏️');
            // TODO: Implementasi edit profil
        }

        function scrollToMenu() {
            const menuSection = document.getElementById('menuSection');
            if (menuSection) {
                menuSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Close modal ketika klik di luar modal
        document.getElementById('profileModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfileModal();
            }
        });

        function addToCart(name, price, img) {
            const existingItem = cart.find(item => item.name === name);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ name, price, img, qty: 1 });
            }
            updateCartUI();
        }

        function updateCartUI() {
            const cartItems = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('totalPrice');

            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="text-center py-20 opacity-20">
                        <i class="fa-solid fa-basket-shopping text-6xl mb-4"></i>
                        <p class="text-sm font-bold uppercase tracking-widest">Belum ada pesanan</p>
                    </div>`;
            } else {
                cartItems.innerHTML = '';
            }

            let subtotal = 0;
            cart.forEach((item, index) => {
                subtotal += item.price * item.qty;
                cartItems.innerHTML += `
                    <div class="flex items-center gap-5 group cart-item-anim">
                        <div class="w-20 h-20 bg-slate-100 rounded-[25px] overflow-hidden shrink-0 shadow-sm border border-slate-50">
                            <img src="${item.img}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-[13px] font-bold text-midnight mb-1 leading-tight uppercase">${item.name}</p>
                            <p class="text-xs text-[#5B88B2] font-black italic">RP ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="flex flex-col items-center gap-2 bg-slate-50 rounded-2xl p-2">
                            <button onclick="changeQty(${index}, 1)" class="text-[#5B88B2] hover:scale-125 transition"><i class="fa-solid fa-plus text-[10px]"></i></button>
                            <span class="text-xs font-black text-midnight">${item.qty}</span>
                            <button onclick="changeQty(${index}, -1)" class="text-slate-300 hover:text-red-500 transition"><i class="fa-solid fa-minus text-[10px]"></i></button>
                        </div>
                    </div>`;
            });

            const discountPercent = getDiscountPercent();
            const discountValue = Math.round(subtotal * discountPercent / 100);
            const total = subtotal - discountValue;

            cartCount.innerText = `${cart.length} Items`;
            subtotalEl.innerText = `RP ${subtotal.toLocaleString('id-ID')}`;
            if (discountPercent > 0) {
                totalEl.innerHTML = `RP ${total.toLocaleString('id-ID')} <span class="block text-xs text-green-600 font-bold">(Disc ${discountPercent}% - RP ${discountValue.toLocaleString('id-ID')})</span>`;
            } else {
                totalEl.innerText = `RP ${total.toLocaleString('id-ID')}`;
            }
        }

        function changeQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) cart.splice(index, 1);
            updateCartUI();
        }

        function filterMenu(category) {
            const items = document.querySelectorAll('#foodContainer .menu-item');
            const buttons = document.querySelectorAll('.filter-btn');

            buttons.forEach(btn => {
                btn.classList.remove('bg-midnight', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-400');
            });

            const activeBtn = document.getElementById(`btn-${category}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-midnight', 'text-white', 'shadow-md');
                activeBtn.classList.remove('bg-white', 'text-slate-400');
            }

            items.forEach(item => {
                const itemCategory = item.dataset.canton || item.dataset.category;
                item.style.display = (category === 'all' || itemCategory === category) ? 'block' : 'none';
            });
        }

        function toggleCart() {
            const cart = document.getElementById('cartSidebar');
            cart.classList.toggle('translate-x-full');
        }

        function isVoucherActive() {
            return sessionStorage.getItem('voucher_active') === 'true';
        }

        function getDiscountPercent() {
            return isVoucherActive() ? parseInt(sessionStorage.getItem('discount_amount') || '0', 10) : 0;
        }

        function updateProductPrices() {
            document.querySelectorAll('#foodContainer .menu-item').forEach(item => {
                const priceEl = item.querySelector('.product-price');
                const basePrice = Number(item.dataset.basePrice);
                if (!priceEl || !basePrice) return;

                if (isVoucherActive()) {
                    const discountValue = Math.round(basePrice * getDiscountPercent() / 100);
                    const discountedPrice = basePrice - discountValue;
                    priceEl.innerHTML = `<span class="line-through text-slate-400">RP ${basePrice.toLocaleString('id-ID')}</span> <span class="text-[#122C4F] font-black">RP ${discountedPrice.toLocaleString('id-ID')}</span>`;
                } else {
                    priceEl.innerHTML = `RP ${basePrice.toLocaleString('id-ID')}`;
                }
            });
        }

        // Daftar voucher yang tersedia
        const allVouchers = [
            { id: 1, name: 'Diskon 10% Minuman', discount: 10, category: 'beverage' },
            { id: 2, name: 'Diskon 15% Makanan Berat', discount: 15, category: 'heavy' },
            { id: 3, name: 'Diskon 20% Cemilan', discount: 20, category: 'snack' },
            { id: 4, name: 'Diskon 25% Semua Menu', discount: 25, category: 'all' },
            { id: 5, name: 'Diskon 12% Pembeli Setia', discount: 12, category: 'all' },
            { id: 6, name: 'Diskon 18% Akhir Bulan', discount: 18, category: 'all' },
            { id: 7, name: 'Diskon 15% Minuman Segar', discount: 15, category: 'beverage' }
        ];

        // Fungsi untuk mendapatkan voucher acak berdasarkan hari
        function getDailyVouchers() {
            const today = new Date().toDateString();
            const lastDate = localStorage.getItem('voucher_last_date');
            const lastVouchers = localStorage.getItem('daily_vouchers');
            
            if (lastDate === today && lastVouchers) {
                return JSON.parse(lastVouchers);
            }
            
            // Shuffle vouchers dan ambil 3 secara acak
            const shuffled = [...allVouchers].sort(() => Math.random() - 0.5);
            const dailyVouchers = shuffled.slice(0, 3);
            
            localStorage.setItem('voucher_last_date', today);
            localStorage.setItem('daily_vouchers', JSON.stringify(dailyVouchers));
            
            return dailyVouchers;
        }

        // Tampilkan daftar voucher di hero section
        function displayVoucherList() {
            const container = document.getElementById('voucherListSection');
            if (!container) return;
            
            const dailyVouchers = getDailyVouchers();
            const activeVoucher = sessionStorage.getItem('active_voucher_id');
            
            container.innerHTML = dailyVouchers.map(voucher => `
                <button onclick="selectVoucher(${voucher.id}, ${voucher.discount}, '${voucher.name}')" 
                        class="w-full px-4 py-3 rounded-lg font-bold transition-all text-left ${
                            activeVoucher == voucher.id 
                            ? 'bg-green-400 text-white shadow-lg' 
                            : 'bg-white/20 text-white hover:bg-white/30'
                        }">
                    ${voucher.name} (${voucher.discount}%)
                </button>
            `).join('');
        }

        // Fungsi untuk membuka modal voucher
        function openVoucherModal() {
            const modal = document.getElementById('voucherModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                displayDailyVouchersInModal();
            }
        }

        function closeVoucherModal() {
            const modal = document.getElementById('voucherModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function displayDailyVouchersInModal() {
            const container = document.getElementById('modalVoucherList');
            if (!container) return;
            
            const dailyVouchers = getDailyVouchers();
            const activeVoucher = sessionStorage.getItem('active_voucher_id');
            
            container.innerHTML = dailyVouchers.map(voucher => `
                <div class="p-4 border-2 rounded-2xl cursor-pointer transition-all ${
                    activeVoucher == voucher.id 
                    ? 'border-green-400 bg-green-50' 
                    : 'border-slate-100 hover:border-blue-400'
                }" onclick="selectVoucher(${voucher.id}, ${voucher.discount}, '${voucher.name}')">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-[#122C4F]">${voucher.name}</h4>
                            <p class="text-sm text-slate-500">Diskon ${voucher.discount}%</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-blue-600">${voucher.discount}%</span>
                            ${activeVoucher == voucher.id ? '<span class="text-green-600 font-bold">✓ Aktif</span>' : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function selectVoucher(voucherId, discount, voucherName) {
            sessionStorage.setItem('voucher_active', 'true');
            sessionStorage.setItem('active_voucher_id', voucherId);
            sessionStorage.setItem('discount_amount', discount);
            sessionStorage.setItem('voucher_name', voucherName);
            
            displayVoucherList();
            displayDailyVouchersInModal();
            updateProductPrices();
            updateCartUI();
            
            alert(`Voucher "${voucherName}" berhasil diaktifkan! Diskon ${discount}% untuk semua menu.`);
        }

        function refreshVoucherUI() {
            displayVoucherList();
            if (isVoucherActive()) {
                updateProductPrices();
            }
        }

        function openPaymentModal() {
            toggleCart();
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        let selectedPaymentType = 'qris';

        function showPaymentDetail(type) {
            document.getElementById('method-options').classList.add('hidden');
            const container = document.getElementById('payment-detail-container');
            const content = document.getElementById('detail-content');
            container.classList.remove('hidden');
            selectedPaymentType = type;

            if (type === 'qris') {
                // QRIS Payment
                content.innerHTML = `
                    <p class="font-bold text-[#122C4F] mb-4 uppercase text-[10px] tracking-widest">Scan QRIS Berikut</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=HulahupPay" class="mx-auto rounded-xl shadow-md mb-3 border-4 border-white">
                    <p class="text-[10px] text-slate-400 font-medium">Menerima Dana, OVO, GoPay, dan ShopeePay</p>
                `;
            } else if (type === 'ewallet') {
                // E-Wallet Payment - Sub options
                content.innerHTML = `
                    <p class="font-bold text-[#122C4F] mb-4 uppercase text-[10px] tracking-widest">Pilih E-Wallet Kamu</p>
                    <div class="space-y-3">
                        <div onclick="selectEWallet('shopee')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#FF7520] cursor-pointer transition group">
                            <i class="fa-solid fa-cart-shopping text-[#FF7520] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">Shopee Pay</p>
                                <p class="text-xs text-slate-400">Pembayaran instan</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#FF7520]"></i>
                        </div>
                        <div onclick="selectEWallet('gopay')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#00B4D8] cursor-pointer transition group">
                            <i class="fa-solid fa-mobile text-[#00B4D8] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">GoPay</p>
                                <p class="text-xs text-slate-400">Dari aplikasi GO-JEK</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#00B4D8]"></i>
                        </div>
                        <div onclick="selectEWallet('dana')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#0055FF] cursor-pointer transition group">
                            <i class="fa-solid fa-wallet text-[#0055FF] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">DANA</p>
                                <p class="text-xs text-slate-400">Dompet digital Indonesia</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#0055FF]"></i>
                        </div>
                    </div>
                `;
            } else if (type === 'saldo') {
                // Saldo TyU-Pay Payment - Show balance
                const userBalance = {{ Auth::user()->balance ?? 0 }};
                const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
                const discountPercent = getDiscountPercent();
                const discountValue = Math.round(subtotal * discountPercent / 100);
                const totalPaid = subtotal - discountValue;
                const isEnoughBalance = userBalance >= totalPaid;
                
                content.innerHTML = `
                    <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-2xl p-6 text-white mb-4">
                        <p class="text-xs font-medium opacity-80 mb-1">Saldo TyU-Pay Kamu</p>
                        <h4 class="text-3xl font-black">RP ${userBalance.toLocaleString('id-ID')}</h4>
                    </div>
                    <div class="space-y-3 text-left">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Total Pesanan:</span>
                            <span class="font-bold text-slate-800">RP ${totalPaid.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                            <span class="text-sm font-bold ${isEnoughBalance ? 'text-green-600' : 'text-red-600'}">
                                Sisa Saldo:
                            </span>
                            <span class="text-xl font-black ${isEnoughBalance ? 'text-green-600' : 'text-red-600'}">
                                RP ${(userBalance - totalPaid).toLocaleString('id-ID')}
                            </span>
                        </div>
                        ${!isEnoughBalance ? `
                            <div class="bg-red-50 border border-red-300 rounded-xl p-3 mt-3">
                                <p class="text-xs text-red-700 font-bold">
                                    ⚠️ Saldo tidak cukup! Kurang RP ${(totalPaid - userBalance).toLocaleString('id-ID')}
                                </p>
                            </div>
                        ` : ''}
                    </div>
                `;
            }
        }

        function selectEWallet(wallet) {
            selectedPaymentType = 'ewallet_' + wallet;
            const containers = document.querySelectorAll('#detail-content > div:last-child > div');
            containers.forEach(el => {
                el.classList.remove('border-[#FF7520]', 'border-[#00B4D8]', 'border-[#0055FF]');
                el.classList.add('border-slate-100');
            });
            
            event.target.closest('.p-4')?.classList.add(
                wallet === 'shopee' ? 'border-[#FF7520]' : 
                wallet === 'gopay' ? 'border-[#00B4D8]' : 
                'border-[#0055FF]'
            );
        }

        function backToMethods() {
            document.getElementById('method-options').classList.remove('hidden');
            document.getElementById('payment-detail-container').classList.add('hidden');
        }

        function processFinalPayment() {
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            const discountPercent = getDiscountPercent();
            const discountValue = Math.round(subtotal * discountPercent / 100);
            const totalPaid = subtotal - discountValue;
            
            // Prepare order data for database
            const orderItemsForDb = cart.map(item => ({
                name: item.name,
                price: item.price,
                qty: item.qty,
                subtotal: item.price * item.qty
            }));

            // Save to localStorage for history display
            const orderData = {
                order_id: "ORD-" + Math.floor(1000 + Math.random() * 9000),
                date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }),
                total: `RP ${totalPaid.toLocaleString('id-ID')}`,
                status: "Selesai"
            };

            let orders = JSON.parse(localStorage.getItem('hulahup_orders')) || [];
            orders.unshift(orderData);
            localStorage.setItem('hulahup_orders', JSON.stringify(orders));

            // Get voucher info if applied
            const voucherName = sessionStorage.getItem('voucher_name') || 'Tanpa Voucher';

            // Determine payment method string for notes
            let paymentMethodNote = selectedPaymentType;
            if (selectedPaymentType.includes('ewallet_shopee')) paymentMethodNote = 'E-Wallet (Shopee Pay)';
            else if (selectedPaymentType.includes('ewallet_gopay')) paymentMethodNote = 'E-Wallet (GoPay)';
            else if (selectedPaymentType.includes('ewallet_dana')) paymentMethodNote = 'E-Wallet (DANA)';
            else if (selectedPaymentType === 'qris') paymentMethodNote = 'QRIS';
            else if (selectedPaymentType === 'saldo') paymentMethodNote = 'Saldo TyU-Pay';

            // Save to database via API
            fetch('/api/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    items: orderItemsForDb,
                    total_amount: totalPaid,
                    payment_method: paymentMethodNote,
                    notes: `Diskon: ${discountPercent}% (${voucherName})`
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                if (data.success) {
                    // Success - refresh page to show updated balance
                    closeModal();
                    const successModal = document.getElementById('successModal');
                    successModal.classList.remove('hidden');
                    successModal.classList.add('flex');
                    
                    // Reload page after 2 seconds to refresh balance
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    // Error - show insufficient balance message (only for Saldo method)
                    closeModal();
                    const errorMessage = data.message || 'Terjadi kesalahan';
                    
                    // Show error modal
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
                    errorDiv.innerHTML = `
                        <div class="bg-white rounded-3xl p-8 max-w-md w-full text-center shadow-2xl">
                            <i class="fa-solid fa-exclamation-circle text-red-500 text-6xl mb-4"></i>
                            <h3 class="text-2xl font-black text-gray-800 mb-3">⚠️ Saldo Tidak Cukup!</h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">${errorMessage}</p>
                            <div class="space-y-3">
                                <a href="/topup" class="block bg-blue-500 text-white py-3 rounded-xl font-bold hover:bg-blue-600 transition">
                                    💳 Isi Saldo TyU-Pay
                                </a>
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="w-full bg-gray-200 text-gray-800 py-3 rounded-xl font-bold hover:bg-gray-300 transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(errorDiv);
                }
            })
            .catch(error => {
                console.error('Error saving order:', error);
                closeModal();
                alert('Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
            });
        }

        function processPayment() {
            processFinalPayment();
        }

        function openKantinModal() {
            document.getElementById('kantinModal').classList.remove('hidden');
            document.getElementById('kantinModal').classList.add('flex');
        }

        function closeKantinModal() {
            document.getElementById('kantinModal').classList.add('hidden');
            document.getElementById('kantinModal').classList.remove('flex');
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const searchPopup = document.getElementById('searchPopup');
            searchPopup.innerHTML = '';
            
            if (query.length > 0) {
                searchPopup.classList.remove('hidden');
                let found = false;
                
                document.querySelectorAll('.menu-item').forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    if (name.includes(query)) {
                        found = true;
                        const row = document.createElement('div');
                        row.className = "p-4 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-none flex items-center";
                        row.innerHTML = `<span class="font-medium text-gray-700">${item.getAttribute('data-name')}</span>`;
                        
                        row.onclick = () => {
                            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            item.classList.add('ring-4', 'ring-orange-400');
                            searchPopup.classList.add('hidden');
                            setTimeout(() => item.classList.remove('ring-4', 'ring-orange-400'), 2000);
                        };
                        searchPopup.appendChild(row);
                    }
                });
                
                if (!found) {
                    searchPopup.innerHTML = '<div class="p-4 text-gray-400">Makanan tidak ditemukan...</div>';
                }
            } else {
                searchPopup.classList.add('hidden');
            }
        });
        
        document.addEventListener('click', (e) => {
            const searchInput = document.getElementById('searchInput');
            const searchPopup = document.getElementById('searchPopup');
            if (!searchInput.contains(e.target) && !searchPopup.contains(e.target)) {
                searchPopup.classList.add('hidden');
            }
        });

        refreshVoucherUI();
    </script>

    <button onclick="toggleCart()" class="fixed bottom-8 right-8 z-50 bg-[#122C4F] text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all">
        <i class="fa-solid fa-cart-shopping text-xl"></i>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-6 h-6 rounded-full flex items-center justify-center border-4 border-[#FBF9E4]">1</span>
    </button>

    <script>
    const searchInput = document.getElementById('searchInput');
    const foodCards = document.querySelectorAll('.food-card');
    const foodContainer = document.getElementById('foodContainer');
    const recommendationSection = document.getElementById('recommendationSection');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasResults = false;

        foodCards.forEach(card => {
            const foodName = card.getAttribute('data-name').toLowerCase();
            
            if (foodName.includes(searchTerm)) {
                card.style.display = 'block'; // Tampilkan jika cocok
                hasResults = true;
            } else {
                card.style.display = 'none'; // Sembunyikan jika tidak cocok
            }
        });

        // Logika untuk menampilkan rekomendasi jika user sedang mengetik
        if (searchTerm.length > 0) {
            recommendationSection.classList.remove('hidden');
            // Menambahkan animasi halus agar konten terasa "naik ke atas"
            foodContainer.style.transition = "all 0.3s ease";
        } else {
            recommendationSection.classList.add('hidden');
        }
    });
</script>
</body>
</html>