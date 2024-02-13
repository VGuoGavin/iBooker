<!-- #Left Sidebar ==================== -->
<div class="sidebar">
    <div class="sidebar-inner">
        <!-- ### $Sidebar Header ### -->
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="{{ route('meetingroom') }}">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo">
                                    <img src="/static/img/logo.svg" alt="">
                                </div>
                            </div>
                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0 logo-text d-none d-lg-block">ROOMBOOKER</h5>
                                <h5 class="lh-1 mB-0 logo-text fsz-xs d-block d-lg-none">ROOMBOOKER</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href="" class="td-n">
                            <i class="ti-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ### $Sidebar Menu ### -->
        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-30">
                <a class="sidebar-link{{ $active == 'dashboard' ? ' active' : '' }}" href="{{ route('dashboard.index') }}">
                    <span class="icon-holder">
                        <i class="c-blue-500 ti-home"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class='sidebar-link{{ $active == 'rooms' ? ' active' : '' }}' href="{{ route('rooms.index')}}">
                    <span class="icon-holder">
                        <i class="c-brown-500 ti-key"></i>
                    </span>
                    <span class="title">Rooms</span>
                </a>
            </li>
            @unless (Auth::user()->role_id == roombooker\User::ROLE_AUTHORITY)
            <li class="nav-item">
                <a class='sidebar-link{{ $active == 'bookings' ? ' active' : '' }}' href="{{ route('bookings.index') }}">
                    <span class="icon-holder">
                        <i class="c-blue-500 ti-bookmark-alt"></i>
                    </span>
                    <span class="title">Bookings</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->is_authority)
            <li class="nav-item">
                <a class='sidebar-link{{ $active == 'sign' ? ' active' : '' }}' href="{{ route('make.signature') }}">
                    <span class="icon-holder">
                        <i class="c-pink-500 ti-ink-pen"></i>
                    </span>
                    <span class="title">Sign</span>
                </a>
            </li>
            @endif
            {{-- <li class="nav-item">
                <a class='sidebar-link{{ $active == 'inbox' ? ' active' : '' }}' href="calendar.html">
                    <span class="icon-holder">
                        <i class="c-deep-orange-500 ti-email"></i>
                    </span>
                    <span class="title">Inbox</span>
                </a>
            </li>
            <li class="nav-item">
                <a class='sidebar-link{{ $active == 'accounts' ? ' active' : '' }}' href="chat.html">
                    <span class="icon-holder">
                        <i class="c-deep-purple-500 ti-user"></i>
                    </span>
                    <span class="title">Accounts</span>
                </a>
            </li> --}}
            <li class="nav-item">
                <a class='sidebar-link{{ $active == 'announcements' ? ' active' : '' }}' href="{{ route('announcements.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-announcement"></i>
                    </span>
                    <span class="title">Announcements</span>
                </a>
            </li>
        </ul>
    </div>
</div>
