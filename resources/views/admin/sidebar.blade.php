<style>
    .nav-link.active {
        background-color: #495057;
    }
</style>
<div class="sidebar text-white p-3" style="min-height: 100vh; width: 250px; background: #343a40;">
    <h4>Admin Panel</h4>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'dashboard') active @endif" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'drivers') active @endif" href="{{ route('admin.drivers') }}">
                <i class="fas fa-users me-2"></i> Driver Management
            </a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link text-white @if(isset($activePage) && $activePage == 'reviews') active @endif" href="{{ route('admin.reviews') }}">
                <i class="fas fa-star me-2"></i> Driver Reviews
            </a>
        </li>
        <li class="nav-item mt-auto">
            <a class="nav-link text-white" href="{{ route('admin.logout') }}">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>
