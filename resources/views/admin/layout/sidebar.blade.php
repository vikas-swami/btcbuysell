<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" class="img-circle" src="{{asset('assets/admin/img/'.Auth::guard('admin')->user()->image)}}" alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::guard('admin')->user()->name }} </p>
            <p class="app-sidebar__user-designation">{{ Auth::guard('admin')->user()->username }}</p>
        </div>
    </div>
    <ul class="app-menu">
        <li><a class="app-menu__item @if(request()->path() == 'admin/dashboard') active @endif" href="{{route('admin.dashboard')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>



        <li class="treeview @if(request()->path() == 'admin/deposits') is-expanded
                @elseif(request()->path() == 'admin/gateway') is-expanded
                @endif ">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-btc"></i><span class="app-menu__label">Coin Gateways</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->path() == 'admin/gateway') active @endif" href="{{route('gateway')}}"><i class="icon fa fa-credit-card"></i> Payment Method</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/deposits') active @endif" href="{{route('deposits')}}"><i class="icon fa fa-file"></i> Deposit Log</a></li>

            </ul>
        </li>
        
        <li><a class="app-menu__item @if(request()->path() == 'admin/crypto') active @endif" href="{{route('crypto.index')}}"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Exchange Methods</span></a></li>

        <li><a class="app-menu__item @if(request()->path() == 'admin/currency') active @endif" href="{{route('currency.index')}}"><i class="app-menu__icon fa fa-eur"></i><span class="app-menu__label">Currency</span></a></li>

        <li class="treeview  @if(request()->path() == 'admin/users')  is-expanded
                @elseif(request()->path() == 'admin/user-banned')  is-expanded
                @elseif(request()->path() == 'admin/user/{user}')  is-expanded
                @elseif(request()->path() == 'admin/active/user')  is-expanded
                @elseif(request()->path() == 'admin/email/unverified/user')  is-expanded
                @elseif(request()->path() == 'admin/phone/unverified/user')  is-expanded
                        @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Manage User</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->path() == 'admin/users') active @endif" href="{{route('users')}}"><i class="icon fa fa-user"></i> Users</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/active/user') active @endif" href="{{route('users.active')}}"><i class="icon fa fa-check"></i>Active Users</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/email/unverified/user') active @endif" href="{{route('users.email.verified')}}"><i class="icon fa fa-envelope"></i>Email Unverified</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/phone/unverified/user') active @endif" href="{{route('users.phone.verified')}}"><i class="icon fa fa-phone"></i>Phone Unerified</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/user-banned') active @endif" href="{{route('user.ban')}}" rel="noopener"><i class="icon fa fa-ban"></i> Banned User</a></li>
            </ul>
        </li>


        <li><a class="app-menu__item @if(request()->path() == 'admin/transactions') active @endif" href="{{route('trans.log')}}"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Transaction Log</span></a></li>

        <li><a class="app-menu__item @if(request()->path() == 'admin/deals') active @endif" href="{{route('deal.log')}}"><i class="app-menu__icon fa fa-exchange"></i><span class="app-menu__label">Deal Log</span></a></li>

        @php $check_count = \App\Ticket::where('status', 1)->orWhere('status',3)->count() @endphp
        <li class="treeview @if(request()->path() == 'admin/supports') is-expanded
                                @elseif(request()->path() == 'admin/pending/ticket') is-expanded

                            @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-ambulance"></i><span class="app-menu__label">Support @if($check_count > 0)<span class="badge badge-danger">  {{$check_count}} @else  @endif </span> </span><i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->path() == 'admin/pending/ticket') active @endif" href="{{route('pending.support.ticket')}}"><i class="icon fa fa-spinner"></i> Pending &nbsp @if($check_count > 0)<span class="badge badge-danger">  {{$check_count}} @else  @endif</span></a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/supports') active @endif" href="{{route('support.admin.index')}}"><i class="icon fa fa-ticket"></i> All Tickets</a></li>

            </ul>
        </li>

        <li class="treeview @if(request()->path() == 'admin/general-settings') is-expanded
                                @elseif(request()->path() == 'admin/template') is-expanded
                                @elseif(request()->path() == 'admin/sms-api') is-expanded
                                @elseif(request()->path() == 'admin/terms/policy') is-expanded
                                @elseif(request()->path() == 'admin/contact-setting') is-expanded
                            @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cogs"></i><span class="app-menu__label">Website Control</span><i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if(request()->path() == 'admin/general-settings') active @endif" href="{{route('admin.GenSetting')}}"><i class="icon fa fa-cogs"></i> General Setting </a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/template') active @endif" href="{{route('email.template')}}"><i class="icon fa fa-envelope"></i> Email Setting</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/sms-api') active @endif" href="{{route('sms.api')}}"><i class="icon fa fa-mobile"></i> SMS Setting</a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/contact-setting') active @endif" href="{{route('contact-setting')}}"><i class="icon fa fa-phone"></i> Contact Setting </a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/terms/policy') active @endif" href="{{route('terms.policy')}}"><i class="icon fa fa-file"></i> Terms & Condition </a></li>
            </ul>
        </li>



        <li class="treeview     @if(request()->path() == 'admin/manage-logo') is-expanded
                                @elseif(request()->path() == 'admin/manage-footer') is-expanded
                                @elseif(request()->path() == 'admin/manage-social') is-expanded
                                @elseif(request()->path() == 'admin/menu-control') is-expanded
                                @elseif(request()->path() == 'admin/menu-create') is-expanded
                                @elseif(request()->path() == 'admin/manage-breadcrumb') is-expanded
                                @elseif(request()->path() == 'admin/manage-about') is-expanded
                                @elseif(request()->path() == 'admin/advertisement') is-expanded
                                @elseif(request()->path() == 'admin/faqs-create') is-expanded

                                @elseif(request()->path() == 'admin/faqs-all') is-expanded
                                @elseif(request()->path() == 'admin/advertisement/create') is-expanded
                            @endif">
            <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-desktop"></i><span class="app-menu__label">Interface Control</span><i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">

                <li><a class="treeview-item @if(request()->path() == 'admin/manage-logo') active @endif" href="{{route('manage-logo')}}"><i class="icon fa fa-photo"></i> Logo & favicon </a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/manage-footer') active @endif" href="{{route('manage-footer')}}"><i class="icon fa fa-file-text"></i> Manage Footer </a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/manage-social') active @endif" href="{{route('manage-social')}}"><i class="icon fa fa-share-alt-square"></i> Manage Social </a></li>
                <li><a class="treeview-item @if(request()->path() == 'admin/menu-control'|| request()->path() == 'admin/menu-create') active @endif" href="{{route('menu-control')}}"><i class="icon fa fa-desktop"></i> Menu Control </a></li>
            </ul>
        </li>



    </ul>
</aside>