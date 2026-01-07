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
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.children') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.children') }}">
                    My Children
                    <span class="badge bg-primary float-end">{{ $stats['children_count'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.attendance') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.attendance') }}">
                    Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.results') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.results') }}">
                    Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.fees') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.fees') }}">
                    Fee Payments
                    @if(($stats['total_fee_balance'] ?? 0) > 0)
                    <span class="badge bg-danger float-end">Due</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.timetable') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.timetable') }}">
                    Timetable
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.teachers') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.teachers') }}">
                    Teachers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.messages') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.messages') }}">
                    Messages
                    <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.announcements') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.announcements') }}">
                    Announcements
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('parent.meetings') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('parent.meetings') }}">
                    Parent-Teacher Meetings
                </a>
            </li>
        </ul>
    </div>
</nav>