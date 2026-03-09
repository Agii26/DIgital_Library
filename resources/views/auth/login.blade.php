<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">

    <!-- Left Panel -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white flex-col justify-between p-12 relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur">
                    📚
                </div>
                <h1 class="text-xl font-bold">Digital Library</h1>
            </div>
        </div>

        <div class="relative z-10">
            <h2 class="text-4xl font-bold leading-tight mb-4">
                Your gateway to<br/>knowledge & learning.
            </h2>
            <p class="text-blue-200 text-sm leading-relaxed">
                Access thousands of books, manage borrowings, and explore digital resources — all in one place.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <p class="text-2xl font-bold">📖</p>
                    <p class="text-xs text-blue-200 mt-1">Physical Books</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <p class="text-2xl font-bold">💻</p>
                    <p class="text-xs text-blue-200 mt-1">Digital Books</p>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4 text-center">
                    <p class="text-2xl font-bold">📡</p>
                    <p class="text-xs text-blue-200 mt-1">RFID Attendance</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-xs text-blue-300">
            © {{ now()->year }} Digital Library System
        </div>
    </div>

    <!-- Right Panel -->
    <div class="flex-1 flex items-center justify-center bg-slate-50 p-6">
        <div class="w-full max-w-md">

            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 shadow-lg">
                    📚
                </div>
                <h1 class="text-2xl font-bold text-blue-800">Digital Library</h1>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome back!</h2>
                    <p class="text-gray-500 text-sm mt-1">Sign in to your account to continue.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <span>⚠️</span> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-300 transition"
                            placeholder="you@example.com"
                            required autofocus />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-300 transition pr-11"
                                placeholder="••••••••"
                                required />
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg">
                                👁️
                            </button>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 active:scale-95 transition-all duration-150 shadow-md shadow-blue-200 mt-2">
                        Sign In →
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Having trouble? Contact your library administrator.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>