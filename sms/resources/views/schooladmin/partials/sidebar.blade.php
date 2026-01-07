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
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.students.index') }}">
                                Students
                                <span class="badge bg-primary float-end">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.teachers.index') }}">
                                Teachers
                                <span class="badge bg-success float-end">{{ $stats['total_teachers'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.parents.index')}}">
                                Parents
                                <span class="badge bg-info float-end">{{ $stats['total_parents'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.bursars.index') }}">
                                Bursars
                                <span class="badge bg-warning float-end">{{ $stats['total_bursars'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.class-levels.index')}}">
                                Class Levels
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.sections.index') }}">
                                Sections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('schooladmin.subjects.index') }}">
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                Attendance
                            </a>
                        </li>
                       <li class="nav-item">
                            <a class="nav-link text-white-50 collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#feesSubmenu" aria-expanded="false">
                                Fees
                                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8em;"></i>
                            </a>
                            <div class="collapse" id="feesSubmenu">
                                <ul class="nav flex-column ms-3">
                                    <li class="nav-item">
                                        <a class="nav-link text-white-50" href="{{ route('finance.fee-structures.index') }}">
                                            Structure Setup
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white-50" href="{{ route('finance.invoices.index') }}">
                                          Student Invoices
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                                                <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.academic-sessions.index') }}">
                                Academic Sessions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('schooladmin.terms.index') }}">
                                Terms
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                Notice
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                Transport
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="#">
                                Hostel
                            </a>
                        </li>
                    </ul>
                </div>
</div>