<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password - Digital Library</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex">

    <!-- Left Panel -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 text-white flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur">
                    📚
                </div>
                <h1 class="text-xl font-bold">Digital Library</h1>
            </div>
        </div>

        <div class="relative z-10">
            <h2 class="text-4xl font-bold leading-tight mb-4">
                Almost there!<br/>Set up your account.
            </h2>
            <p class="text-blue-200 text-sm leading-relaxed">
                You've been invited to the Digital Library System. Create a secure password to activate your account.
            </p>

            <div class="mt-10 space-y-3">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl px-4 py-3">
                    <span class="text-green-400">✓</span>
                    <p class="text-sm text-blue-100">Account created by administrator</p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl px-4 py-3">
                    <span class="text-yellow-400">→</span>
                    <p class="text-sm text-blue-100">Set your password</p>
                </div>
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-xl px-4 py-3 opacity-50">
                    <span>🔒</span>
                    <p class="text-sm text-blue-100">Access the library system</p>
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
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mb-4">
                        🔐
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Set your password</h2>
                    <p class="text-gray-500 text-sm mt-1">Create a secure password for your account.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                        <span>⚠️</span> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('set-password.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="token" value="{{ request()->token }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" value="{{ $user->email }}"
                            class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed"
                            disabled />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition pr-11"
                                placeholder="Minimum 8 characters" required />
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                👁️
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition pr-11"
                                placeholder="Repeat your password" required />
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                👁️
                            </button>
                        </div>
                    </div>

                    <!-- Password strength indicator -->
                    <div id="strength-bar" class="hidden">
                        <div class="flex gap-1 mt-1">
                            <div class="h-1 flex-1 rounded-full bg-gray-200" id="bar1"></div>
                            <div class="h-1 flex-1 rounded-full bg-gray-200" id="bar2"></div>
                            <div class="h-1 flex-1 rounded-full bg-gray-200" id="bar3"></div>
                            <div class="h-1 flex-1 rounded-full bg-gray-200" id="bar4"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-gray-400 mt-1"></p>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 active:scale-95 transition-all duration-150 shadow-md shadow-blue-200">
                        Activate Account →
                    </button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Link expired? Contact your library administrator.
            </p>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Password strength
        document.getElementById('password').addEventListener('input', function() {
            const val = this.value;
            const bar = document.getElementById('strength-bar');
            bar.classList.remove('hidden');

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];

            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById('bar' + i);
                bar.className = 'h-1 flex-1 rounded-full ' + (i <= score ? colors[score - 1] : 'bg-gray-200');
            }

            document.getElementById('strength-text').textContent = val.length > 0 ? labels[score - 1] || '' : '';
        });
    </script>
</body>
</html>