<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    <!-- Logo -->

                    @php($store_logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard')}}" aria-label="Front">
                        <img class="navbar-brand-logo"
                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('storage/app/public/store/'.$store_logo)}}"
                             alt="Logo">
                        <img class="navbar-brand-logo-mini"
                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                             src="{{asset('storage/app/public/store/'.$store_logo)}}" alt="Logo">
                    </a>

                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content text-capitalize">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('branch')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('branch.dashboard')}}" title="Dashboards">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{trans('messages.dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->


                        <li class="nav-item">
                            <small class="nav-subtitle" title="Pages">{{trans('messages.order')}} {{trans('messages.section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                         <!-- Pages -->
                         <li class="navbar-vertical-aside-has-menu {{Request::is('branch/order/')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Orders</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('branch/order*')?'block':'none'}}">
                                
                                <li class="nav-item {{Request::is('branch/order/')?'active':''}}">
                                    <a class="nav-link " href="{{route('branch.order.add-new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">Add new</span>
                                    </a>
                                </li>
                                 <li class="nav-item {{Request::is('branch/order/list')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('branch.order.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">List</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <!-- End Pages -->


                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

<div id="sidebarCompact" class="d-none">

</div>


{{--<script>
    $(document).ready(function () {
        $('.navbar-vertical-content').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 'slow');
    });
</script>--}}
