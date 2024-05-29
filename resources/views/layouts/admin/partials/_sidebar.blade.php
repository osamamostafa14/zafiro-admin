<div id="sidebarMain" class="d-none">
    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container text-capitalize">
            <div class="navbar-vertical-footer-offset">
                <div class="navbar-brand-wrapper justify-content-between">
                    <!-- Logo -->
     
                    <!--@php($admin=\App\Model\Admin::where('id', auth('admin')->user()->id)->first())-->
                    <!--@php($store_logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)-->
                    <!--<a class="navbar-brand" aria-label="Front">-->
                    <!--    <img class="navbar-brand-logo"-->
                    <!--         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"-->
                    <!--         src="{{asset('storage/app/public/store/'.$store_logo)}}"-->
                    <!--         alt="Logo">-->
                    <!--    <img class="navbar-brand-logo-mini"-->
                    <!--         onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"-->
                    <!--         src="{{asset('storage/app/public/store/'.$store_logo)}}" alt="Logo">-->
                    <!--</a>-->
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>
                 
                <!-- Content -->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                      
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.dashboard')}}" title="Dashboards">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{trans('messages.dashboard')}}
                                </span>
                            </a>
                        </li>
                        
                       <!-- End Dashboards -->
               
                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/business-settings/mail-config')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:"
                            >
                                <i class="tio-settings nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{trans('messages.business')}} {{trans('messages.section')}}</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/business-settings*')?'block':'none'}}">
                                
                                <li class="nav-item {{Request::is('admin/business-settings/store-setup')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.business-settings.store-setup')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">Settings</span>
                                    </a>
                                </li>
                                
                                <!--<li class="nav-item {{Request::is('admin/business-settings/mail-config')?'active':''}}">-->
                                <!--    <a class="nav-link " href="{{route('admin.business-settings.mail-config')}}">-->
                                <!--        <span class="tio-circle nav-indicator-icon"></span>-->
                                <!--        <span-->
                                <!--            class="text-truncate">{{trans('messages.mail')}} {{trans('messages.config')}}</span>-->
                                <!--    </a>-->
                                <!--</li>-->
                                
                                 <li class="nav-item {{Request::is('admin/business-settings/terms-and-conditions')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.business-settings.terms-and-conditions')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{trans('messages.terms_and_condition')}}</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <!-- End Pages -->
                        
                        
                      <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/order/')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Orders</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/order*')?'block':'none'}}">
                                
                                <li class="nav-item {{Request::is('admin/order/')?'active':''}}">
                                    <a class="nav-link " href="{{route('admin.order.add-new')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span
                                            class="text-truncate">Add new</span>
                                    </a>
                                </li>
                                 <li class="nav-item {{Request::is('admin/order/list')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.order.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">List</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <!-- End Pages -->


                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/driver/')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-car nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Drivers</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/driver*')?'block':'none'}}">
                                
                                 <li class="nav-item {{Request::is('admin/driver/list')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.driver.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">List</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <!-- End Pages -->

                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('admin/earnings/')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-money nav-icon"></i>
                                <span
                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Earnings</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('admin/earnings*')?'block':'none'}}">
                                
                                 <li class="nav-item {{Request::is('admin/earnings/list')?'active':''}}">
                                    <a class="nav-link "
                                       href="{{route('admin.earnings.list')}}">
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
