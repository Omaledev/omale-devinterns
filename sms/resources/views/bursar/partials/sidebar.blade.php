<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark text-dark sidebar collapse min-vh-100">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <div class="bg-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                style="width: 60px; height: 60px; border: 1px solid rgba(255,255,255,0.1);">
                <span class="text-white fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <h6 class="text-white mb-1">{{ auth()->user()->name }}</h6>
            <small class="text-white-50">Bursar • Finance Manager</small>
        </div>

        <ul class="nav flex-column">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- Fee Structures --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('finance.fee-structures.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('finance.fee-structures.index') }}">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Fee Structures
                </a>
            </li>

            {{-- Invoices --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('finance.invoices.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('finance.invoices.index') }}">
                    <i class="fas fa-file-invoice me-2"></i>
                    Invoices
                    <span class="badge bg-primary float-end">{{ $stats['pending_invoices'] ?? 0 }}</span>
                </a>
            </li>
            
            {{-- Receipts --}}
           <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.payments.index') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.payments.index') }}">
                    <i class="fas fa-receipt me-2"></i>
                    Receipts
                </a>
            </li>

            {{-- Payments--}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.payments.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.payments.index') }}">
                    <i class="fas fa-credit-card me-2"></i>
                    Payments
                    {{-- Only show badge if stats exist --}}
                    @if(isset($stats['recent_payments']))
                        <span class="badge bg-success float-end">{{ $stats['recent_payments'] }}</span>
                    @endif
                </a>
            </li>

            {{-- Students --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.students.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.students.index') }}">
                    <i class="fas fa-user-graduate me-2"></i>
                    Students
                </a>
            </li>
            
            {{-- Outstanding Fees --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.reports.outstanding') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.reports.outstanding') }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Outstanding Fees
                </a>
            </li>

            {{-- Announcement --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('announcements.index') }}">
                    <i class="fas fa-fw fa-bullhorn me-2"></i>
                    <span>Announcement</span>
                </a>
            </li>

            {{-- Financial Reports --}}
           <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.reports.index') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.reports.index') }}">
                    <i class="fas fa-chart-pie me-2"></i>
                    Financial Reports
                </a>
            </li>

            {{-- Collection Reports --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bursar.reports.collections') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('bursar.reports.collections') }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Collection Reports
                </a>
            </li>
        </ul>
    </div>
</nav>