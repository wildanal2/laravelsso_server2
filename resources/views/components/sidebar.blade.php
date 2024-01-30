<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ url('') }}/assets/theme/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Snacked</h4>
        </div>
        <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li class="{{ request()->is('home') ? 'mm-active' : '' }}">
            <a href="{{ route('home')}}">
                <div class="parent-icon"><i class="bi bi-house-fill"></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li class="menu-label">Menu</li>
        <li class="{{ request()->is('users') ? 'mm-active' : '' }}">
            <a href="{{ route('users')}}">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Users</div>
            </a>
        </li>
        <li class="{{ request()->is('roles') ? 'mm-active' : '' }}">
            <a href="{{ route('roles')}}">
                <div class="parent-icon"><i class='bx bxs-user-account'></i>
                </div>
                <div class="menu-title">Role</div>
            </a>
        </li>
        <li class="{{ request()->is('permission') ? 'mm-active' : '' }}">
            <a href="{{ route('permission')}}">
                <div class="parent-icon"><i class="bi bi-person-lines-fill"></i>
                </div>
                <div class="menu-title">Permission</div>
            </a>
        </li>
        <li class="menu-label">Others</li>
        <li>
            <a href="{{ route('module') }}">
                <div class="parent-icon"><i class='bx bx-git-branch'></i>
                </div>
                <div class="menu-title">Module</div>
            </a>
        </li>
        <li>
            <a href="javascript:;">
                <div class="parent-icon"><i class="bi bi-telephone-fill"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</aside>