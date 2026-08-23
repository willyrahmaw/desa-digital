@php
    try {
        $loginSettings = \App\Models\Pengaturan::pluck('value', 'key')->toArray();
        $namaDesa = $loginSettings['nama_desa'] ?? 'Desa Candraloka';
    } catch(\Exception $e) {
        $loginSettings = [];
        $namaDesa = 'Desa Candraloka';
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Desa {{ $namaDesa }}</title>

    @if(!empty($loginSettings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $loginSettings['favicon']) }}">
    @elseif(!empty($loginSettings['logo_desa']))
        <link rel="icon" href="{{ asset('storage/' . $loginSettings['logo_desa']) }}">
    @endif

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
    </style>
</head>
<body class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            @if(!empty($loginSettings['logo_desa']))
                <img src="{{ asset('storage/' . $loginSettings['logo_desa']) }}" alt="Logo {{ $namaDesa }}" class="h-16 w-16 object-contain drop-shadow-sm">
            @else
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold text-xl tracking-wider shadow-md">
                    ED
                </div>
            @endif
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-slate-900">E-Desa {{ $namaDesa }}</h2>
        <p class="mt-1 text-center text-xs text-slate-600">
            Sistem Informasi Pelayanan & Tata Kelola Administrasi Desa
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white px-8 py-8 border border-slate-200 rounded-lg sm:px-10">
            <!-- Flash Alert -->
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded border border-slate-300 px-3 py-2 text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:outline-none sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <label for="remember" class="ml-2 block text-sm text-slate-700 select-none">Ingat saya</label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-none hover:bg-indigo-700 focus:outline-none transition-colors duration-150">
                        Masuk Ke Admin Panel
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
