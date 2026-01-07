<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
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
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('teacher.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('teacher.my-classes')}}">
                                My Classes
                                <span class="badge bg-primary float-end">{{ $stats['total_classes'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('teacher.attendance.select')}}">
                                Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Assessments
                                <span class="badge bg-warning float-end">{{ $stats['pending_assessments'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('teacher.grades.index')}}">
                                Grades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Timetable
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Students
                                <span class="badge bg-info float-end">{{ $stats['total_students'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Messages
                                <span class="badge bg-primary float-end">{{ $stats['unread_messages'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Announcements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('teacher.reports.index')}}">
                                Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>