<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <div id="sidebar-menu">
            <ul id="side-menu">
                <li>
                    <a href="#dashboard">
                        <i class="fe-airplay"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#planbilling" data-toggle="collapse">
                    &nbsp;<span class="icon-subscribe"></span>
                        <span>&nbsp;&nbsp;Plan & Billing</span>
                    </a>
                    <div class="collapse" id="planbilling">
                        <ul class="nav-second-level">
                                <li>
                                    <a href="">&nbsp;&nbsp;&nbsp;Plans</a>
                                </li>
                                <li>
                                    <a href="">&nbsp;&nbsp;&nbsp;Timeframes</a>
                                </li>
                                <li>
                                    <a href="">&nbsp;&nbsp;&nbsp;Pricing</a>
                                </li>
                                <li>
                                    <a href="">&nbsp;&nbsp;&nbsp;Client Subscriptions</a>
                                </li>
                                <li>
                                    <a href="">&nbsp;&nbsp;&nbsp;Demo Clients</a>
                                </li>
                        </ul>
                    </div>
                </li> -->
                
                <li>
                    <a href="{{route('client.index')}}">
                        <i data-feather="users"></i>
                        <span> Clients </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('language.index')}}">
                        <i data-feather="layout" class="icon-dual"></i>
                        <span> Language </span>
                    </a>
                </li>
                <li>
                    <a href="{{route('currency.index')}}">
                        <i data-feather="dollar-sign" class="icon-dual"></i>
                        <span> Currency </span>
                    </a>
                </li>
                <li>
                    <a href="#chatsocket" data-toggle="collapse">
                    &nbsp;<i data-feather="tool"></i>
                        <span>&nbsp;&nbsp;Configuration</span>
                    </a>
                    <div class="collapse" id="chatsocket">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('chatsocket')}}">&nbsp;&nbsp;&nbsp;Chat Socket</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{route('god.logout')}}">
                        <i data-feather="lock" class="icon-dual"></i>
                        <span> Logout </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->