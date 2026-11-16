<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Parkir') - Aplikasi Parkir</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navbar -->
    <header class="bg-indigo-700 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <span class="bg-white text-indigo-700 font-black text-xl rounded-lg px-2.5 py-1 shadow">🅿️</span>
                <div>
                    <a href="{{ route('dashboard') }}" class="font-bold text-lg tracking-wide hover:text-indigo-100 transition">PARKIR SYSTEM</a>
                    <span class="text-xs text-indigo-200 block -mt-1 font-medium">Laravel Murni Edition</span>
                </div>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold leading-tight">{{ Auth::user()->nama_lengkap }}</p>
                    <span class="inline-block text-xs uppercase tracking-wider px-2 py-0.5 rounded-full font-bold
                        {{ Auth::user()->role === 'admin' ? 'bg-amber-400 text-amber-950' : (Auth::user()->role === 'petugas' ? 'bg-emerald-400 text-emerald-950' : 'bg-blue-300 text-blue-950') }}">
                        {{ Auth::user()->role }}
                    </span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-indigo-800 hover:bg-indigo-900 text-white text-xs font-semibold py-2 px-3 rounded-lg border border-indigo-600 transition flex items-center space-x-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sub-Navbar / Navigation -->
    <nav class="bg-white border-b border-gray-200 shadow-sm sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-1 sm:space-x-3 overflow-x-auto py-2 text-sm font-medium">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Kelola User</span>
                    </a>
                    <a href="{{ route('admin.areas.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.areas.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Area Parkir</span>
                    </a>
                    <a href="{{ route('admin.tarifs.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.tarifs.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Master Tarif</span>
                    </a>
                    <a href="{{ route('admin.kendaraans.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.kendaraans.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <span>Master Kendaraan</span>
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('admin.logs.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Log Aktivitas</span>
                    </a>
                @elseif(Auth::user()->role === 'petugas')
                    <a href="{{ route('petugas.dashboard') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('petugas.dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('petugas.transaksi.masuk') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('petugas.transaksi.masuk') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-600 hover:text-emerald-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span>Kendaraan Masuk</span>
                    </a>
                    <a href="{{ route('petugas.transaksi.index', ['status' => 'masuk']) }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('petugas.transaksi.index') && request('status', 'masuk') === 'masuk' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Kendaraan Parkir (Aktif)</span>
                    </a>
                    <a href="{{ route('petugas.transaksi.index', ['status' => 'keluar']) }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('petugas.transaksi.index') && request('status') === 'keluar' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Riwayat / History Transaksi</span>
                    </a>
                @elseif(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('owner.laporan.index') }}" class="px-3 py-2 rounded-md inline-flex items-center space-x-1.5 {{ request()->routeIs('owner.laporan.*') ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Rekap Laporan Transaksi</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    @if(session('cetak_masuk_id'))
                        <a href="{{ route('petugas.transaksi.struk-masuk', session('cetak_masuk_id')) }}" target="_blank"
                           class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow inline-flex items-center space-x-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak Tiket Masuk</span>
                        </a>
                    @endif
                    @if(session('cetak_keluar_id'))
                        <a href="{{ route('petugas.transaksi.struk-keluar', session('cetak_keluar_id')) }}" target="_blank"
                           class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow inline-flex items-center space-x-1">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>Cetak Struk Keluar</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-medium text-rose-800">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
                <p class="text-sm font-bold text-rose-800 mb-1">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside text-xs text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">@yield('page_subtitle', '')</p>
            </div>
            <div>
                @yield('page_action')
            </div>
        </div>

        <!-- Main Body -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-4 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} Sistem Parkir - Laravel Murni (Branch: <code class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded">laravel-murni</code>)
    </footer>

    @stack('scripts')
</body>
</html>
