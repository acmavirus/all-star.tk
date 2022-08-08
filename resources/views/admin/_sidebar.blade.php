<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item " aria-haspopup="true">
                <a href="<?php echo site_admin_url() ?>" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                Dashboard
                            </span>
                        </span>
                    </span>
                </a>
            </li>
            {{-- <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Quản lý nội dung
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon flaticon-users"></i>
                    <span class="m-menu__link-text">
                        Quản lý tài khoản
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý tài khoản
                                </span>
                            </span>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('group') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Nhóm quyền
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('user') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Tài khoản Admin
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!--Quản lý Media-->
            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-image"></i>
                    <span class="m-menu__link-text">
                        Quản lý đa phương tiện
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý đa phương tiện
                                </span>
                            </span>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('media') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Đa phương tiện
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--Quản lý Media--> --}}


            <!--Quản lý Danh mục-->
            <li class="m-menu__item  m-menu__item--submenu seo" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-newspaper-o"></i>
                    <span class="m-menu__link-text">
                        Quản lý Danh mục
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý Xổ số
                                </span>
                            </span>
                        </li>
		                    <li class="m-menu__item " aria-haspopup="true">
			                    <a href="<?php echo site_admin_url('category') ?>" class="m-menu__link">
				                    <i class="m-menu__link-bullet m-menu__link-bullet--dot">
					                    <span></span>
				                    </i>
				                    <span class="m-menu__link-text">
                                        Danh mục chung
                            </span>
			                    </a>
		                    </li>
                            <li class="m-menu__item " aria-haspopup="true">
                                <a href="<?php echo site_admin_url('category/post') ?>" class="m-menu__link">
                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                        <span></span>
                                    </i>
                                    <span class="m-menu__link-text">
                                        Danh mục bài viết
                                    </span>
                                </a>
                            </li>
                    </ul>
                </div>
            </li>
            <!--Quản lý Danh mục -->

            <!--Quản lý bài viết-->
            <li class="m-menu__item  m-menu__item--submenu seo" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-newspaper-o"></i>
                    <span class="m-menu__link-text">
                        Quản lý bài viết
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý bài viết
                                </span>
                            </span>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('post') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Danh sách bài viết
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('post/tag') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Danh mục thẻ tags
                                </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <!--Quản lý bài viết-->

            <!--Quản lý onPage-->
            <li class="m-menu__item  m-menu__item--submenu seo" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-newspaper-o"></i>
                    <span class="m-menu__link-text">
                        Quản lý onPage
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý onPage
                                </span>
                            </span>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('onpage') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Danh sách onPage
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--Quản lý onPage-->

            <!--Quản lý Page-->
            <li class="m-menu__item  m-menu__item--submenu seo" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-newspaper-o"></i>
                    <span class="m-menu__link-text">
                        Quản lý page
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link">
                                <span class="m-menu__link-text">
                                    Quản lý page
                                </span>
                            </span>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true">
                            <a href="<?php echo site_admin_url('category/page') ?>" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    Danh sách page
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <!--Quản lý Page-->

            <li class="m-menu__section">
                <h4 class="m-menu__section-text">
                    Cấu hình hệ thống
                </h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>
            <li class="m-menu__item">
                <a href="<?php echo site_admin_url('setting') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-settings"></i>
                    <span class="m-menu__link-text">
                        Cấu hình chung
                    </span>
                </a>
            </li>
            {{-- <li class="m-menu__item">
                <a href="<?php echo site_admin_url('menus') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-menu"></i>
                    <span class="m-menu__link-text">
                        Quản lý menu
                    </span>
                </a>
            </li> --}}
            {{-- <li class="m-menu__item">
                <a href="<?php echo site_admin_url('logs/logs_user') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-lock"></i>
                    <span class="m-menu__link-text">
                        Logs User
                    </span>
                </a>
            </li>
            <li class="m-menu__item">
                <a href="<?php echo site_admin_url('logs/logs_cms') ?>" class="m-menu__link">
                    <i class="m-menu__link-icon flaticon-lock"></i>
                    <span class="m-menu__link-text">
                        Logs CMS
                    </span>
                </a>
            </li> --}}
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>