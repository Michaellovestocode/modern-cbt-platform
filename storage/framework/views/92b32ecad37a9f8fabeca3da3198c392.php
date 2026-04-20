<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambridge International School - Education for a Bright Future</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'school-blue': '#1E40AF',
                        'school-yellow': '#FCD34D',
                        'school-green': '#10B981',
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Poppins', sans-serif; }

        /* ── Blob animations ── */
        .blob-1 {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation: blob-anim 8s ease-in-out infinite;
        }
        .blob-2 {
            border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
            animation: blob-anim 7s ease-in-out infinite reverse;
        }
        @keyframes blob-anim {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50%       { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }

        /* ── Hover cards ── */
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,.15);
        }

        /* ── Student circles ── */
        .student-circle {
            width: 150px; height: 150px;
            border-radius: 50% 40% 60% 50%;
            object-fit: cover;
        }
        @media (max-width: 768px) {
            .student-circle { width: 120px; height: 120px; }
        }

        /* ── Announcement ticker ── */
        .ticker-wrap { overflow: hidden; }
        .ticker-track {
            display: flex;
            white-space: nowrap;
            animation: ticker 28s linear infinite;
        }
        .ticker-track:hover { animation-play-state: paused; }
        @keyframes ticker {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ── Fade-in on scroll ── */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Testimonial slider ── */
        .testimonial-slide { display: none; }
        .testimonial-slide.active { display: block; }

        /* ── Active nav link ── */
        .nav-link.active { color: #2563EB; }
        .nav-link.active::after {
            content: '';
            display: block;
            height: 2px;
            background: #2563EB;
            border-radius: 2px;
            margin-top: 2px;
        }

        /* ── WhatsApp floating button pulse ── */
        @keyframes whatsapp-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(37,211,102,.6); }
            70%  { box-shadow: 0 0 0 14px rgba(37,211,102,0); }
            100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
        }
        .whatsapp-btn { animation: whatsapp-pulse 2s infinite; }

        /* ── Back-to-top ── */
        #backToTop {
            opacity: 0; pointer-events: none;
            transition: opacity 0.4s ease;
        }
        #backToTop.visible { opacity: 1; pointer-events: auto; }

        /* ── Lightbox ── */
        #lightbox {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #lightbox.open { display: flex; }

        /* ── Sticky nav shadow on scroll ── */
        .nav-scrolled { box-shadow: 0 4px 24px rgba(0,0,0,.12) !important; }

        /* ── Accordion ── */
        .faq-body {
            max-height: 0; overflow: hidden;
            transition: max-height 0.4s ease;
        }
        .faq-body.open { max-height: 300px; }

        /* ── Progress bar ── */
        #pageProgress {
            position: fixed; top: 0; left: 0;
            height: 3px; width: 0;
            background: linear-gradient(90deg,#2563EB,#10B981);
            z-index: 9999;
            transition: width 0.1s linear;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-yellow-50 to-green-50">

<!-- Page read progress bar -->
<div id="pageProgress"></div>

<!-- ══════════════════════════════════════════
     ANNOUNCEMENT TICKER
════════════════════════════════════════════ -->
<div class="bg-gradient-to-r from-blue-700 to-green-600 text-white text-sm py-2 ticker-wrap">
    <div class="ticker-track gap-x-16">
        <span class="px-6">📢 2026/2027 Admission is NOW OPEN — Apply Today!</span>
        <span class="px-6">🏆 Cambridge Students Win State Science Olympiad 2025</span>
        <span class="px-6">📅 Next PTA Meeting: Saturday, 19 April 2026</span>
        <span class="px-6">🎓 WAEC Registration Deadline: 30 April 2026</span>
        <span class="px-6">💻 CBT Mock Exams begin Monday 14 April — Log in to your portal</span>
        <!-- duplicate for seamless loop -->
        <span class="px-6">📢 2025/2026 Admission is NOW OPEN — Apply Today!</span>
        <span class="px-6">🏆 Cambridge Students Win State Science Olympiad 2025</span>
        <span class="px-6">📅 Next PTA Meeting: Saturday, 19 April 2026</span>
        <span class="px-6">🎓 WAEC Registration Deadline: 30 April 2026</span>
        <span class="px-6">💻 CBT Mock Exams begin Monday 14 April — Log in to your portal</span>
    </div>
</div>

<!-- ══════════════════════════════════════════
     MOBILE MENU OVERLAY
════════════════════════════════════════════ -->
<div id="mobileMenu" class="fixed inset-0 bg-black/95 z-50 hidden">
    <div class="flex justify-end p-6">
        <button onclick="toggleMenu()" class="text-white" aria-label="Close menu">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="flex flex-col items-center justify-center space-y-8 text-white text-2xl font-semibold mt-12">
        <a href="#home"        onclick="toggleMenu()" class="hover:text-yellow-400 transition">Home</a>
        <a href="#programs"    onclick="toggleMenu()" class="hover:text-yellow-400 transition">Programs</a>
        <a href="#about"       onclick="toggleMenu()" class="hover:text-yellow-400 transition">About</a>
        <a href="#news"        onclick="toggleMenu()" class="hover:text-yellow-400 transition">News</a>
        <a href="#gallery"     onclick="toggleMenu()" class="hover:text-yellow-400 transition">Gallery</a>
        <a href="#faq"         onclick="toggleMenu()" class="hover:text-yellow-400 transition">FAQ</a>
        <a href="#contact"     onclick="toggleMenu()" class="hover:text-yellow-400 transition">Contact</a>
        <a href="/login" class="bg-gradient-to-r from-blue-600 to-green-600 px-8 py-3 rounded-full mt-4">Login</a>
    </div>
</div>

<!-- ══════════════════════════════════════════
     NAVIGATION
════════════════════════════════════════════ -->
<nav id="mainNav" class="fixed w-full z-40 bg-white/90 backdrop-blur-lg shadow-md transition-shadow duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-600 via-yellow-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg transform rotate-3">
                    <span class="text-white font-black text-2xl -rotate-1"><img src="<?php echo e(asset('images/schoollogo.jpg')); ?>" alt="Vice Principal" class="w-full h-full object-cover rounded-2xl"></span>
                </div>
                <div>
                    <h1 class="text-xl font-black text-gray-900">Cambridge</h1>
                    <p class="text-xs text-gray-600 font-semibold">International School</p>
                </div>
            </div>

            <!-- Desktop nav links -->
            <div class="hidden lg:flex space-x-6">
                <a href="#home"     class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">Home</a>
                <a href="#programs" class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">Programs</a>
                <a href="#about"    class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">About</a>
                <a href="#news"     class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">News</a>
                <a href="#gallery"  class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">Gallery</a>
                <a href="#faq"      class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">FAQ</a>
                <a href="#contact"  class="nav-link text-gray-700 hover:text-blue-600 font-semibold transition text-sm">Contact</a>
            </div>

            <div class="flex items-center space-x-4">
                <a href="/login" class="hidden sm:block bg-gradient-to-r from-blue-600 to-green-600 text-white px-6 py-2.5 rounded-full font-bold hover:shadow-xl transition transform hover:scale-105">
                    Login
                </a>
                <button onclick="toggleMenu()" class="lg:hidden" aria-label="Open menu">
                    <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- ══════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════ -->
<section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
    <div class="absolute top-20 right-10 w-64 h-64 bg-yellow-300 opacity-30 blob-1 blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-80 h-80 bg-blue-400 opacity-20 blob-2 blur-3xl"></div>
    <div class="absolute top-40 left-1/4 w-40 h-40 bg-green-400 opacity-25 blob-1 blur-2xl"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left -->
            <div class="text-center lg:text-left fade-in-up">
                <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-100 to-green-100 border-2 border-blue-200 rounded-full px-5 py-2 mb-6 sm:mb-8">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-gray-800 text-sm font-bold">🇳🇬 Best School in Warri</span>
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-gray-900 mb-6 leading-tight">
                    Education for<br>a <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-purple-600 to-green-600">Bright Future.</span>
                </h1>

                <p class="text-lg sm:text-xl text-gray-600 mb-8 sm:mb-10 max-w-xl mx-auto lg:mx-0">
                    A modern learning environment where education meets innovation, inspiring every student to succeed.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#contact" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl hover:shadow-2xl transition transform hover:scale-105 flex items-center justify-center space-x-2">
                        <span>📚 Apply Now</span>
                    </a>
                    <a href="/login" class="bg-white border-2 border-gray-200 text-gray-800 px-8 py-4 rounded-full font-bold text-lg hover:border-blue-600 hover:text-blue-600 transition flex items-center justify-center space-x-2">
                        <span>🎓Login</span>
                    </a>
                </div>
            </div>

            <!-- Right – student photos -->
            <div class="relative fade-in-up" style="transition-delay:.2s">
                <div class="grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="flex justify-end">
                        <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-1 shadow-2xl card-hover" style="border-radius:60% 40% 60% 50%">
                            <img src="<?php echo e(asset('images/heropage3.png')); ?>"  alt="Student" class="student-circle bg-purple-100">
                        </div>
                    </div>
                    <div class="mt-8 sm:mt-16">
    <div class="bg-gradient-to-br from-cyan-400 to-cyan-600 p-1 shadow-2xl card-hover" style="border-radius:40% 60% 50% 60%">
        <img src="<?php echo e(asset('images/herobox1.png')); ?>" alt="Student" class="student-circle bg-cyan-100">
    </div>
</div>
                    <div class="flex justify-end -mt-4 sm:-mt-8">
                        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-1 shadow-2xl card-hover" style="border-radius:50% 60% 40% 60%">
                            <img src="<?php echo e(asset('images/clear.jfif')); ?>" alt="Student" class="student-circle bg-yellow-100">
                        </div>
                    </div>
                    <div class="mt-0 sm:mt-4">
                        <div class="bg-gradient-to-br from-pink-400 to-pink-600 p-1 shadow-2xl card-hover" style="border-radius:60% 50% 60% 40%">
                            <img src="<?php echo e(asset('images/heropage2.png')); ?>" alt="Student" class="student-circle bg-pink-100">
        
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-yellow-400 rounded-full opacity-70 animate-bounce"></div>
                <div class="absolute top-10 -left-4 w-16 h-16 bg-green-400 rounded-full opacity-60"></div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     PROGRAMS SECTION
════════════════════════════════════════════ -->
<section id="programs" class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16 fade-in-up">
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 mb-4">Our Programs</h2>
            <p class="text-lg sm:text-xl text-gray-600">Comprehensive educational programs designed to nurture every aspect of student development.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Primary -->
            <div class="card-hover bg-gradient-to-br from-blue-500 to-blue-700 p-8 rounded-3xl shadow-xl text-white fade-in-up">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black mb-3">Primary School</h3>
                <p class="text-blue-100 text-sm leading-relaxed mb-4">Building strong foundations for young learners from grades 1–6.</p>
                <div class="text-xs font-semibold text-blue-200 mb-4">Ages 6–12</div>
                <a href="#contact" class="inline-block text-xs bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition">Learn More →</a>
            </div>

            <!-- High School -->
            <div class="card-hover bg-gradient-to-br from-green-500 to-emerald-700 p-8 rounded-3xl shadow-xl text-white fade-in-up" style="transition-delay:.1s">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black mb-3">Secondary School</h3>
                <p class="text-green-100 text-sm leading-relaxed mb-4">Preparing students for higher education and career success.</p>
                <div class="text-xs font-semibold text-green-200 mb-4">JSS &amp; SSS</div>
                <a href="#contact" class="inline-block text-xs bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition">Learn More →</a>
            </div>

            <!-- Digital Learning -->
            <div class="card-hover bg-gradient-to-br from-purple-500 to-pink-600 p-8 rounded-3xl shadow-xl text-white fade-in-up" style="transition-delay:.2s">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black mb-3">Digital Learning</h3>
                <p class="text-purple-100 text-sm leading-relaxed mb-4">Innovative education with modern technology and digital tools.</p>
                <div class="text-xs font-semibold text-purple-200 mb-4">CBT Platform</div>
                <a href="/login" class="inline-block text-xs bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition">Open Portal →</a>
            </div>

            <!-- Co-Curricular -->
            <div class="card-hover bg-gradient-to-br from-orange-500 to-red-600 p-8 rounded-3xl shadow-xl text-white fade-in-up" style="transition-delay:.3s">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black mb-3">Co-Curricular</h3>
                <p class="text-orange-100 text-sm leading-relaxed mb-4">Sports, arts, and extracurricular activities for holistic growth.</p>
                <div class="text-xs font-semibold text-orange-200 mb-4">All Levels</div>
                <a href="#gallery" class="inline-block text-xs bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-full transition">See Photos →</a>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FEATURES / WHY CAMBRIDGE
════════════════════════════════════════════ -->
<section id="about" class="py-16 sm:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Image -->
            <div class="order-2 lg:order-1 fade-in-up">
                <div class="relative">
                    <img src="<?php echo e(asset('images/excursion1.jpg')); ?>" alt="Classroom" class="rounded-3xl shadow-2xl w-full">
                    <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-yellow-400 to-orange-500 p-6 rounded-2xl shadow-2xl">
                        <div class="text-white">
                            <div class="text-4xl font-black mb-1">98%</div>
                            <div class="text-sm font-semibold">Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="order-1 lg:order-2 fade-in-up" style="transition-delay:.15s">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-6">
                    Why Choose <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-green-600">Cambridge?</span>
                </h2>

                <div class="space-y-4">
                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl shadow-md card-hover">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">WAEC/NECO Accredited</h4>
                            <p class="text-sm text-gray-600">Full Nigerian curriculum alignment with international standards</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl shadow-md card-hover">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Modern Facilities</h4>
                            <p class="text-sm text-gray-600">State-of-the-art classrooms with digital learning tools</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl shadow-md card-hover">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Expert Teachers</h4>
                            <p class="text-sm text-gray-600">Highly qualified educators passionate about student success</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl shadow-md card-hover">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Parent Engagement</h4>
                            <p class="text-sm text-gray-600">Real-time updates and transparent communication</p>
                        </div>
                    </div>

                    <!-- NEW: School bus / safety feature -->
                    <div class="flex items-start space-x-4 bg-white p-5 rounded-2xl shadow-md card-hover">
                        <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17h8M8 17v-2a4 4 0 014-4h0a4 4 0 014 4v2M3 12l2-7h14l2 7M5 12h14"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Boarding Facilities</h4>
                            <p class="text-sm text-gray-600">Safe and comfortable accommodation for students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     STAFF SECTION  (NEW)
════════════════════════════════════════════ -->
<section class="py-16 sm:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16 fade-in-up">
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 mb-4">Meet Our Expert Team</h2>
            <p class="text-lg sm:text-xl text-gray-600">Dedicated educators and professionals committed to nurturing every student's potential</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6 sm:gap-8">
            <!-- Staff Member 1 -->


             <!-- Staff Member 1 -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up">
                <div class="relative">
    <img src="<?php echo e(asset('images/Director2.jpg')); ?>" alt="Director" class="w-full h-64 object-cover">
    
    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
        Director
    </div>
</div>
                <div class="p-6 text-center">
                    <h3 class="font-black text-gray-900 text-lg mb-1">Mrs. Precious Awe</h3>
                    <p class="text-blue-600 font-semibold text-sm mb-3">School Director</p>
                    <p class="text-gray-600 text-sm leading-relaxed">20+ years of educational leadership, PhD in Educational Administration</p>
                </div>
            </div>



            <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up">
                <div class="relative">
    <img src="<?php echo e(asset('images/Director.jpg')); ?>" alt="Director" class="w-full h-64 object-cover">
    
    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
        Director
    </div>
</div>
                <div class="p-6 text-center">
                    <h3 class="font-black text-gray-900 text-lg mb-1">Mrs. Precious Awe</h3>
                    <p class="text-blue-600 font-semibold text-sm mb-3">School Director</p>
                    <p class="text-gray-600 text-sm leading-relaxed">20+ years of educational leadership, PhD in Educational Administration</p>
                </div>
            </div>

            <!-- Staff Member 2 -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up" style="transition-delay:.1s">
                <div class="relative">
    <img src="<?php echo e(asset('images/principal.jpg')); ?>" alt="Principal" class="w-full h-64 object-cover">
    
    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
        Principal
    </div>
</div>
                <div class="p-6 text-center">
                    <h3 class="font-black text-gray-900 text-lg mb-1">Mr. Eziyi John</h3>
                    <p class="text-green-600 font-semibold text-sm mb-3">Principal</p>
                    <p class="text-gray-600 text-sm leading-relaxed">MSc Mathematics, WAEC examiner, 15 years teaching experience</p>
                </div>
            </div>

            <!-- Staff Member 3 -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up" style="transition-delay:.2s">
                <div class="relative">
    <img src="<?php echo e(asset('images/admin1.jpg')); ?>" alt="Principal" class="w-full h-64 object-cover">
    
    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
        Administrator
    </div>
</div>
                <div class="p-6 text-center">
                    <h3 class="font-black text-gray-900 text-lg mb-1">Mrs Atibaka Toritseju Louisa</h3>
                    <p class="text-purple-600 font-semibold text-sm mb-3">Administrator</p>
                    <p class="text-gray-600 text-sm leading-relaxed">BSc Biology/Chemistry, Laboratory specialist, STEM enthusiast</p>
                </div>
            </div>

            <!-- Staff Member 4 -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up" style="transition-delay:.3s">
                <div class="relative">
    <img src="<?php echo e(asset('images/vice-principal1.jpg')); ?>" alt="Principal" class="w-full h-64 object-cover">
    
    <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
        Vice Principal
    </div>
</div>
                <div class="p-6 text-center">
                    <h3 class="font-black text-gray-900 text-lg mb-1">Mr. Awonuga Daniel Olalekan</h3>
                    <p class="text-orange-600 font-semibold text-sm mb-3">Vice Principal</p>
                    <p class="text-gray-600 text-sm leading-relaxed">MA English Literature, Published author, Drama club coordinator</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     STATS SECTION
════════════════════════════════════════════ -->
<section class="py-16 sm:py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-green-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center fade-in-up">
                <div class="text-5xl sm:text-6xl font-black text-white mb-2 counter" data-target="500">0</div>
                <div class="text-white/90 font-semibold text-sm sm:text-base">Happy Students</div>
            </div>
            <div class="text-center fade-in-up" style="transition-delay:.1s">
                <div class="text-5xl sm:text-6xl font-black text-white mb-2 counter" data-target="80">0</div>
                <div class="text-white/90 font-semibold text-sm sm:text-base">Expert Teachers</div>
            </div>
            <div class="text-center fade-in-up" style="transition-delay:.2s">
                <div class="text-5xl sm:text-6xl font-black text-white mb-2 counter" data-target="15">0</div>
                <div class="text-white/90 font-semibold text-sm sm:text-base">Years Excellence</div>
            </div>
            <div class="text-center fade-in-up" style="transition-delay:.3s">
                <div class="text-5xl sm:text-6xl font-black text-white mb-2"><span class="counter" data-target="98">0</span>%</div>
                <div class="text-white/90 font-semibold text-sm sm:text-base">Success Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     NEWS & EVENTS SECTION  (NEW)
════════════════════════════════════════════ -->
<section id="news" class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 fade-in-up">
            <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Latest News &amp; Events</h2>
            <p class="text-lg text-gray-600">Stay up to date with everything happening at Cambridge</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Card 1 -->
            <article class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up border border-gray-100">
                <img src="<?php echo e(asset('images/achieve.jpg')); ?>" alt="Science Olympiad" class="w-full h-60 object-cover">
                <div class="p-6">
                    <span class="inline-block text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full mb-3">🏆 Achievement</span>
                    <h3 class="font-black text-gray-900 text-lg mb-2">Students Win World Hydrography Competition 2025</h3>
                    <p class="text-sm text-gray-500 mb-4">Our JSS3 team clinched first place in this year's state-wide science competition, beating 40 schools.</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>📅 March 28, 2025</span>
                        <a href="#contact" class="text-blue-600 font-semibold hover:underline">Read more</a>
                    </div>
                </div>
            </article>

            <!-- Card 2 -->
            <article class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up border border-gray-100" style="transition-delay:.1s">
                <img src="<?php echo e(asset('images/admission.jpg')); ?>" alt="Admission" class="w-full h-60 object-cover">
                <div class="p-6">
                    <span class="inline-block text-xs font-bold bg-green-100 text-green-700 px-3 py-1 rounded-full mb-3">📋 Admission</span>
                    <h3 class="font-black text-gray-900 text-lg mb-2">2026/2027 Admission Now Open</h3>
                    <p class="text-sm text-gray-500 mb-4">Applications for all levels are now being accepted. Secure your child's spot before the deadline.</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>📅 April 1, 2026</span>
                        <a href="#contact" class="text-blue-600 font-semibold hover:underline">Apply now</a>
                    </div>
                </div>
            </article>

            <!-- Card 3 -->
            <article class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover fade-in-up border border-gray-100" style="transition-delay:.2s">
                <img src="<?php echo e(asset('images/club1.jpg')); ?>" alt="CBT Mock Exams" class="w-full h-60 object-cover">
                <div class="p-6">
                    <span class="inline-block text-xs font-bold bg-purple-100 text-purple-700 px-3 py-1 rounded-full mb-3">🏇 Clubs</span>
                    <h3 class="font-black text-gray-900 text-lg mb-2">Students Activity Center</h3>
                    <p class="text-sm text-gray-500 mb-4">Join our vibrant student activities and clubs to enhance your school experience.</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>📅 April 10, 2026</span>
                        <a href="#" class="text-blue-600 font-semibold hover:underline">Go to portal</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     TESTIMONIALS SECTION  (NEW)
════════════════════════════════════════════ -->
<section class="py-16 sm:py-24 bg-gradient-to-br from-blue-50 to-green-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4 fade-in-up">What Parents Say</h2>
        <p class="text-lg text-gray-600 mb-12 fade-in-up">Real testimonials from families in our Cambridge community</p>

        <div class="relative bg-white rounded-3xl shadow-2xl p-8 sm:p-12 fade-in-up">
            <!-- Quote icon -->
            <div class="text-6xl text-blue-200 font-black leading-none mb-4 select-none">"</div>

            <!-- Slides -->
            <div id="testimonialContainer">
                <div class="testimonial-slide active">
                    <p class="text-lg sm:text-xl text-gray-700 italic mb-6 leading-relaxed">
                        My daughter joined Cambridge in JSS1 and her performance has been outstanding. The CBT platform especially helps her practise at home — we can see her results in real time!
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-black">A</div>
                        <div class="text-left">
                            <div class="font-bold text-gray-900">Mrs Adunola Balogun</div>
                            <div class="text-sm text-gray-500">Parent, JSS2 Student · Warri</div>
                        </div>
                        <div class="flex text-yellow-400 text-sm ml-2">★★★★★</div>
                    </div>
                </div>

                <div class="testimonial-slide">
                    <p class="text-lg sm:text-xl text-gray-700 italic mb-6 leading-relaxed">
                        The teachers are incredibly dedicated. My son's WAEC results last year — 8 A1s — speak for themselves. Cambridge is the best investment we made for his future.
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-black">K</div>
                        <div class="text-left">
                            <div class="font-bold text-gray-900">Mr Kunle Oladele</div>
                            <div class="text-sm text-gray-500">Parent, SS3 Graduate · Surulere</div>
                        </div>
                        <div class="flex text-yellow-400 text-sm ml-2">★★★★★</div>
                    </div>
                </div>

                <div class="testimonial-slide">
                    <p class="text-lg sm:text-xl text-gray-700 italic mb-6 leading-relaxed">
                        From the safe school bus to the prompt communication from teachers via WhatsApp, Cambridge makes parenthood so much easier. Highly recommended for every Lagos parent.
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-black">F</div>
                        <div class="text-left">
                            <div class="font-bold text-gray-900">Mrs Fatima Usman</div>
                            <div class="text-sm text-gray-500">Parent, Primary 4 Student · Ikeja</div>
                        </div>
                        <div class="flex text-yellow-400 text-sm ml-2">★★★★★</div>
                    </div>
                </div>
            </div>

            <!-- Dots -->
            <div class="flex justify-center space-x-2 mt-8">
                <button onclick="setSlide(0)" class="testimonial-dot w-3 h-3 rounded-full bg-blue-600 transition" aria-label="Slide 1"></button>
                <button onclick="setSlide(1)" class="testimonial-dot w-3 h-3 rounded-full bg-gray-300 transition" aria-label="Slide 2"></button>
                <button onclick="setSlide(2)" class="testimonial-dot w-3 h-3 rounded-full bg-gray-300 transition" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     GALLERY SECTION
════════════════════════════════════════════ -->
<section id="gallery" class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16 fade-in-up">
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 mb-4">School Life</h2>
            <p class="text-lg sm:text-xl text-gray-600">Experience the vibrant Cambridge community</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
            <div class="card-hover rounded-3xl overflow-hidden shadow-lg cursor-pointer fade-in-up" onclick="openLightbox(this)">
                <img src="<?php echo e(asset('images/sport.jpg')); ?>" alt="Students" class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition duration-500">
            </div>
            <div class="card-hover rounded-3xl overflow-hidden shadow-lg cursor-pointer fade-in-up" onclick="openLightbox(this)" style="transition-delay:.05s">
                <img src="<?php echo e(asset('images/school life1.jpg')); ?>" alt="Classroom" class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition duration-500">
            </div>
            <div class="card-hover rounded-3xl overflow-hidden shadow-lg cursor-pointer fade-in-up" onclick="openLightbox(this)" style="transition-delay:.1s">
                <img src="<?php echo e(asset('images/boycomputer.jpg')); ?>" alt="Learning" class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition duration-500">
            </div>
            <div class="card-hover rounded-3xl overflow-hidden shadow-lg cursor-pointer fade-in-up md:col-span-2" onclick="openLightbox(this)" style="transition-delay:.15s">
                <img src="<?php echo e(asset('images/lifee.jpg')); ?>" alt="Technology" class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition duration-500">
            </div>
            <div class="card-hover rounded-3xl overflow-hidden shadow-lg cursor-pointer fade-in-up" onclick="openLightbox(this)" style="transition-delay:.2s">
                <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?w=400&q=80" alt="Group learning" class="w-full h-48 sm:h-64 object-cover hover:scale-105 transition duration-500">
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div id="lightbox" onclick="closeLightbox()">
    <button class="absolute top-6 right-6 text-white text-4xl z-10 leading-none" onclick="closeLightbox()">✕</button>
    <img id="lightboxImg" src="" alt="Gallery" class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl object-contain">
</div>

<!-- ══════════════════════════════════════════
     FAQ SECTION  (NEW)
════════════════════════════════════════════ -->
<section id="faq" class="py-16 sm:py-24 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 fade-in-up">
            <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">Quick answers for parents and prospective students</p>
        </div>

        <div class="space-y-4 fade-in-up">
            <!-- FAQ 1 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left font-bold text-gray-900 hover:bg-gray-50 transition">
                    <span>What are the school fees for 2025/2026?</span>
                    <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-body px-6">
                    <p class="text-gray-600 text-sm pb-5 leading-relaxed">School fees vary by level. Contact our admissions office via the form below or call us for the full fee schedule. We also offer a convenient Paystack / bank transfer payment option with flexible instalment plans.</p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left font-bold text-gray-900 hover:bg-gray-50 transition">
                    <span>How does the CBT portal work?</span>
                    <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-body px-6">
                    <p class="text-gray-600 text-sm pb-5 leading-relaxed">Each student is given a unique login to access our Computer-Based Testing platform. From there they can take practice tests, view scores instantly, and track their progress across all subjects. The portal is accessible on any device.</p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left font-bold text-gray-900 hover:bg-gray-50 transition">
                    <span>Is there a school boarding facility for students?</span>
                    <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-body px-6">
                    <p class="text-gray-600 text-sm pb-5 leading-relaxed">Yes! We run GPS-tracked school buses covering Lekki, Surulere, Ikeja, and Yaba. Contact our office to confirm your route availability and bus subscription fees.</p>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left font-bold text-gray-900 hover:bg-gray-50 transition">
                    <span>When is the admission deadline?</span>
                    <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-body px-6">
                    <p class="text-gray-600 text-sm pb-5 leading-relaxed">Admissions for the 2025/2026 academic session close on 31 May 2026. We strongly advise applying early as spaces fill up quickly, especially for JSS1 and Primary 1.</p>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <button onclick="toggleFaq(this)" class="w-full flex justify-between items-center p-6 text-left font-bold text-gray-900 hover:bg-gray-50 transition">
                    <span>How can parents track their child's progress?</span>
                    <svg class="w-5 h-5 text-blue-600 transform transition-transform duration-300 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="faq-body px-6">
                    <p class="text-gray-600 text-sm pb-5 leading-relaxed">Parents receive a separate portal login where they can view their child's scores, attendance records, and teacher remarks. We also send weekly updates via WhatsApp and email.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CTA SECTION
════════════════════════════════════════════ -->
<section class="py-16 sm:py-24 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-yellow-400 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-green-400 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
        <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6">Ready to Start Your Journey?</h2>
        <p class="text-xl sm:text-2xl text-white/90 mb-10">Join thousands of students achieving excellence at Cambridge</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#contact" class="bg-white text-gray-900 px-10 py-4 rounded-full font-black text-lg hover:shadow-2xl transition transform hover:scale-105">
                Apply for Admission
            </a>
            <a href="/login" class="bg-white/20 backdrop-blur-sm border-2 border-white text-white px-10 py-4 rounded-full font-black text-lg hover:bg-white/30 transition">
                Portal Login
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CONTACT / ADMISSION FORM  (UPGRADED)
════════════════════════════════════════════ -->
<section id="contact" class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <!-- Info -->
            <div class="fade-in-up">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-4">Get In Touch</h2>
                <p class="text-lg text-gray-600 mb-8">Have questions about admissions, fees, or our CBT portal? We're here to help.</p>

                <div class="space-y-5 mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📍</div>
                        <div>
                            <div class="font-bold text-gray-900">Address</div>
                            <div class="text-gray-500 text-sm">Delta, Nigeria</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-xl">📧</div>
                        <div>
                            <div class="font-bold text-gray-900">Email</div>
                            <a href="mailto:info@cambridge.ng" class="text-blue-600 text-sm hover:underline">info@cambridge.ng</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-xl">📱</div>
                        <div>
                            <div class="font-bold text-gray-900">Phone / WhatsApp</div>
                            <a href="https://wa.me/234XXXXXXXXXX" target="_blank" class="text-blue-600 text-sm hover:underline">+234 XXX XXX XXXX</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-xl">🕐</div>
                        <div>
                            <div class="font-bold text-gray-900">Office Hours</div>
                            <div class="text-gray-500 text-sm">Mon–Fri: 7:30am – 4:00pm</div>
                        </div>
                    </div>
                </div>

                <!-- Social links -->
                <div class="flex space-x-3">
                    <a href="#" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold hover:scale-110 transition" title="Facebook">f</a>
                    <a href="#" class="w-10 h-10 bg-gradient-to-br from-pink-500 to-orange-400 text-white rounded-full flex items-center justify-center text-sm font-bold hover:scale-110 transition" title="Instagram">ig</a>
                    <a href="https://wa.me/234XXXXXXXXXX" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold hover:scale-110 transition" title="WhatsApp">wa</a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-gradient-to-br from-blue-50 to-green-50 p-8 rounded-3xl shadow-lg fade-in-up" style="transition-delay:.15s">
                <h3 class="text-2xl font-black text-gray-900 mb-6">Admission Enquiry</h3>
                <div id="formSuccess" class="hidden bg-green-100 border border-green-300 text-green-800 rounded-xl p-4 mb-6 text-sm font-semibold">
                    ✅ Thank you! We'll be in touch within 24 hours.
                </div>
                <div class="space-y-4" id="admissionForm">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Parent / Guardian Name *</label>
                            <input type="text" id="parentName" placeholder="e.g. Mrs Balogun" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone / WhatsApp *</label>
                            <input type="tel" id="phone" placeholder="+234 XXX XXX XXXX" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" placeholder="parent@email.com" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Student's Name *</label>
                            <input type="text" id="studentName" placeholder="e.g. Tunde Balogun" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Class Applying For *</label>
                            <select id="classLevel" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                <option value="">-- Select Level --</option>
                                <optgroup label="Primary School">
                                    <option>Primary 1</option>
                                    <option>Primary 2</option>
                                    <option>Primary 3</option>
                                    <option>Primary 4</option>
                                    <option>Primary 5</option>
                                    <option>Primary 6</option>
                                </optgroup>
                                <optgroup label="Junior Secondary">
                                    <option>JSS 1</option>
                                    <option>JSS 2</option>
                                    <option>JSS 3</option>
                                </optgroup>
                                <optgroup label="Senior Secondary">
                                    <option>SS 1</option>
                                    <option>SS 2</option>
                                    <option>SS 3</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Message / Questions</label>
                        <textarea id="message" rows="3" placeholder="Any questions about fees, transport, or the CBT portal?" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white resize-none"></textarea>
                    </div>
                    <button onclick="submitForm()" class="w-full bg-gradient-to-r from-blue-600 to-green-600 text-white py-4 rounded-xl font-bold text-base hover:shadow-xl transition transform hover:scale-[1.02]">
                        Send Enquiry 🚀
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FOOTER
════════════════════════════════════════════ -->
<footer class="bg-gray-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- About -->
            <div class="sm:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 via-yellow-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-black text-xl">C</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black">Cambridge International</h3>
                        <p class="text-sm text-gray-400">Education for a Bright Future</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-6 leading-relaxed max-w-md">
                    Empowering students with world-class education and modern learning tools for success in the 21st century.
                </p>
                <div class="flex space-x-3">
                    <a href="#" class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold hover:scale-110 transition">f</a>
                    <a href="#" class="w-9 h-9 bg-gradient-to-br from-pink-500 to-orange-400 rounded-full flex items-center justify-center text-xs font-bold hover:scale-110 transition">ig</a>
                    <a href="https://wa.me/234XXXXXXXXXX" target="_blank" class="w-9 h-9 bg-green-500 rounded-full flex items-center justify-center text-xs font-bold hover:scale-110 transition">wa</a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-bold text-lg mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#home"     class="hover:text-yellow-400 transition">Home</a></li>
                    <li><a href="#programs" class="hover:text-yellow-400 transition">Programs</a></li>
                    <li><a href="#news"     class="hover:text-yellow-400 transition">News &amp; Events</a></li>
                    <li><a href="#gallery"  class="hover:text-yellow-400 transition">Gallery</a></li>
                    <li><a href="#faq"      class="hover:text-yellow-400 transition">FAQ</a></li>
                    <li><a href="/login"    class="hover:text-yellow-400 transition">Student Portal</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-bold text-lg mb-4">Contact</h4>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-start space-x-2"><span>📍</span><span>Lagos, Nigeria</span></li>
                    <li class="flex items-start space-x-2"><span>📧</span><a href="mailto:info@cambridge.ng" class="hover:text-yellow-400 transition">info@cambridge.ng</a></li>
                    <li class="flex items-start space-x-2"><span>📱</span><a href="https://wa.me/234XXXXXXXXXX" target="_blank" class="hover:text-yellow-400 transition">+234 XXX XXX XXXX</a></li>
                    <li class="flex items-start space-x-2"><span>🕐</span><span>Mon–Fri: 7:30am–4:00pm</span></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; 2026 Cambridge International School. All rights reserved. | Built with ❤️ in Lagos</p>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════════════════
     FLOATING BUTTONS
════════════════════════════════════════════ -->
<!-- WhatsApp float -->
<a href="https://wa.me/234XXXXXXXXXX" target="_blank"
   class="whatsapp-btn fixed bottom-24 right-6 z-50 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition"
   title="Chat on WhatsApp" aria-label="WhatsApp">
    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M20.52 3.48A11.93 11.93 0 0012.01 0C5.37 0 .01 5.37.01 12c0 2.11.55 4.16 1.6 5.97L0 24l6.19-1.62A11.95 11.95 0 0012.01 24c6.63 0 11.99-5.37 11.99-12 0-3.2-1.25-6.22-3.48-8.52zM12.01 21.9a9.88 9.88 0 01-5.04-1.38l-.36-.21-3.74.98 1-3.63-.24-.38A9.83 9.83 0 012.1 12c0-5.46 4.44-9.9 9.91-9.9 2.65 0 5.13 1.03 6.99 2.91a9.84 9.84 0 012.9 6.99c0 5.47-4.44 9.9-9.89 9.9zm5.44-7.4c-.3-.15-1.76-.87-2.03-.97-.28-.1-.48-.15-.68.15-.2.3-.76.97-.93 1.17-.17.2-.34.22-.64.07-.3-.15-1.25-.46-2.39-1.47-.88-.79-1.47-1.76-1.64-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.68-1.63-.93-2.23-.24-.58-.49-.5-.68-.51-.18-.01-.37-.01-.57-.01-.2 0-.52.07-.79.37-.28.3-1.05 1.03-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.62.72.23 1.37.2 1.89.12.57-.09 1.76-.72 2.01-1.42.24-.69.24-1.29.17-1.41-.07-.13-.27-.2-.57-.35z"/>
    </svg>
</a>

<!-- Back to top -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})"
   class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-gradient-to-br from-blue-600 to-green-600 text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition"
   aria-label="Back to top">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
</button>

<!-- ══════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════ -->
<script>
    /* ── Mobile Menu ── */
    function toggleMenu() {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    }

    /* ── Counter Animation ── */
    function animateCounter(el) {
        const target = parseInt(el.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                el.textContent = Math.floor(current).toLocaleString();
            }
        }, 16);
    }

    const counterObserver = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animateCounter(e.target);
                counterObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('.counter').forEach(c => counterObserver.observe(c));

    /* ── Fade-in on scroll ── */
    const fadeObserver = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                fadeObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.fade-in-up').forEach(el => fadeObserver.observe(el));

    /* ── Smooth scroll ── */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const t = document.querySelector(this.getAttribute('href'));
            if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    /* ── Page progress bar ── */
    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        document.getElementById('pageProgress').style.width = pct + '%';
    });

    /* ── Nav active section highlight ── */
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');
    const sectionObserver = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                navLinks.forEach(l => l.classList.remove('active'));
                const active = document.querySelector(`.nav-link[href="#${e.target.id}"]`);
                if (active) active.classList.add('active');
            }
        });
    }, { threshold: 0.4 });
    sections.forEach(s => sectionObserver.observe(s));

    /* ── Nav shadow on scroll ── */
    window.addEventListener('scroll', () => {
        document.getElementById('mainNav').classList.toggle('nav-scrolled', window.scrollY > 20);
        document.getElementById('backToTop').classList.toggle('visible', window.scrollY > 400);
    });

    /* ── Testimonial Slider ── */
    let currentSlide = 0;
    function setSlide(n) {
        const slides = document.querySelectorAll('.testimonial-slide');
        const dots   = document.querySelectorAll('.testimonial-dot');
        slides.forEach((s,i) => s.classList.toggle('active', i === n));
        dots.forEach((d,i) => {
            d.classList.toggle('bg-blue-600', i === n);
            d.classList.toggle('bg-gray-300', i !== n);
        });
        currentSlide = n;
    }
    setInterval(() => setSlide((currentSlide + 1) % 3), 5000);

    /* ── Gallery Lightbox ── */
    function openLightbox(el) {
        const src = el.querySelector('img').src;
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    /* ── FAQ Accordion ── */
    function toggleFaq(btn) {
        const body = btn.nextElementSibling;
        const icon = btn.querySelector('.faq-icon');
        const isOpen = body.classList.contains('open');
        // close all
        document.querySelectorAll('.faq-body').forEach(b => b.classList.remove('open'));
        document.querySelectorAll('.faq-icon').forEach(i => i.style.transform = '');
        // open clicked if it was closed
        if (!isOpen) {
            body.classList.add('open');
            icon.style.transform = 'rotate(180deg)';
        }
    }

    /* ── Contact Form (demo) ── */
    function submitForm() {
        const parent  = document.getElementById('parentName').value.trim();
        const phone   = document.getElementById('phone').value.trim();
        const student = document.getElementById('studentName').value.trim();
        const level   = document.getElementById('classLevel').value;
        if (!parent || !phone || !student || !level) {
            alert('Please fill in all required fields (*)');
            return;
        }
        document.getElementById('admissionForm').style.opacity = '0.4';
        document.getElementById('admissionForm').style.pointerEvents = 'none';
        document.getElementById('formSuccess').classList.remove('hidden');
        window.scrollTo({ top: document.getElementById('contact').offsetTop - 80, behavior: 'smooth' });
    }
</script>
</body>
</html>


<?php /**PATH C:\laragon\www\modern-cbt-platform-for-highschool\resources\views/welcome.blade.php ENDPATH**/ ?>