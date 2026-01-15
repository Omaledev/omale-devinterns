<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        
        {{-- PROFILE HEADER --}}
        <div class="text-center mb-4 pb-3 border-bottom border-secondary">
            @if(auth()->user()->parentProfile && auth()->user()->parentProfile->photo)
                <img src="{{ asset('storage/' . auth()->user()->parentProfile->photo) }}" 
                     class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 60px; height: 60px;">
                    <span class="text-white fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            @endif
            <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">Parent • {{ $stats['children_count'] ?? 0 }} Children</small>
        </div>

        <ul class="nav flex-column">
            
            {{-- DASHBOARD --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- CHILDREN & ACADEMICS --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.children') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.children') }}">
                    <i class="fas fa-users me-2"></i>
                    My Children
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.timetable') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.timetable') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Timetable
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.attendance') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.attendance') }}">
                    <i class="fas fa-calendar-check me-2"></i>
                    Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.results') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.results') }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Results
                </a>
            </li>

            {{-- FINANCE --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.fees') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.fees') }}">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Fee Payments
                    @if(($stats['total_fee_balance'] ?? 0) > 0)
                        <span class="badge bg-danger float-end">Due</span>
                    @endif
                </a>
            </li>

            {{-- COMMUNICATION & STAFF --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.teachers') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.teachers') }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.meetings') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.meetings') }}">
                    <i class="fas fa-handshake me-2"></i>
                    Meetings
                    @if(($stats['approved_meetings'] ?? 0) > 0)
                        <span class="badge bg-success float-end">{{ $stats['approved_meetings'] }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('messages.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('messages.index') }}">
                    <i class="fas fa-comments me-2"></i>
                    Messages
                </a>
            </li>
            
            {{-- Announcements Link --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements') || request()->routeIs('announcements.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('announcements.index') }}">
                    <i class="fas fa-bullhorn me-2"></i>
                    Announcements
                </a>
            </li>

             {{-- Report cards --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.results') ? 'active text-white' : 'text-white-50' }}" 
                href="{{ route('parent.results') }}">
                    <i class="fas fa-file-alt me-2"></i>
                    Report Cards
                </a>
            </li>
        </ul>
    </div>
</nav>