<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - {{ config('app.name') }}</title>

    {{-- Styles global (Tailwind / Theme) --}}
    @include('layouts.partials.styles')
</head>

<body class="bg-base text-textmain antialiased">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-surface rounded-2xl shadow-sm border border-black/5 p-8">
                
                {{-- Logo --}}
                <div class="text-center mb-6">
                    {{-- Ganti logo sesuai aset kamu --}}
                    <img src="{{ asset('assets/images/LOGOS.svg') }}" 
                         alt="Logo"
                         class="h-14 mx-auto mb-3">
                    <h1 class="text-xl font-semibold">Login Admin</h1>
                    <p class="text-sm text-textmuted mt-1">
                        Silakan masuk untuk melanjutkan
                    </p>
                </div>

                {{-- Alert Session --}}
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Email
                        </label>
                        <input type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full rounded-lg border border-black/10 bg-base px-4 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-accent
                                   @error('email') border-red-500 @enderror">

                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Password
                        </label>
                        <input type="password"
                            name="password"
                            required
                            class="w-full rounded-lg border border-black/10 bg-base px-4 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-accent
                                   @error('password') border-red-500 @enderror">

                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-textmuted">
                            <input type="checkbox"
                                   name="remember"
                                   class="rounded border-black/20 text-accent focus:ring-accent">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full rounded-lg bg-accent py-2.5 text-white font-medium
                               hover:bg-accentHover transition">
                        Masuk
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-textmuted mt-6">
                © {{ date('Y') }} {{ config('app.name') }}
            </p>
        </div>
    </div>

    {{-- Scripts global --}}
    @include('layouts.partials.scripts')

    {{-- SweetAlert --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            })
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json($errors->first())
            })
        </script>
    @endif

</body>
</html>
