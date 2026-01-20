<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Axia School Management System - Comprehensive platform for schools, students, teachers, and parents">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Axia School Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Vite handles Bootstrap CSS and JS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        .role-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }
        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .nav-link {
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <div class="bg-primary rounded d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <span class="text-white fw-bold">A</span>
                    </div>
                    <span class="fw-bold fs-4">Axia SMS</span>
                </a>

                {{-- MOBILE MESSAGE ICON (Visible on Phone, Hidden on Desktop) --}}
                @auth
                    @if(auth()->user()->hasRole(['SuperAdmin', 'SchoolAdmin', 'Teacher', 'Parent']))
                        <a class="d-lg-none ms-auto me-3 position-relative text-secondary" href="{{ route('messages.index') }}">
                            <i class="fas fa-envelope fa-lg"></i>
                            @php
                                $msgCount = \App\Models\Message::whereHas('thread.participants', function($q) {
                                    $q->where('user_id', auth()->id());
                                })->where('user_id', '!=', auth()->id())->whereNull('read_at')->count();
                            @endphp
                            
                            @if($msgCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.5rem;">
                                    {{ $msgCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                @endauth

                

                {{-- MOBILE NOTIFICATION BELL --}}
                @auth
                <a class="d-lg-none me-4 position-relative text-secondary" href="{{ route('announcements.index') }}">
                    <i class="fas fa-bell fa-lg"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.5rem;">
                            {{ auth()->user()->unreadNotifications->count() }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
                @endauth

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Center Links -->
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                    </ul>

                    <!-- Right Side Auth Links -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('sign-in'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-secondary me-2" href="{{ route('sign-in') }}">Sign In/Sign Up</a>
                                </li>
                            @endif
                        @else
                        {{-- DESKTOP MESSAGE ICON --}}
                            @if(auth()->user()->hasRole(['SuperAdmin', 'SchoolAdmin', 'Teacher', 'Parent']))
                            <li class="nav-item me-3 d-none d-lg-block">
                                <a class="nav-link position-relative" href="{{ route('messages.index') }}">
                                    <i class="fas fa-envelope fa-lg text-secondary"></i>
                                    @php
                                        // Re-run count for desktop scope
                                        $msgCountDesk = \App\Models\Message::whereHas('thread.participants', function($q) {
                                            $q->where('user_id', auth()->id());
                                        })->where('user_id', '!=', auth()->id())->whereNull('read_at')->count();
                                    @endphp

                                    @if($msgCountDesk > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.35em 0.5em;">
                                            {{ $msgCountDesk }}
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @endif
                            {{-- NOTIFICATION BELL --}}
                            <li class="nav-item dropdown me-3 d-none d-lg-block">
                                <a id="alertsDropdown" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-lg text-secondary"></i>
                                    {{-- Red Counter Badge --}}
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.35em 0.5em;">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    @endif
                                </a>
                                {{-- Dropdown Menu --}}
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in py-0" aria-labelledby="alertsDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    <div class="card border-0">
                                        <div class="card-header bg-primary text-white small fw-bold">
                                            Notification Center
                                        </div>
                                        <div class="list-group list-group-flush">
                                            @forelse(auth()->user()->unreadNotifications as $notification)
                                                <a href="{{ route('notifications.read', $notification->id) }}" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-bullhorn"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="small text-muted mb-1">{{ $notification->created_at->diffForHumans() }}</div>
                                                        <span class="d-block text-dark text-wrap small fw-bold" style="line-height: 1.4;">
                                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @empty
                                                <div class="text-center py-4">
                                                    <i class="fas fa-bell-slash text-muted mb-2"></i>
                                                    <p class="text-muted small mb-0">No new notification</p>
                                                </div>
                                            @endforelse
                                        </div>
                                        <a class="card-footer text-center small text-primary bg-white d-block py-2 text-decoration-none fw-bold" href="{{ route('announcements.index') }}">
                                            Show All Notification
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ url('/dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('sign-out') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Sign_Out') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('sign-out') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="container mt-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert" data-dismiss-time="5000">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert" data-dismiss-time="5000">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle for all my dashboards
        if (document.querySelector('.sidebar')) {
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'btn btn-primary d-md-none position-fixed';
            sidebarToggle.style.cssText = 'bottom: 20px; right: 20px; z-index: 1050; border-radius: 50%; width: 50px; height: 50px;';
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            sidebarToggle.setAttribute('aria-label', 'Toggle sidebar');

            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });

            document.body.appendChild(sidebarToggle);
        }

        const alerts = document.querySelectorAll('.auto-dismiss');
           alerts.forEach(alert => {
             const dismissTime = alert.getAttribute('data-dismiss-time') || 5000;
            
            setTimeout(() => {
                alert.classList.add('fading-out');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, dismissTime);
        });

    });

    
    </script>
</body>
</html>