<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            @if(auth()->user()->studentProfile && auth()->user()->studentProfile->photo)
                <img src="{{ asset('storage/' . auth()->user()->studentProfile->photo) }}" 
                     class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 60px; height: 60px;">
                    <span class="text-primary fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            @endif
            <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">Student • {{ auth()->user()->studentProfile->classLevel->name ?? 'N/A' }}</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.timetable') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.timetable') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Timetable
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.attendance') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.attendance') }}">
                    <i class="fas fa-calendar-check me-2"></i>
                    Attendance
                    <span class="badge bg-success float-end">{{ $stats['attendance_rate'] ?? '0' }}%</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white-50" href="">
                    <i class="fas fa-chart-bar me-2"></i>
                    Results
                    <span class="badge bg-info float-end">{{ $stats['average_grade'] ?? 'N/A' }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.books') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.books') }}">
                    <i class="fas fa-book-open me-2"></i>
                    Study Books
                    <span class="badge bg-info float-end">{{ $stats['new_books'] ?? 0 }}</span>
                </a>
            </li> 
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Fees
                    @if(($stats['fee_balance'] ?? 0) > 0)
                    <span class="badge bg-danger float-end">Due</span>
                    @else
                    <span class="badge bg-success float-end">Paid</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.assignments') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.assignments') }}">
                    <i class="fas fa-tasks me-2"></i>
                    Assignments
                    <span class="badge bg-warning float-end">{{ $stats['pending_assignments'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.subjects') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.subjects') }}">
                    <i class="fas fa-book me-2"></i>
                    Subjects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.teachers') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.teachers') }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.messages') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.messages') }}">
                    <i class="fas fa-comments me-2"></i>
                    Messages
                    <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('announcements.index') }}">
                    <i class="fas fa-fw fa-bullhorn"></i>
                    <span>Announcement</span>
                </a>
            </li>
        </ul>
    </div>
</nav>