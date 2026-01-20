<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse min-vh-100">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                style="width: 60px; height: 60px;">
                <span class="text-white fw-bold fs-4">{{ substr(auth()->user()->school->name ?? 'S', 0, 1) }}</span>
            </div>
            <h6 class="text-white mb-1">{{ auth()->user()->school->name ?? 'School Name' }}</h6>
            <small class="text-white-50">School Admin</small>
        </div>

        <ul class="nav flex-column">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- Students --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.students.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.students.index') }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    Students
                </a>
            </li>

            {{-- Teachers --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.teachers.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.teachers.index') }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Teachers
                </a>
            </li>

            {{-- Parents --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.parents.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.parents.index') }}">
                    <i class="fas fa-users me-2"></i>
                    Parents
                </a>
            </li>

            {{-- Bursars --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.bursars.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.bursars.index') }}">
                    <i class="fas fa-money-check me-2"></i>
                    Bursars
                </a>
            </li>

            {{-- Class Levels --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.class-levels.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.class-levels.index') }}">
                    <i class="fas fa-door-open me-2"></i>
                    Class Levels
                </a>
            </li>

            {{-- Sections --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.sections.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.sections.index') }}">
                    <i class="fas fa-layer-group me-2"></i>
                    Sections
                </a>
            </li>

            {{-- Subjects --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.subjects.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.subjects.index') }}">
                    <i class="fas fa-book me-2"></i>
                    Subjects
                </a>
            </li>

            {{-- Timetables --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.timetables.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.timetables.index') }}">
                    <i class="fas fa-calendar-day me-2"></i>
                    Timetables
                </a>
            </li>

            {{-- Assessments --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.assessments.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.assessments.index') }}">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Assessments
                </a>
            </li>

            {{-- Fees (Dropdown) --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('finance.*') ? 'active text-white' : 'text-white-50' }} collapsed" 
                   href="#" data-bs-toggle="collapse" data-bs-target="#feesSubmenu" aria-expanded="{{ request()->routeIs('finance.*') ? 'true' : 'false' }}">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Fees
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8em;"></i>
                </a>
                <div class="collapse {{ request()->routeIs('finance.*') ? 'show' : '' }}" id="feesSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('finance.fee-structures.*') ? 'active text-white' : 'text-white-50' }}" 
                               href="{{ route('finance.fee-structures.index') }}">
                                <i class="fas fa-list-alt me-2"></i> Structure Setup
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('finance.invoices.*') ? 'active text-white' : 'text-white-50' }}" 
                               href="{{ route('finance.invoices.index') }}">
                                <i class="fas fa-file-invoice-dollar me-2"></i> Student Invoices
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Academic Sessions --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.academic-sessions.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.academic-sessions.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Academic Sessions
                </a>
            </li>

            {{-- Terms --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('schooladmin.terms.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('schooladmin.terms.index') }}">
                    <i class="fas fa-calendar-week me-2"></i>
                    Terms
                </a>
            </li>

            {{-- Notification --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('announcements.index') }}">
                    <i class="fas fa-fw fa-bullhorn me-2"></i>
                    <span>Notification</span>
                </a>
            </li>

            {{-- Transport --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-bus me-2"></i>
                    Transport
                </a>
            </li>

            {{-- Hostel --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-bed me-2"></i>
                    Hostel
                </a>
            </li>
        </ul>
    </div>
</nav>