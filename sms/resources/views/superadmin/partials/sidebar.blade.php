<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <div class="bg-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                style="width: 60px; height: 60px; border: 1px solid rgba(255,255,255,0.1);">
                <span class="text-white fw-bold fs-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <h6 class="text-white mb-1">SuperAdmin</h6>
            <small class="text-white-50">System Administrator</small>
        </div>

        <ul class="nav flex-column">
            {{-- Dashboard --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>

            {{-- Schools --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.schools.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('superadmin.schools.index') }}">
                    <i class="fas fa-school me-2"></i>
                    Schools
                </a>
            </li>

            {{-- Users --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.users.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('superadmin.users.index') }}">
                    <i class="fas fa-users me-2"></i>
                    All Users
                </a>
            </li>

            {{-- Roles --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active text-white' : 'text-white-50' }}" 
                   href="{{ route('superadmin.schools.index') }}">
                    <i class="fas fa-user-shield me-2"></i>
                    Roles & Permissions
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

            {{-- System Analytics --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-chart-bar me-2"></i>
                    System Analytics
                </a>
            </li>

            {{-- System Settings --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-cog me-2"></i>
                    System Settings
                </a>
            </li>

            {{-- Billing --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-file-invoice me-2"></i>
                    Billing
                </a>
            </li>

            {{-- Backup --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-database me-2"></i>
                    Backup & Restore
                </a>
            </li>

            {{-- Support --}}
            <li class="nav-item">
                <a class="nav-link text-white-50" href="#">
                    <i class="fas fa-life-ring me-2"></i>
                    Support
                </a>
            </li>
        </ul>
    </div>
</div>