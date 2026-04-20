<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cambridge International School CBT System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Sora', sans-serif; }
        body {
            background-image: url('/images/cambridge.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(2px);
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            display: none;
        }
        main { position: relative; z-index: 1; }
        .nav-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); }
        .btn-secondary { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); transition: all 0.3s ease; }
        .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4); }
        .user-badge { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .sidebar-link { transition: all 0.3s ease; }
        .sidebar-link:hover { transform: translateX(4px); }
        .success-bg { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .error-bg { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
        .fade-in { animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Modern Navigation Bar -->
    <nav class="nav-gradient text-white shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Branding -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-2xl backdrop-blur-lg">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-black">CAMBRIDGE</h1>
                        <p class="text-xs text-white/70 font-medium">CBT Platform</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    @php
                        $dashboardRoute = auth()->user()->isStudent() ? 'student.dashboard' : 'admin.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                        📊 Dashboard
                    </a>
                </div>

                <!-- User Info & Actions -->
                <div class="flex items-center space-x-3">
                    <!-- User Profile -->
                    <div class="hidden sm:flex flex-col items-end">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <span class="text-xs text-white/70 font-medium">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    
                    <!-- User Avatar -->
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="fade-in mb-6 success-bg text-white px-6 py-4 rounded-xl font-semibold shadow-lg flex items-center space-x-3">
                    <span class="text-2xl">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="fade-in mb-6 error-bg text-white px-6 py-4 rounded-xl font-semibold shadow-lg flex items-center space-x-3">
                    <span class="text-2xl">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
