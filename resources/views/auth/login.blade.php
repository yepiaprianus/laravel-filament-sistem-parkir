<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Parkir (Laravel Murni)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100">
        <!-- Header -->
        <div class="bg-indigo-700 px-8 py-8 text-center text-white relative">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md mb-3 shadow-inner">
                <span class="text-3xl">🅿️</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight">SISTEM PARKIR</h1>
            <p class="text-indigo-200 text-xs mt-1">Laravel Murni MVC Edition</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border-l-4 border-emerald-500 p-3 rounded text-xs text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-rose-50 border-l-4 border-rose-500 p-3 rounded text-xs text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition outline-none"
                           placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition outline-none"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-sm flex justify-center items-center">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-gray-200">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center mb-2">Akun Default (Demo):</p>
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-gray-50 p-2 rounded-lg border border-gray-200">
                        <span class="font-bold text-indigo-700 block">Admin</span>
                        <code class="text-[10px] text-gray-600">admin / admin</code>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg border border-gray-200">
                        <span class="font-bold text-emerald-700 block">Petugas</span>
                        <code class="text-[10px] text-gray-600">petugas / petugas</code>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg border border-gray-200">
                        <span class="font-bold text-blue-700 block">Owner</span>
                        <code class="text-[10px] text-gray-600">owner / owner</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
