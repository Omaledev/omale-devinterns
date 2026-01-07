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
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('finance.fee-structures.index') }}">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Fee Structures
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{ route('finance.invoices.index') }}">
                                <i class="fas fa-file-invoice me-2"></i>
                                Invoices
                                <span class="badge bg-primary float-end">{{ $stats['pending_invoices'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('finance.invoices.index')}}">
                                <i class="fas fa-credit-card me-2"></i>
                                Payments
                                <span class="badge bg-success float-end">{{ $stats['recent_payments'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-user-graduate me-2"></i>
                                Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="{{route('bursar.reports.index')}}">
                                <i class="fas fa-chart-pie me-2"></i>
                                Financial Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-chart-bar me-2"></i>
                                Collection Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Outstanding Fees
                                <span class="badge bg-danger float-end">{{ $stats['students_with_balance'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-receipt me-2"></i>
                                Receipts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="">
                                <i class="fas fa-bullhorn me-2"></i>
                                Fee Announcements
                            </a>
                        </li>
                    </ul>
                </div>
            </div>