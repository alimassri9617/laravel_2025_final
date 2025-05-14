<div class="sidebar bg-dark text-white p-3">
    <div class="sidebar-header mb-4">
        <h4>DeliveryApp</h4>
        <p class="text-muted mb-0">Driver Dashboard</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'dashboard') active @endif" href="{{ route('driver.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'available-deliveries') active @endif" href="{{ route('driver.available-deliveries') }}">
                <i class="fas fa-list-alt me-2"></i> Available Deliveries
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'earnings') active @endif" href="{{ route('driver.earnings') }}">
                <i class="fas fa-money-bill-wave me-2"></i> Earnings
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'profile') active @endif" href="{{ route('driver.profile') }}">
                <i class="fas fa-user me-2"></i> Profile
            </a>
        </li>
        <li class="nav-item mt-auto">
            <a class="nav-link text-white" href="{{ route('driver.logout') }}">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>
