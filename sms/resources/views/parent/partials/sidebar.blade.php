<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            @if(auth()->user()->parentProfile && auth()->user()->parentProfile->photo)
                <img src="{{ asset('storage/' . auth()->user()->parentProfile->photo) }}" 
                     class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 60px; height: 60px;">
                    <span class="text-info fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            @endif
            <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">Parent • {{ $stats['children_count'] ?? 0 }} Children</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.children') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.children') }}">
                    <i class="fas fa-child me-2"></i>
                    My Children
                    <span class="badge bg-primary float-end">{{ $stats['children_count'] ?? 0 }}</span>
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
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.timetable') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.timetable') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Timetable
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.teachers') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.teachers') }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.messages') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.messages') }}">
                    <i class="fas fa-comments me-2"></i>
                    Messages
                    <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.announcements') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.announcements') }}">
                    <i class="fas fa-bullhorn me-2"></i>
                    Announcements
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.meetings') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.meetings') }}">
                    <i class="fas fa-handshake me-2"></i>
                    Parent-Teacher Meetings
                </a>
            </li>
        </ul>
    </div>
</nav>