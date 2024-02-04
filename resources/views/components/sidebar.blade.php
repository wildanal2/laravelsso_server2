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
        @foreach ($menu as $item)
        @if (isset($item['dropdown']) && count($item['dropdown']) != 0)
        <li class="{{ $item['active'] ?? false ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="{{ $item['icon'] }}"></i></div>
                <div class="menu-title">{{ $item['text'] }}</div>
            </a>
            <ul>
                @foreach ($item['dropdown'] as $dropdown)
                <li class="{{ $dropdown['active'] ?? false ? 'mm-active' : '' }}"><a href="{{ $dropdown['url'] }}"><i class="bi bi-circle"></i>{{ $dropdown['text'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @elseif (isset($item['url']))
        <li class="{{ $item['active'] ?? false ? 'mm-active' : '' }}">
            <a href="{{ $item['url'] }}">
                <div class="parent-icon"><i class="{{ $item['icon'] }}"></i>
                </div>
                <div class="menu-title">{{ $item['text'] }}</div>
            </a>
        </li>
        @else
        <li class="menu-label">{{ $item['text'] }}</li>
        @endif
        @endforeach
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