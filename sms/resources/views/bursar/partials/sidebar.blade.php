<div class="col-md-3 col-lg-2 d-md-block bg-dark text-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <div class="bg-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 60px; height: 60px;">
                            <span class="text-white fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-white-50">Bursar • Finance Manager</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="{{ route('bursar.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('finance.fee-structures.index') }}">
                                Fee Structures
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('finance.invoices.index') }}">
                                Invoices
                                <span class="badge bg-primary float-end">{{ $stats['pending_invoices'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('finance.invoices.index')}}">
                                Payments
                                <span class="badge bg-success float-end">{{ $stats['recent_payments'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('bursar.reports.index')}}">
                                Financial Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Collection Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Outstanding Fees
                                <span class="badge bg-danger float-end">{{ $stats['students_with_balance'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Receipts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                Fee Announcements
                            </a>
                        </li>
                    </ul>
                </div>
            </div>