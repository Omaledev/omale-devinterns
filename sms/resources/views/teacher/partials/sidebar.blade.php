<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        {{-- Profile Section --}}
        <div class="text-center mb-4">
            @if(auth()->user()->teacherProfile && auth()->user()->teacherProfile->photo)
                <img src="{{ asset('storage/' . auth()->user()->teacherProfile->photo) }}" 
                     class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 60px; height: 60px;">
                    <span class="text-success fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
            @endif
            <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">Teacher • {{ auth()->user()->teacherProfile->employee_id ?? 'N/A' }}</small>
        </div>

        <ul class="nav flex-column">
            
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- My Classes --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.my-classes*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.my-classes') }}">
                    <i class="fas fa-door-open me-2"></i>
                    My Classes
                </a>
            </li>

            {{-- Attendance --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.attendance.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.attendance.select') }}">
                    <i class="fas fa-calendar-check me-2"></i>
                    Attendance
                </a>
            </li>

            {{-- Assessments --}}
            <li class="nav-item">
                {{-- Updated Route --}}
                <a class="nav-link {{ request()->routeIs('teacher.assessments.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.assessments.index') }}">
                    <i class="fas fa-tasks me-2"></i>
                    Assessments
                    {{-- Assuming $stats is passed globally, otherwise remove this badge to avoid errors --}}
                    @if(isset($stats['pending_assessments']))
                        <span class="badge bg-warning float-end">{{ $stats['pending_assessments'] }}</span>
                    @endif
                </a>
            </li>

            {{-- Grades --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.grades.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.grades.index') }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Grades
                </a>
            </li>

            {{-- Timetable --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.timetable.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.timetable.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Timetable
                </a>
            </li>
            {{-- Students --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.students.index') }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    Students
                </a>
            </li>

            {{-- Messages --}}
            <li class="nav-item">
               <a class="nav-link {{ request()->routeIs('messages.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                href="{{ route('messages.index') }}"> 
                    <i class="fas fa-comments me-2"></i>
                    Messages
                </a>
            </li>

            {{-- Notifications --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('announcements.index') }}">
                    <i class="fas fa-fw fa-bullhorn me-2"></i>
                    Notifications
                </a>
            </li>

            {{-- Reports --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.reports.*') ? 'active text-white bg-secondary bg-opacity-25 rounded' : 'text-white-50' }}" 
                   href="{{ route('teacher.reports.index') }}">
                    <i class="fas fa-chart-pie me-2"></i>
                    Reports
                </a>
            </li>
        </ul>
    </div>
</div>