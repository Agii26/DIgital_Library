<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Digital Library') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay"
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-20 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside class="w-64 min-h-screen flex flex-col fixed top-0 left-0 z-30 overflow-y-auto
        bg-gradient-to-b from-blue-900 via-blue-800 to-blue-900 text-white shadow-2xl
        transition-transform duration-300 ease-in-out
        -translate-x-full lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        <!-- Logo -->
        <div class="px-6 py-5 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-500 rounded-xl flex items-center justify-center text-lg shadow-inner">
                    📚
                </div>
                <div>
                    <h1 class="text-base font-bold leading-tight">Digital Library</h1>
                    <p class="text-xs text-blue-300">{{ ucfirst(Auth::user()->role) }} Panel</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-5 space-y-0.5">

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">🏠</span> Dashboard
                </a>
                <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📖</span> Books
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">👥</span> Users
                </a>
                <a href="{{ route('admin.borrows.index') }}" class="nav-link {{ request()->routeIs('admin.borrows.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📋</span> Borrowing
                </a>
                <a href="{{ route('admin.penalties.index') }}" class="nav-link {{ request()->routeIs('admin.penalties.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💰</span> Penalties
                </a>
                <a href="{{ route('admin.attendance.index') }}" class="nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📡</span> RFID Attendance
                </a>
                <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💬</span> Messages
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📊</span> Reports
                </a>
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">⚙️</span> Settings
                </a>
                <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">👤</span> My Profile
                </a>

            @elseif(Auth::user()->role === 'faculty')
                <a href="{{ route('faculty.dashboard') }}" class="nav-link {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">🏠</span> Dashboard
                </a>
                <div x-data="{ open: {{ request()->routeIs('digital.*') || request()->routeIs('faculty.books.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="nav-link w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                        <span class="flex items-center gap-3"><span class="text-base">📖</span> Browse Books</span>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="ml-4 mt-0.5 space-y-0.5">
                        <a href="{{ route('digital.index') }}" class="nav-link {{ request()->routeIs('digital.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-xl text-sm hover:bg-white/10">
                            <span>💻</span> Digital Books
                        </a>
                        <a href="{{ route('faculty.books.index') }}" class="nav-link {{ request()->routeIs('faculty.books.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-xl text-sm hover:bg-white/10">
                            <span>📚</span> Physical Books
                        </a>
                    </div>
                </div>
                <a href="{{ route('faculty.borrows.index') }}" class="nav-link {{ request()->routeIs('faculty.borrows.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📋</span> My Borrows
                </a>
                <a href="{{ route('faculty.penalties.index') }}" class="nav-link {{ request()->routeIs('faculty.penalties.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💰</span> My Penalties
                </a>
                <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💬</span> Messages
                </a>
                <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">👤</span> My Profile
                </a>

            @elseif(Auth::user()->role === 'student')
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">🏠</span> Dashboard
                </a>
                <div x-data="{ open: {{ request()->routeIs('digital.*') || request()->routeIs('student.books.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="nav-link w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                        <span class="flex items-center gap-3"><span class="text-base">📖</span> Browse Books</span>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="ml-4 mt-0.5 space-y-0.5">
                        <a href="{{ route('digital.index') }}" class="nav-link {{ request()->routeIs('digital.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-xl text-sm hover:bg-white/10">
                            <span>💻</span> Digital Books
                        </a>
                        <a href="{{ route('student.books.index') }}" class="nav-link {{ request()->routeIs('student.books.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2 rounded-xl text-sm hover:bg-white/10">
                            <span>📚</span> Physical Books
                        </a>
                    </div>
                </div>
                <a href="{{ route('student.borrows.index') }}" class="nav-link {{ request()->routeIs('student.borrows.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">📋</span> My Borrows
                </a>
                <a href="{{ route('student.penalties.index') }}" class="nav-link {{ request()->routeIs('student.penalties.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💰</span> My Penalties
                </a>
                <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">💬</span> Messages
                </a>
                <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10">
                    <span class="text-base">👤</span> My Profile
                </a>
            @endif
        </nav>

        <!-- User & Logout -->
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-300 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-white/10 text-red-300 hover:text-red-200">
                    <span class="text-base">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="lg:ml-64 flex-1 flex flex-col min-h-screen pb-16 lg:pb-0">

        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile only) -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h2 class="text-base lg:text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm text-gray-400">{{ now()->format('F d, Y') }}</span>
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-6 flex-1">
            @yield('content')
        </main>
    </div>

    <!-- Bottom Nav (mobile only) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-20 shadow-lg">
        <div class="flex">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-xs mt-0.5">Home</span>
                </a>
                <a href="{{ route('admin.books.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="text-xs mt-0.5">Books</span>
                </a>
                <a href="{{ route('admin.borrows.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.borrows.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs mt-0.5">Borrows</span>
                </a>
                <a href="{{ route('messages.index') }}" class="bottom-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-xs mt-0.5">Messages</span>
                </a>
                <button @click="sidebarOpen = true" class="flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span class="text-xs mt-0.5">More</span>
                </button>

            @elseif(Auth::user()->role === 'faculty')
                <a href="{{ route('faculty.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-xs mt-0.5">Home</span>
                </a>
                <a href="{{ route('digital.index') }}" class="bottom-nav-item {{ request()->routeIs('digital.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="text-xs mt-0.5">Books</span>
                </a>
                <a href="{{ route('faculty.borrows.index') }}" class="bottom-nav-item {{ request()->routeIs('faculty.borrows.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs mt-0.5">Borrows</span>
                </a>
                <a href="{{ route('messages.index') }}" class="bottom-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-xs mt-0.5">Messages</span>
                </a>
                <button @click="sidebarOpen = true" class="flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span class="text-xs mt-0.5">More</span>
                </button>

            @elseif(Auth::user()->role === 'student')
                <a href="{{ route('student.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-xs mt-0.5">Home</span>
                </a>
                <a href="{{ route('digital.index') }}" class="bottom-nav-item {{ request()->routeIs('digital.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="text-xs mt-0.5">Books</span>
                </a>
                <a href="{{ route('student.borrows.index') }}" class="bottom-nav-item {{ request()->routeIs('student.borrows.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span class="text-xs mt-0.5">Borrows</span>
                </a>
                <a href="{{ route('messages.index') }}" class="bottom-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }} flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="text-xs mt-0.5">Messages</span>
                </a>
                <button @click="sidebarOpen = true" class="flex-1 flex flex-col items-center py-2 text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span class="text-xs mt-0.5">More</span>
                </button>
            @endif
        </div>
    </nav>

</body>
</html>