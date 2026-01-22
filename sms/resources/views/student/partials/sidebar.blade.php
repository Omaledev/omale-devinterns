@php
    $user = auth()->user();
    $balance = 0;
    
    // Calculating Fees (Safe for everyone)
    if($user) {
        $invoices = \App\Models\Invoice::where('student_id', $user->id)->get();
        $balance = $invoices->sum('total_amount') - $invoices->sum('paid_amount');
    }

    // CRITICAL CHECKing: Does student have a profile and a class assigned?
    $hasActiveProfile = $user->studentProfile && $user->studentProfile->class_level_id;
@endphp

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        
        {{-- PROFILE HEADER --}}
        <div class="text-center mb-4">
            @if($user->studentProfile && $user->studentProfile->photo)
                <img src="{{ asset('storage/' . $user->studentProfile->photo) }}" 
                     class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
            @else
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 60px; height: 60px;">
                    <span class="text-primary fw-bold fs-4">{{ substr($user->name, 0, 1) }}</span>
                </div>
            @endif
            <h6 class="text-white mb-1">{{ $user->name }}</h6>
            
            {{-- Safe Class Name Display --}}
            <small class="text-white-50">
                Student • {{ $user->studentProfile->classLevel->name ?? 'No Class Assigned' }}
            </small>
        </div>

        <ul class="nav flex-column">
            
            {{-- SECTION 1: PUBLIC LINKS (Visible to everyone) === --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('student.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.fees') ? 'active text-white' : 'text-white-50' }}" 
                href="{{ route('student.fees') }}">  
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Fees
                    @if($balance > 0)
                        <span class="badge bg-danger float-end">Due</span>
                    @else
                        <span class="badge bg-success float-end">Paid</span>
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

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active text-white' : 'text-white-50' }}" 
                href="{{ route('announcements.index') }}">
                    <i class="fas fa-fw fa-bullhorn me-2"></i> 
                    <span>Announcement</span>
                </a>
            </li>

            {{-- SECTION 2: ACADEMIC LINKS (Only if Class is Assigned) === --}}
            @if($hasActiveProfile)
                
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
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.results') ? 'active text-white' : 'text-white-50' }}" 
                    href="{{ route('student.results') }}">
                        <i class="fas fa-chart-bar me-2"></i>
                        Results
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.books') ? 'active text-white' : 'text-white-50' }}" 
                    href="{{ route('student.books') }}">
                        <i class="fas fa-book-open me-2"></i>
                        Study Books
                    </a>
                </li> 

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.assignments') ? 'active text-white' : 'text-white-50' }}" 
                    href="{{ route('student.assignments') }}">
                        <i class="fas fa-tasks me-2"></i>
                        Assignments
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
            @endif

        </ul>
    </div>
</nav>