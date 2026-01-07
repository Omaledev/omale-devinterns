  <div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                        <i class="fas fa-crown text-dark fa-xl"></i>
                    </div>
                    <h6 class="text-white mb-1">SuperAdmin</h6>
                    <small class="text-white-50">System Administrator</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="{{ route('superadmin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{ route('superadmin.schools.index') }}">
                            <i class="fas fa-school me-2"></i>
                            Schools
                            <span class="badge bg-primary float-end">{{ $stats['total_schools'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="{{route('superadmin.users.index')}}">
                            <i class="fas fa-users me-2"></i>
                            All Users
                            <span class="badge bg-info float-end">{{ $stats['total_users'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link text-white-50" href="{{ route('superadmin.schools.index')}}">
                        <i class="fas fa-user-shield me-2"></i>
                        Roles & Permissions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-chart-bar me-2"></i>
                            System Analytics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-cog me-2"></i>
                            System Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-file-invoice me-2"></i>
                            Billing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-bell me-2"></i>
                            Notifications
                            <span class="badge bg-danger float-end">{{ $stats['system_alerts'] ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-database me-2"></i>
                            Backup & Restore
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">
                            <i class="fas fa-life-ring me-2"></i>
                            Support
                        </a>
                    </li>
                </ul>
            </div>
        </div>