<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 60px; height: 60px;">
                            <span class="text-white fw-bold fs-4">A</span>
                        </div>
                        <h6 class="text-white mb-1">{{ auth()->user()->school->name }}</h6>
                        <small class="text-white-50">School Admin</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('schooladmin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.students.index') }}">
                                <i class="fas fa-user-graduate me-2"></i>
                                Students
                                <span class="badge bg-primary float-end">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Teachers
                                <span class="badge bg-success float-end">{{ $stats['total_teachers'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.parents.index')}}">
                                <i class="fas fa-users me-2"></i>
                                Parents
                                <span class="badge bg-info float-end">{{ $stats['total_parents'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.bursars.index') }}">
                                <i class="fas fa-money-check me-2"></i>
                                Bursars
                                <span class="badge bg-warning float-end">{{ $stats['total_bursars'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.class-levels.index')}}">
                                <i class="fas fa-door-open me-2"></i>
                                Class Levels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.sections.index') }}">
                                <i class="fas fa-layer-group me-2"></i>
                                Sections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="route('schooladmin.subjects.index') }}">
                                <i class="fas fa-book me-2"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-calendar-check me-2"></i>
                                Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Fees
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.academic-sessions.index') }}">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Academic Sessions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.terms.index') }}">
                                <i class="fas fa-calendar-week me-2"></i>
                                Terms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bullhorn me-2"></i>
                                Notice
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bus me-2"></i>
                                Transport
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                <i class="fas fa-bed me-2"></i>
                                Hostel
                            </a>
                        </li>
                    </ul>
                </div>
</div>