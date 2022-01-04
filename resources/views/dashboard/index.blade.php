@extends('layouts.validate.validate')
@section('content')
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!--<img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo">-->
                            <!-- Light Logo icon -->
                            <img src="../assets/images/website-v.png" alt="homepage" class="light-logo">
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                    <span style="display: none;">
                        <!-- dark Logo text -->
                        <!--<img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo">-->
                        <!-- Light Logo text -->
                        <img src="../assets/images/validate-light.png" class="light-logo" alt="homepage">
                     </span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mt-md-0 ">
                        <!-- This is  -->
                        <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                                                href="javascript:void(0)"><i class="ti-menu"></i></a></li>
                        <li class="nav-item"><a
                                    class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark"
                                    href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-format-list-bulleted"></i>
                                <span class="selected-company-name hide-md">Upper Hutt Cossie Club</span>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown">
                                <ul>
                                    <li class="text-center search-companies">
                                        <form class="app-search">
                                            <input type="text" class="form-control" placeholder="Search for..."> <a
                                                    class="srh-btn"><i class="ti-search"></i></a>
                                        </form>
                                    </li>
                                    <li>
                                        <div class="message-center" style="overflow: hidden; width: auto; height: 250px;">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle"><i class="fa fa-building-o"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Yacht Club</h5>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle"><i class="fa fa-building-o"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Fishing Club</h5>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle"><i class="fa fa-building-o"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>RSL Club</h5>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-info btn-circle"><i
                                                            class="fa fa-building-o"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Cossie Club</h5>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ml-auto my-lg-0">
                        <li class="nav-item hidden-sm-down">
                            <form class="app-search">
                                <input type="text" class="form-control" placeholder="Search for..."> <a class="srh-btn"><i
                                            class="ti-search"></i></a>
                            </form>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown my-notifications">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href=""
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-message"></i>
                                <div class="notify"><span class="heartbit"></span> <span class="point"></span></div>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Luanch Admin</h5>
                                                <span
                                                        class="mail-desc">Just see the my new admin!</span> <span
                                                            class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Event today</h5>
                                                    <span class="mail-desc">Just a reminder that you have event</span>
                                                    <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Settings</h5>
                                                    <span class="mail-desc">You can customize this template as you want</span>
                                                    <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5>
                                                <span
                                                        class="mail-desc">Just see the my admin!</span> <span
                                                            class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all
                                                notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown my-messages">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" id="2"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-email"></i>
                                <div class="notify"><span class="heartbit"></span> <span class="point"></span></div>
                            </a>
                            <div class="dropdown-menu mailbox animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"><img src="../assets/images/users/5.jpg" alt="user"
                                                                           class="img-circle"> <span
                                                            class="profile-status online pull-right"></span></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5>
                                                <span
                                                        class="mail-desc">Just see the my admin!</span> <span
                                                            class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"><img src="../assets/images/users/2.jpg" alt="user"
                                                                           class="img-circle"> <span
                                                            class="profile-status busy pull-right"></span></div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5>
                                                <span
                                                        class="mail-desc">I've sung a song! See you at</span> <span
                                                            class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"><img src="../assets/images/users/3.jpg" alt="user"
                                                                           class="img-circle"> <span
                                                            class="profile-status away pull-right"></span></div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5>
                                                    <span class="mail-desc">I am a singer!</span> <span
                                                            class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="#">
                                                <div class="user-img"><img src="../assets/images/users/2.jpg" alt="user"
                                                                           class="img-circle"> <span
                                                            class="profile-status offline pull-right"></span></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5>
                                                <span
                                                        class="mail-desc">Just see the my admin!</span> <span
                                                            class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all
                                                e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown mega-dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href=""
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="mdi mdi-view-grid"></i></a>
                            <div class="dropdown-menu animated bounceInDown">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"><img class="d-block img-fluid"
                                                                                src="../assets/images/big/img1.jpg"
                                                                                alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid"
                                                                                src="../assets/images/big/img2.jpg"
                                                                                alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid"
                                                                                src="../assets/images/big/img3.jpg"
                                                                                alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                               data-slide="prev"> <span class="carousel-control-prev-icon"
                                                                        aria-hidden="true"></span> <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                               data-slide="next"> <span class="carousel-control-next-icon"
                                                                        aria-hidden="true"></span> <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    <li class="col-lg-3 m-b-30">
                                        <h4 class="m-b-20">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div id="accordion" class="nav-accordion" role="tablist"
                                             aria-multiselectable="true">
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <a data-toggle="collapse" data-parent="#accordion"
                                                           href="#collapseOne" aria-expanded="true"
                                                           aria-controls="collapseOne">
                                                            Collapsible Group Item #1
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseOne" class="collapse show" role="tabpanel"
                                                     aria-labelledby="headingOne">
                                                    <div class="card-block"> Anim pariatur cliche reprehenderit, enim
                                                        eiusmod high.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                           href="#collapseTwo" aria-expanded="false"
                                                           aria-controls="collapseTwo">
                                                            Collapsible Group Item #2
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse" role="tabpanel"
                                                     aria-labelledby="headingTwo">
                                                    <div class="card-block"> Anim pariatur cliche reprehenderit, enim
                                                        eiusmod high life accusamus terry richardson ad squid.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion"
                                                           href="#collapseThree" aria-expanded="false"
                                                           aria-controls="collapseThree">
                                                            Collapsible Group Item #3
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseThree" class="collapse" role="tabpanel"
                                                     aria-labelledby="headingThree">
                                                    <div class="card-block"> Anim pariatur cliche reprehenderit, enim
                                                        eiusmod high life accusamus terry richardson ad squid.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1"
                                                       placeholder="Enter Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email">
                                            </div>
                                            <div class="form-group">
                                       <textarea class="form-control" id="exampleTextarea" rows="3"
                                                 placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You
                                                    can give link</a>
                                            </li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give
                                                    link</a>
                                            </li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i>
                                                    Another Give link</a>
                                            </li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth
                                                    link</a>
                                            </li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i>
                                                    Another fifth link</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark my-profile-pic" href=""
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                        src="../assets/images/users/10.png" alt="user" class="profile-pic"/></a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img class="img-responsive"
                                                                    src="../assets/images/users/10.png" alt="user"></div>
                                            <div class="u-text m-t-20">
                                                <h4 class="">Steave Jobs</h4>
                                                <p class="text-muted">varun@gmail.com</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" data-toggle="modal" data-target="#profile-setting-modal"><i class="fa fa-user-circle m-r-10"></i> My Profile</a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-money m-r-10"></i> My Balance</a></li>
                                    <li><a href="#"><i
                                                    class="fa fa-envelope m-r-10"></i> Inbox</a></li>
                                    <li><a href="#" data-toggle="modal" data-target="#show-card-modal"><i
                                                    class="fa fa-id-card m-r-10"></i> Card</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li>

                                        <a href="#"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                        </a>
                                        <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                            @if(config('adminlte.logout_method'))
                                                {{ method_field(config('adminlte.logout_method')) }}
                                            @endif
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="aside-with-modals">
            <aside class="left-sidebar">
                <!-- Sidebar scroll-->
                <div class="scroll-sidebar">
                    <!-- User profile -->
                    <div class="user-profile">
                        <!-- User profile image -->
                        <div class="organization-img"><img class=" img-responsive" src="../assets/images/companylogo2.png"
                                                           alt="user"/></div>
                    </div>
                    <!-- End User profile text-->
                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li>
                                <a href="#"><i class="fa fa-tachometer"></i>
                                    <span class="hide-menu">Dashboard</span></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-group"></i>
                                    <span class="hide-menu">Members</span></a>

                            </li>
                            <li>
                                <a href="#"><i class="fa fa-address-book"></i>
                                    <span class="hide-menu">Contacts</span></a>

                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#virtual-cards-modal"><i
                                            class="fa fa-address-card"></i>
                                    <span class="hide-menu">Virtual Cards</span></a>

                            </li>
                            <li>
                                <a href="user-cards.html"><i
                                            class="fa fa-shopping-bag"></i>
                                    <span class="hide-menu">Cards</span></a>

                            </li>
                            <li>
                                <a href="#"><i class="fa fa-ticket"></i>
                                    <span class="hide-menu">Kiosk</span></a>

                            </li>
                            <li>
                                <a class="has-arrow " href="#" aria-expanded="false"><i
                                            class="fa fa-calendar"></i><span
                                            class="hide-menu">Events</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="#" data-toggle="modal" data-target="#add-event-modal">Add Event</a>
                                    </li>
                                    <li><a href="#">Item 2</a></li>
                                    <li><a href="#">Item 3</a></li>
                                    <li><a href="#">Item 4</a></li>
                                    <li><a href="#">Item 5</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#reports-modal"><i
                                            class="fa fa-line-chart"></i><span
                                            class="hide-menu">Reports</span></a>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><i class="fa fa-cog"></i><span
                                            class="hide-menu">Settings</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a class="pointer" title="Open Organization Setting" data-toggle="modal"
                                           data-target="#organization-detail-modal" href="#">Organization</a></li>
                                    <li><a class="pointer" title="Open Site Setting" data-toggle="modal"
                                           data-target="#site-settings-modal" href="#">Site Settings</a></li>
                                    <li><a href="#">Users</a></li>
                                    <li><a href="#">Plans</a></li>
                                    <li><a href="#">Templates</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                    <!-- End Sidebar navigation -->
                </div>
                <!-- End Sidebar scroll-->
                <!-- Bottom points-->
                <div class="sidebar-footer">
                    <!-- item-->
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-fw fa-power-off"></i>
                    </a>
                    <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                        @if(config('adminlte.logout_method'))
                            {{ method_field(config('adminlte.logout_method')) }}
                        @endif
                        {{ csrf_field() }}
                    </form>
                </div>
                <!-- End Bottom points-->
            </aside>

            <!--Organization Detail Modal-->
            <div id="organization-detail-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="organization-detail">
                <div class="modal-dialog modal-lg div-my-preloader">
                    <div class="modal-content">
                        <div class="my-preloader preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="modal-header">
                            <h4 id="organization-detail" class="modal-title">
                                Organization Details
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="user-tabs">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#org-detail" role="tab">
                                            <span class="hidden-sm-up"><i class="ti-id-badge"></i></span> <span
                                                    class="hidden-xs-down">Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#org-groups" role="tab">
                                            <span class="hidden-sm-up"><i class="fa fa-group"></i></span> <span
                                                    class="hidden-xs-down">Group</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#plan"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-light-bulb"></i>
                                </span> <span class="hidden-xs-down">Plan</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#wifi"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-signal"></i>
                                </span> <span class="hidden-xs-down">WIFI</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#org-subscription" role="tab">
                                            <span class="hidden-sm-up"><i class="ti-settings"></i></span> <span
                                                    class="hidden-xs-down">Subscription</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#logo"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="fa fa-picture-o"></i>
                                </span> <span class="hidden-xs-down">Logo</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#options"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-list"></i>
                                </span> <span class="hidden-xs-down">Options</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#office-use"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-briefcase"></i>
                                </span> <span class="hidden-xs-down">Office Use</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="org-detail" role="tabpanel">
                                        <div class="p-20">
                                            <div class="row org-details">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Organization :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Organization Name"
                                                               data-placeholder="Enter Organization Name"
                                                               class="editable editable-click inline-text">
                                                                Organization Name
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Contact Name :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Contact Name"
                                                               data-placeholder="Enter Contact Name"
                                                               class="editable editable-click inline-firstname">
                                                                Contact Name
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Contact Phone :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Contact Phone"
                                                               data-placeholder="Enter Contact Phone"
                                                               class="editable editable-click inline-lastname">
                                                                Phone Number
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Industry :</div>
                                                        <div class="col-md-7">
                                                            <a id="industry" data-type="select"
                                                               class="editable editable-click editable-open"
                                                               data-placeholder="Select Industry"
                                                               data-title="Industry" data-emptytext="Select Industry"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Account # :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Account Numnber"
                                                               data-placeholder="Enter Account Number"
                                                               class="editable editable-click inline-text">
                                                                10435
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Contact Email :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="email"
                                                               data-title="Enter Contact Email"
                                                               data-placeholder="Enter Contact Email"
                                                               class="editable editable-click inline-text">
                                                                admin@club.co.nz
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">GST # :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter GST Value"
                                                               data-placeholder="Enter GST Value"
                                                               class="editable editable-click inline-text">
                                                                123-456-123
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20">
                                            <div class="row org-postal-details">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <h4 class="text-center text-muted">Physical Address</h4>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Address 1 :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Address 1"
                                                               data-placeholder="Enter Address 1"
                                                               class="editable editable-click inline-text">
                                                                Riverside Drive
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Address 2 :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Address 2"
                                                               data-placeholder="Enter Address 2"
                                                               class="editable editable-click inline-text">
                                                                -
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Suburb :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Suburb"
                                                               data-placeholder="Enter Suburb"
                                                               class="editable editable-click inline-text">
                                                                Silver Stream
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Postal Code :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Postal Code"
                                                               data-placeholder="Enter Postal Code"
                                                               class="editable editable-click inline-text">
                                                                5018
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">City :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter City"
                                                               data-placeholder="Enter City"
                                                               class="editable editable-click inline-text">
                                                                Upper Hutt
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Region :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Region"
                                                               data-placeholder="Enter Region"
                                                               class="editable editable-click inline-text">
                                                                Wellington
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Country :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Country"
                                                               data-placeholder="Enter Country"
                                                               class="editable editable-click inline-text">
                                                                New Zealand
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Latitude :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Latitude"
                                                               data-placeholder="Enter Latitude"
                                                               data-emptytext="Enter Latitude"
                                                               class="editable editable-click inline-text">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Longitude :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Longitude"
                                                               data-placeholder="Enter Longitude"
                                                               data-emptytext="Enter Longitude"
                                                               class="editable editable-click inline-text">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="m-t-20 m-b-20 show-md">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <h4 class="text-center text-muted">
                                                                Postal Address
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Address 1 :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Address 1"
                                                               data-placeholder="Enter Address 1"
                                                               class="editable editable-click inline-text">
                                                                PO BOX 12345
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Address 2 :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Address 2"
                                                               data-placeholder="Enter Address 2"
                                                               class="editable editable-click inline-text">
                                                                -
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Suburb :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Suburb"
                                                               data-placeholder="Enter Suburb"
                                                               class="editable editable-click inline-text">
                                                                Silver Stream
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Postal Code :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Postal Code"
                                                               data-placeholder="Enter Postal Code"
                                                               class="editable editable-click inline-text">
                                                                5018
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">City :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter City"
                                                               data-placeholder="Enter City"
                                                               class="editable editable-click inline-text">
                                                                Upper Hutt
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Region :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Region ID"
                                                               data-placeholder="Enter Region ID"
                                                               class="editable editable-click inline-text">
                                                                Wellington
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Country :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Country ID"
                                                               data-placeholder="Enter Country ID"
                                                               class="editable editable-click inline-text">
                                                                New Zealand
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Starting Member # :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Starting Member"
                                                               data-placeholder="Enter Starting Member"
                                                               class="editable editable-click inline-text">
                                                                12345
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Next Member # :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Next Member"
                                                               data-placeholder="Enter Next Member"
                                                               class="editable editable-click inline-firstname">
                                                                12345
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Starting Receipt # :</div>
                                                        <div class="col-md-7">
                                                            <a data-type="text"
                                                               data-title="Enter Starting Reciept"
                                                               data-placeholder="Enter Starting Reciept"
                                                               class="editable editable-click inline-text">
                                                                10435
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="org-groups" role="tabpanel">
                                        <div class="user-tab-others">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-3 my-label multi-dd-label">Adjuncts</div>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="org-adjunct"
                                                                           type="text"
                                                                           title="Adjuncts"
                                                                           placeholder="Enter Adjuncts"/>
                                                                <span class="input-group-btn">
                                                                            <button id="org-adjuncts-add-btn"
                                                                                    class="btn btn-info" type="button">
                                                                                <i class="fa fa-plus show-480"></i>
                                                                                <span class="hide-480">Add</span>
                                                                            </button>
                                                                         </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-3 my-label multi-dd-label">Activities</div>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="org-activity"
                                                                           type="text"
                                                                           title="Activities"
                                                                           placeholder="Enter Activities"/>
                                                                <span class="input-group-btn">
                                                                            <button id="org-activities-add-btn"
                                                                                    class="btn btn-info" type="button">
                                                                                <i class="fa fa-plus show-480"></i>
                                                                                <span class="hide-480">Add</span>
                                                                            </button>
                                                                         </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20">
                                            <div class="row">

                                                <div class="col-lg-6 text-center">
                                                    <h4 class="text-muted">Adjuncts</h4>

                                                    <div class="multi-dd-list">
                                                        <ul id="org-adjuncts-list" class="list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 text-center">
                                                    <h4 class="text-muted">Activities</h4>
                                                    <div class="multi-dd-list">
                                                        <ul id="org-activities-list" class="list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="plan" role="tabpanel">
                                        <div class="org-plan-tab-content">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Current Plan :</div>
                                                        <div class="col-md-6"> Silver
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Upgrade Plan :</div>
                                                        <div class="col-md-6">
                                                            <a id="upgrade-plan" data-type="select" data-pk="1"
                                                               data-title="Select Plan" data-placeholder="Select Plan"
                                                               class="editable editable-click"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="wifi" role="tabpanel">Wifi</div>
                                    <div class="tab-pane p-20" id="org-subscription" role="tabpanel">
                                        <div class="org-subscription-tab-content">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                                        href="#org-subscriptions-inner" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-info"></i></span> <span
                                                                class="hidden-xs-down">Subscriptions</span></a></li>
                                                <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                        href="#subscriptions-options" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-th-list"></i></span> <span
                                                                class="hidden-xs-down">Options</span></a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane active" id="org-subscriptions-inner" role="tabpanel">
                                                    <div class="p-20">
                                                        <h3>Subscriptions</h3>
                                                        <div class="subscription-table">
                                                            <div class="table-responsive">
                                                                <table class="table color-table inverse-table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Title</th>
                                                                        <th>Joining Fee</th>
                                                                        <th>Annual Fee</th>
                                                                        <th>Term</th>
                                                                        <th>Active From</th>
                                                                        <th>Pro Rata</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td class="title">Standard Member</td>
                                                                        <td>$10</td>
                                                                        <td>$42.00</td>
                                                                        <td>12 Months</td>
                                                                        <td>Join Date</td>
                                                                        <td>Yes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td class="title">Life Member</td>
                                                                        <td>-</td>
                                                                        <td>-</td>
                                                                        <td>12 Months</td>
                                                                        <td>Join Date</td>
                                                                        <td>-</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>3</td>
                                                                        <td class="title">Senior</td>
                                                                        <td>-</td>
                                                                        <td>$12.00</td>
                                                                        <td>12 Months</td>
                                                                        <td>Join Date</td>
                                                                        <td>Yes</td>
                                                                    </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <a id="add-subscription-btn" class="btn btn-info">
                                                                <i class="fa fa-plus"></i> Add Subscription
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane p-20" id="subscriptions-options" role="tabpanel">
                                                    <div class="subscriptions-options-tab-content">

                                                        <div class="subscription-options">
                                                            <p>Subscription delay as due days
                                                                <a data-type="number"
                                                                   data-title="Delay Days"
                                                                   data-placeholder="Delay"
                                                                   class="editable editable-click inline-text">
                                                                    10
                                                                </a> days prior to expiring.
                                                            </p>
                                                            <p>Subscription will display as overdue when there are
                                                                <a data-type="number"
                                                                   data-title="Overdue  Days"
                                                                   data-placeholder="Overdue"
                                                                   class="editable editable-click inline-text">
                                                                    1
                                                                </a> day past the due date.
                                                            </p>
                                                            <p>Subscription will expire when they are
                                                                <a data-type="number"
                                                                   data-title="Expire"
                                                                   data-placeholder="Expire"
                                                                   class="editable editable-click inline-text">
                                                                    30
                                                                </a> day overdue.
                                                            </p>
                                                        </div>
                                                        <div class="subscription-note">
                                                            <h4>Note:</h4>
                                                            <p>
                                                                New members join date is the date first payment is
                                                                made. Payment
                                                                must be in full before membership is activated
                                                                unless prior
                                                                arrangements have been made with management.
                                                                <br/> Renewing members regardless of early or late
                                                                payment, the team
                                                                is added to anniversary date.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="tab-pane p-20" id="logo" role="tabpanel">
                                        <div class="organization-logo-wrapper">
                                            <div class="organization-logo">
                                                <img src="../assets/images/companylogo2.png" width="300" height="300"
                                                     alt="Logo"/>

                                                <div class="button-overlay">
                                                    <button id="upload-logo-btn" type="button"
                                                            class="btn btn-success btn-rounded">
                                                        <i class="fa fa-picture-o"></i>
                                                    </button>
                                                </div>

                                                <input class="hide" id="upload-logo" type="file"
                                                       title="Upload Logo"
                                                       accept="image/*"/>
                                            </div>
                                            <div class="text-center m-t-15">
                                                <button id="save-logo" type="button" class="btn btn-info btn-rounded">
                                                    <i class="fa fa-floppy-o"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="options" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 single-toggle">
                                                <p> Adjuncts
                                                </p>
                                                <input title="toggle" id="adjuncts-toggle" type="checkbox" checked
                                                       data-toggle="toggle"
                                                       data-onstyle="info" data-offstyle="warning" data-style="ios">
                                            </div>
                                            <div class="col-lg-4 col-md-6 single-toggle">
                                                <p>Activities:</p>
                                                <input title="toggle" id="activities-toggle" type="checkbox" checked
                                                       data-toggle="toggle"
                                                       data-onstyle="info" data-offstyle="warning" data-style="ios">


                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 single-toggle">
                                                <p>Interest:</p>
                                                <input title="toggle" id="interest-toggle" type="checkbox" checked
                                                       data-toggle="toggle"
                                                       data-onstyle="info" data-offstyle="warning" data-style="ios">


                                            </div>
                                            <div class="col-lg-4 col-md-6 single-toggle">
                                                <p>RSA:</p>
                                                <input title="toggle" id="rsa-toggle" type="checkbox" checked
                                                       data-toggle="toggle"
                                                       data-onstyle="info" data-offstyle="warning" data-style="ios">


                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="office-use" role="tabpanel">
                                        <div class="toggles-wrapper">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>SMS:</p>
                                                    <input title="toggle" id="sms-toggle" type="checkbox" checked
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">
                                                </div>
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>Virtual Cards:</p>
                                                    <input title="toggle" id="virtual-card-toggle" type="checkbox" checked
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">


                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>Email:</p>
                                                    <input title="toggle" id="email-toggle" type="checkbox" checked
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">


                                                </div>
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>Advertising:</p>
                                                    <input title="toggle" id="advertising-toggle" type="checkbox" checked
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">


                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>Gateway:</p>
                                                    <input title="toggle" id="gateway-toggle" type="checkbox" checked
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">


                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>Kiosk:</p>
                                                    <input title="toggle" id="kiosk-toggle" type="checkbox"
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-lg-4 single-toggle">
                                                    <p>POS:</p>
                                                    <input title="toggle" id="pos-toggle" type="checkbox"
                                                           data-toggle="toggle"
                                                           data-onstyle="info" data-offstyle="warning" data-style="ios">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Virtual Cards Modal-->
            <div id="virtual-cards-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="virtual-card-options">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="virtual-card-options" class="modal-title">
                                Virtual Cards
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="user-tabs">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#cards" role="tab">
                                            <span class="hidden-sm-up"><i class="ti-id-badge"></i></span> <span
                                                    class="hidden-xs-down">Cards</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#setup"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-light-bulb"></i>
                                </span> <span class="hidden-xs-down">Setup</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane p-20 active" id="cards" role="tabpanel">
                                        <div class="virtual-card-list-wrapper">
                                            <div class="row">
                                                <button type="button" class="btn btn-info btn-rounded"
                                                        id="add-virtual-cards"><i
                                                            class="fa fa-plus"></i> Add
                                                </button>
                                            </div>
                                            <div class="table-responsive table-hover">
                                                <table class="table color-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Card ID</th>
                                                        <th>Card Title</th>
                                                        <th>Reward</th>
                                                        <th class="text-center">Options</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>9H6G2YY5</td>
                                                        <td>Coffee Card</td>
                                                        <td>Buy 10 Get 1 Free</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-info btn-circle"><i
                                                                        class="fa fa-eye"></i></button>
                                                            <button type="button" class="btn btn-primary btn-circle"><i
                                                                        class="fa fa-pencil"></i></button>
                                                            <button type="button" class="btn btn-danger btn-circle"><i
                                                                        class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>F6HH672F</td>
                                                        <td>Beer Card</td>
                                                        <td>Buy 8 Get 1 Free</td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-info btn-circle"><i
                                                                        class="fa fa-eye"></i></button>
                                                            <button type="button" class="btn btn-primary btn-circle"><i
                                                                        class="fa fa-pencil"></i></button>
                                                            <button type="button" class="btn btn-danger btn-circle"><i
                                                                        class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="setup" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-4 my-label">Stamp ID :</div>
                                                    <div class="col-md-8">
                                                    <textarea title="Stamp ID" class="form-control" name="stamp-id-text"
                                                              rows="6">1aads535asdf513d512as&#13;&#10;25aed254tg2h5etejn2b2</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-4 my-label">Stamp Key:</div>
                                                    <div class="col-md-8">
                                                        <p>
                                                            1aads535asdf513d512as
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Add Virtual Cards Modal-->
            <div id="add-virtual-cards-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="add-new-virtual-cards">
                <div class="modal-dialog modal-lg div-my-preloader">
                    <div class="modal-content">
                        <div class="my-preloader preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="modal-header">
                            <h4 id="add-new-virtual-cards" class="modal-title">
                                Add Cards
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="user-tabs">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#card-details" role="tab">
                                            <span class="hidden-sm-up"><i class="ti-id-badge"></i></span> <span
                                                    class="hidden-xs-down">Card Details</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#card-design"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-light-bulb"></i>
                                </span> <span class="hidden-xs-down">Card Design</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane p-20 active" id="card-details" role="tabpanel">
                                        <div class="card-details-wrapper">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-4 my-label">Card Title :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text" data-title="Card Name"
                                                               data-placeholder="Enter Card Name"
                                                               data-emptyText="Enter Card Name"
                                                               class="editable editable-click inline-text">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-4 my-label">Remaining</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text" data-title="Card Remaining"
                                                               data-placeholder="Enter Quantity"
                                                               data-emptyText="Enter Quantity"
                                                               class="editable editable-click inline-text">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive table-hover m-t-20">
                                                <table id="rewards-table"
                                                       class="table color-table color-bordered-table color-table inverse-bordered-table">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Reward</th>
                                                        <th>Stamps</th>
                                                        <th>Message</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr id="reward-entry">
                                                        <td class="text-center">#</td>
                                                        <td>
                                                            <input id="reward-name" class="form-control" name="reward-name"
                                                                   placeholder="Reward Name" type="text"
                                                                   title="Reward Name"/>
                                                        </td>
                                                        <td>
                                                            <input id="stamps" class="form-control" name="stamps"
                                                                   placeholder="Enter Required Stamps" type="number"
                                                                   title="Stamps"/>
                                                        </td>
                                                        <td>
                                                            <input id="reward-message" class="form-control" name="message"
                                                                   placeholder="Enter Required Message" type="text"
                                                                   title="Message"/>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-right p-r-20 m-t-20">
                                                <div class="inline-block">
                                                    <button type="button" class="btn btn-info btn-rounded" id="add-reward">
                                                        <i class="fa fa-plus"></i> Add
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-rounded"
                                                            id="save-reward"><i class="fa fa-floppy-o"></i> Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-20" id="card-design" role="tabpanel">
                                        <div class="center-block">
                                            <div class="virtual-card-design">
                                                <div class="div-image">
                                                    <img src="{{asset('assets/images/background/virtual-card-bg2.png')}}"
                                                         alt="Card Background" class="center-block"/>
                                                </div>
                                                <div class="text-center m-t-30">
                                                    <button id="upload-card-design" type="button"
                                                            class="btn btn-success btn-rounded"><i
                                                                class="fa fa-upload"></i> Upload
                                                    </button>
                                                    <input class="hide" id="card-design-btn" type="file"
                                                           title="Upload Image" accept="image/*"/>
                                                    <button type="button" class="btn btn-info btn-rounded"><i
                                                                class="fa fa-save"></i> Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Adding Subscription Modal-->
            <div id="add-subscription-modal" class="modal fade" role="dialog" aria-labelledby="add-subscription">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="add-subscription" class="modal-title">Add Subscription</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="add-subscription-form" class="form-material">
                                <div class="form-group m-t-10 row">
                                    <label for="subscription-title" class="col-lg-5 col-md-12 col-form-label">Subscription
                                        Title
                                        :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <input class="form-control" type="text" placeholder="Enter Title"
                                               id="subscription-title"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="joining-fee" class="col-lg-5 col-md-12 col-form-label">Joining Fee ($)
                                        :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <input class="form-control" type="number" placeholder="Enter Joining Fee"
                                               id="joining-fee" value="10"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fee" class="col-lg-5 col-md-12 col-form-label">Fee ($):</label>
                                    <div class="col-lg-7 col-md-12">
                                        <input class="form-control" type="number" placeholder="Enter Fee"
                                               id="fee" value="30"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fee" class="col-lg-5 col-md-12 col-form-label">Active From :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <div class="radio-list">
                                            <label class="custom-control custom-radio">
                                                <input id="join-date" name="radio" type="radio" checked
                                                       class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Join Date</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input id="specific-date" name="radio" type="radio"
                                                       class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Specific Date</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pro-rata" class="col-lg-5 col-md-12 col-form-label">Pro Rata :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <select id="pro-rata" class="form-control">
                                            <option>Yes</option>
                                            <option>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="allow-payment" class="col-lg-5 col-md-12 col-form-label">Allow Part Payment
                                        :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <select id="allow-payment" class="form-control">
                                            <option>Yes</option>
                                            <option>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="frequency" class="col-lg-5 col-md-12 col-form-label">Frequency :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <select id="frequency" class="form-control">
                                            <option>Monthly</option>
                                            <option>Anually</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="frequency-amount" class="col-lg-5 col-md-12 col-form-label">Amount ($)
                                        :</label>
                                    <div class="col-lg-7 col-md-12">
                                        <input class="form-control" type="number" placeholder="Enter Frequency Amount"
                                               id="frequency-amount" value="9.00"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info waves-effect text-left"
                                    id="sumbit-subscription-form"><i
                                        class="fa fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!--Update Subscription Modal-->
            <div id="update-subscription-modal" class="modal fade" role="dialog" aria-labelledby="update-subscription">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="update-subscription" class="modal-title">Update Subscription</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="update-subscription-form">
                                <div class="row">
                                    <label class="col-md-5 my-label">Current Subscription
                                        :</label>
                                    <div class="col-md-7">
                                        <a data-type="text"
                                           data-title="Enter Current Subscription"
                                           data-placeholder="Current Subscription"
                                           class="editable editable-click inline-text">
                                            Standard Member
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-5 my-label">Change To :</label>
                                    <div class="col-md-7">
                                        <a data-type="select" data-pk="1"
                                           data-title="Select Option"
                                           class="editable editable-click"
                                           id="change-to"></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-5 my-label">Date Effective</label>
                                    <div class="col-md-7">
                                        <a data-type="select" data-pk="1"
                                           data-title="Select Option"
                                           class="editable editable-click"
                                           id="date-effective"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!--Reason for Current Status (Suspended / On Hold)-->
            <div id="current-status-reason-modal" class="modal fade" role="dialog"
                 aria-labelledby="status-reason">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="status-reason" class="modal-title">Write Reason</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="current-status-reason-content" class="full-x-editable">
                                <a id="reason" data-type="textarea" data-pk="1"
                                   data-placeholder="Write your reason here" data-title="Enter reason"
                                   class="editable editable-click" data-original-title=""
                                   title=""></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!--Adding Event Modal-->
            <div id="add-event-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="add-event">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="add-event" class="modal-title text-center">
                                Add New Event
                            </h4>
                            <div class="col-sm-4 detail-modal-btns text-right">
                                <button type="button" class="btn btn-danger btn-circle btn-sm" title="Close"
                                        data-dismiss="modal" aria-hidden="true">
                                    <i class="fa fa-close"> </i>
                                </button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-4 my-label">Event Name :
                                        </div>
                                        <div class="col-md-8">
                                            <a data-type="text"
                                               data-title="Enter Event Name"
                                               data-placeholder="Enter Event Name"
                                               class="editable editable-click inline-text">
                                                Darts Tournament
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 my-label">Start Date Time :
                                        </div>
                                        <div class="col-md-8 my-datetime-picker">
                                            <a class="event-date-time editable editable-click editable-empty"
                                               data-type="combodate"
                                               data-template="D MMM YYYY  HH:mm" data-format="YYYY-MM-DD HH:mm"
                                               data-viewformat="DD / MM / YY, HH:mm" data-pk="1"
                                               data-title="Set event date and time">
                                                Select Date Time</a>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 my-label">End Date Time :
                                        </div>
                                        <div class="col-md-8 my-datetime-picker">
                                            <a class="event-date-time editable editable-click editable-empty"
                                               data-type="combodate"
                                               data-template="D MMM YYYY  HH:mm"
                                               data-format="YYYY-MM-DD HH:mm"
                                               data-viewformat="MMM D, YYYY, HH:mm"
                                               data-title="Set event date and time"
                                               title="">Select Date Time</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-4 my-label">Attendees :
                                        </div>
                                        <div class="col-md-8">
                                            <a id="attendees" data-type="select" data-pk="1"
                                               data-title="Select Attendees"
                                               class="editable editable-click"
                                               data-original-title="" title=""></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 my-label">Tickets Qty :</div>
                                        <div class="col-md-8">
                                            <a data-type="text"
                                               data-title="Enter Tickets Quantity"
                                               data-placeholder="Enter Tickets Quantity"
                                               class="editable editable-click inline-text">
                                                1122
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">
                            <div class="row event-contact-details">
                                <h4 class="text-muted"> Contact Details</h4>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-4 my-label">Contact Name :
                                        </div>
                                        <div class="col-md-8">
                                            <a data-type="text"
                                               data-title="Enter Contact Name"
                                               data-placeholder="Enter Contact Name"
                                               class="editable editable-click inline-text">
                                                John Smith
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 my-label">Contact Email :
                                        </div>
                                        <div class="col-md-8">
                                            <a data-type="email"
                                               data-title="Enter Email"
                                               data-placeholder="Enter Email"
                                               class="editable editable-click inline-text">
                                                johnsmith@events.com
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-4 my-label">Contact Number :
                                        </div>
                                        <div class="col-md-8">
                                            <a data-type="text"
                                               data-title="Enter Contact Number"
                                               data-placeholder="Enter Contact Number"
                                               class="editable editable-click inline-firstname">
                                                +62 123 451 234
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">
                            <div class="row event-contact-details">
                                <h4 class="text-muted"> Event Venue</h4>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-4 my-label">Location :
                                        </div>
                                        <div class="col-md-8">
                                            <a data-type="text"
                                               data-title="Enter Location Name"
                                               data-placeholder="Enter Location Name"
                                               class="editable editable-click inline-text">
                                                Upper Hutt Cossie Club
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-add-member-form">
                                <button type="button" class="btn btn-info waves-effect text-left"
                                        id="add-new-event"><i
                                            class="fa fa-plus"></i> <span class="hide-480">Add Event</span>
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Adding Member Modal-->
            <div id="add-new-member" class="modal fade" role="dialog" aria-labelledby="add-member">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="add-member" class="modal-title">Add New Member</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form-material" id="add-member-form">
                                <div class="form-group m-t-20 row">
                                    <label for="first-name" class="col-sm-3 col-form-label">First Name <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input class="form-control required" type="text" placeholder="Enter First Name"
                                               id="first-name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="last-name" class="col-sm-3 col-form-label">Last Name <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input class="form-control required" type="text" placeholder="Enter Last Name"
                                               id="last-name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date Of Birth</label>
                                    <div class="col-sm-9">
                                        <a id="dob-picker"
                                           class="editable editable-click inline-text"
                                           data-type="combodate"
                                           data-value="24/09/2014" data-format="DD/MM/YYYY"
                                           data-viewformat="DD/MM/YYYY"
                                           data-template="DD MM YYYY"
                                           data-title="Select Date of birth"></a>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact-mobile" class="col-sm-3 col-form-label">Contact
                                        Mobile</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text"
                                               placeholder="Enter Mobile Number" data-mask="99 999 999 999"
                                               id="contact-mobile">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-add-member-form">
                                <button type="button" class="btn btn-info waves-effect text-left"
                                        id="submit-new-member"><i
                                            class="fa fa-plus"></i> <span class="hide-480">Add Member</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--User Detail Modal -->
            <div id="user-detail-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="member-detail-modal">
                <div class="modal-dialog modal-lg div-my-preloader">
                    <div class="modal-content">
                        <div class="my-preloader preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="modal-header">
                            <h4 id="member-detail-modal" class="modal-title col-lg-4 text-muted hide-md">
                                Member Details
                            </h4>
                            <h4 class="modal-title col-lg-4 col-md-6">
                                <span class="hide-480">Name :</span> John Smith
                            </h4>
                            <div class="col-lg-4 col-md-6">
                                <h4 class="modal-title age">
                                    <span class="hide-480">Age :</span> 35
                                </h4>

                                <div class="pull-right detail-modal-btns inline-block">
                                    <button type="button" class="btn btn-primary btn-circle btn-sm" title="Print"><i
                                                class="fa fa-print"></i></button>
                                    <button type="button" class="btn btn-info btn-circle btn-sm" title="Previous Member"><i
                                                class="fa fa-angle-left"></i></button>
                                    <button type="button" class="btn btn-info btn-circle btn-sm" title="Next Member"><i
                                                class="fa fa-angle-right"></i></button>
                                    <button type="button" class="btn btn-danger btn-circle btn-sm" title="Close"
                                            data-dismiss="modal" aria-hidden="true">
                                        <i class="fa fa-close"> </i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row static-detail-fields ">
                                <div class="col-lg-4">
                                    <!--<div class="row validate-id">-->
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <h5 class="text-muted">Validate ID: </h5>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <h5 class="text-muted">ABC1234</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Subscription: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p>Standard Member</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Status: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="status">Active</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Renewal: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="renewal">15.07.2018</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Financial: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="financial">Yes</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Duration: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="status">3 Yrs 3 Months</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Created: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="created">01.01.2018</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Updated: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p><span class="updated">01.01.2018</span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-6">
                                            <p>Last Login: </p>
                                        </div>
                                        <div class="col-lg-8 col-6">
                                            <p>
                                                <span class="login-date">01.01.2018</span>
                                                <span class="login-time">20:50</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="user-tabs">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#detail" role="tab">
                                            <span class="hidden-sm-up"><i class="ti-list"></i></span> <span
                                                    class="hidden-xs-down">Details</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#other"
                                                            role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-panel"></i></span> <span class="hidden-xs-down">Other</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#groups" role="tab">
                                            <span class="hidden-sm-up"><i class="fa fa-users"></i></span> <span
                                                    class="hidden-xs-down">Groups</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#subscription"
                                                            role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-credit-card"></i></span> <span
                                                    class="hidden-xs-down">Subscription</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#events"
                                                            role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-calendar"></i></span> <span
                                                    class="hidden-xs-down">Events</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#comms"
                                                            role="tab"><span
                                                    class="hidden-sm-up"><i class="ti-comments"></i></span> <span
                                                    class="hidden-xs-down">Comms</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#notes"
                                                            role="tab"><span
                                                    class="hidden-sm-up"><i class="fa fa-sticky-note"></i></span> <span
                                                    class="hidden-xs-down">Notes</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#identity"
                                                            role="tab"><span
                                                    class="hidden-sm-up"><i class="fa fa-id-card"></i></span> <span
                                                    class="hidden-xs-down">Identity</span></a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logs"
                                                            role="tab"><span class="hidden-sm-up"><i
                                                        class="ti-receipt"></i></span> <span class="hidden-xs-down">Logs</span></a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane p-t-20 p-b-20 active" id="detail" role="tabpanel">
                                        <div class="row user-details">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Member Name :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Member Name"
                                                           data-placeholder="Enter Member Name"
                                                           class="editable editable-click inline-text">
                                                            John Smith
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">First Name :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter First Name"
                                                           data-placeholder="Enter First Name"
                                                           class="editable editable-click inline-firstname">
                                                            John
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Last Name :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Last Name"
                                                           data-placeholder="Enter Last Name"
                                                           class="editable editable-click inline-lastname">
                                                            Smith
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Title :</div>
                                                    <div class="col-md-6">
                                                        <a id="title" data-type="select" data-pk="1"
                                                           class="editable editable-click"
                                                           data-original-title="" title=""></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Known As :</div>
                                                    <div class="col-md-6">
                                                        <a id="known-as" data-type="text"
                                                           data-title="Known As"
                                                           data-placeholder="Enter Known As"
                                                           class="editable editable-click inline-text">John</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Contact Phone :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Contact Phone"
                                                           data-placeholder="Enter Contact Phone"
                                                           class="editable editable-click inline-text">
                                                            +62 123 456 789
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Contact Mobile :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Contact Mobile"
                                                           data-placeholder="Enter Contact Mobile"
                                                           class="editable editable-click inline-text">
                                                            +62 123 456 789
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Member ID :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Member Phone ID"
                                                           data-placeholder="Enter Phone ID"
                                                           class="editable editable-click inline-text">
                                                            1122
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Parent Code :</div>
                                                    <div class="col-md-6">
                                                        <a id="parent-code" data-type="text"
                                                           data-title="Parent Code"
                                                           data-placeholder="Enter Parent Code"
                                                           class="editable editable-click inline-text">2244</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Email :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="email"
                                                           data-title="Enter Email"
                                                           data-placeholder="Enter Email"
                                                           class="editable editable-click inline-text">
                                                            johnsmith@membership.com
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Facebook ID :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Facebook ID"
                                                           data-placeholder="Enter Facebook ID"
                                                           class="editable editable-click inline-text">
                                                            123456123465
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Gender :</div>
                                                    <div class="col-md-6">
                                                        <a id="gender" data-type="select" data-pk="1"
                                                           class="editable editable-click"
                                                           data-original-title="" title=""></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Date of Birth :</div>
                                                    <div class="col-md-6">
                                                        <a
                                                                class="editable editable-click inline-text"
                                                                data-type="combodate"
                                                                data-value="24/09/2014" data-format="DD/MM/YYYY"
                                                                data-viewformat="DD/MM/YYYY"
                                                                data-template="D MMM YYYY" data-pk="1"
                                                                data-title="Select Date of birth"></a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Password :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Password"
                                                           data-placeholder="Enter Password"
                                                           class="editable editable-click inline-text">
                                                            ************
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="m-t-20 m-b-20">
                                        <div class="row user-postal-details">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-12">
                                                        <h4 class="text-center text-muted">Physical Address</h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Address 1 :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Address 1"
                                                           data-placeholder="Enter Address 1"
                                                           class="editable editable-click inline-text">
                                                            Riverside Drive
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Address 2 :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Address 2"
                                                           data-placeholder="Enter Address 2"
                                                           class="editable editable-click inline-text">
                                                            -
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Suburb :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Suburb"
                                                           data-placeholder="Enter Suburb"
                                                           class="editable editable-click inline-text">
                                                            Silver Stream
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Postal Code :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Postal Code"
                                                           data-placeholder="Enter Postal Code"
                                                           class="editable editable-click inline-text">
                                                            5018
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">City :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter City"
                                                           data-placeholder="Enter City"
                                                           class="editable editable-click inline-text">
                                                            Upper Hutt </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Region :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Region"
                                                           data-placeholder="Enter Region"
                                                           class="editable editable-click inline-text">
                                                            Wellington </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Country :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Country"
                                                           data-placeholder="Enter Country"
                                                           class="editable editable-click inline-text">
                                                            New Zealand </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20 show-md">
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-10 col-md-12">
                                                        <h4 class="text-center text-muted">Postal Address
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Address 1 :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Address 1"
                                                           data-placeholder="Enter Address 1"
                                                           class="editable editable-click inline-text">
                                                            PO BOX 12345 </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Address 2 :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Address 2"
                                                           data-placeholder="Enter Address 2"
                                                           class="editable editable-click inline-text">
                                                            -
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Suburb :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Suburb"
                                                           data-placeholder="Enter Suburb"
                                                           class="editable editable-click inline-text">
                                                            Silver Stream </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Postal Code :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Postal Code"
                                                           data-placeholder="Enter Postal Code"
                                                           class="editable editable-click inline-text">
                                                            5018 </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">City :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter City"
                                                           data-placeholder="Enter City"
                                                           class="editable editable-click inline-text">
                                                            Upper Hutt </a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Region :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Region ID"
                                                           data-placeholder="Enter Region ID"
                                                           class="editable editable-click inline-text">
                                                            Wellington</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-5 my-label">Country :</div>
                                                    <div class="col-md-6">
                                                        <a data-type="text"
                                                           data-title="Enter Country ID"
                                                           data-placeholder="Enter Country ID"
                                                           class="editable editable-click inline-text">
                                                            New Zealand
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="other" role="tabpanel">
                                        <div class="user-tab-others">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Recieve Email :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="select" data-pk="1"
                                                               data-title="Select Option"
                                                               class="editable editable-click yesno"
                                                               data-original-title="" title=""></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Transferred From :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Transferred From"
                                                               data-placeholder="Enter Transferred From"
                                                               class="editable editable-click inline-text">
                                                                Johnsville Club
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Recieve SMS :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="select" data-pk="1"
                                                               data-title="Select Option"
                                                               class="editable editable-click yesno"
                                                               data-original-title="" title=""></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Newsletter :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="select" data-pk="1"
                                                               data-title="Select Option"
                                                               class="editable editable-click yesno"
                                                               data-original-title="" title=""></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Proposer :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Proposer"
                                                               data-placeholder="Enter Proposer"
                                                               class="editable editable-click inline-text">
                                                                5447
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Proposer Name :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Proposer Name"
                                                               data-placeholder="Enter Proposer Name"
                                                               class="editable editable-click inline-text">
                                                                Johnsville Club
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Secondary :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Secondary"
                                                               data-placeholder="Enter Secondary"
                                                               class="editable editable-click inline-text">
                                                                5447
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Secondary Name :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Secondary Name"
                                                               data-placeholder="Enter Secondary Name"
                                                               class="editable editable-click inline-text">
                                                                Johnsville Club
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">RSA Type :</div>
                                                        <div class="col-md-6">
                                                            <a id="rsaType" data-type="select" data-pk="1"
                                                               data-title="Select Type"
                                                               class="editable editable-click"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">RSA Served :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter RSA Served"
                                                               data-placeholder="Enter RSA Served"
                                                               class="editable editable-click inline-text">
                                                                NZ Army
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Company :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter Company"
                                                               data-placeholder="Enter Company"
                                                               class="editable editable-click inline-text">
                                                                501
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Swipe Card:</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Swipe Card"
                                                               data-placeholder="Enter Swipe Card"
                                                               data-emptytext="Enter Swipe Card"
                                                               class="editable editable-click inline-text">
                                                                123456
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Prox Card:</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Prox Card"
                                                               data-placeholder="Enter Prox Card"
                                                               data-emptytext="Enter Prox Card"
                                                               class="editable editable-click inline-text">
                                                                3457634564
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="groups" role="tabpanel">
                                        <div class="user-tab-others">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="row">
                                                        <div class="col-md-3 my-label multi-dd-label">Adjuncts</div>
                                                        <div class="col-md-9">
                                                            <select id="adjuncts-picker" class="selectpicker" multiple
                                                                    data-style="form-control btn-secondary"
                                                                    title="Adjuncts">
                                                                <option>Aces High</option>
                                                                <option>Indoor Bowls</option>
                                                                <option>Snooker</option>
                                                                <option>Computer</option>
                                                                <option>Karaoke</option>
                                                                <option>Social</option>
                                                                <option>Dance</option>
                                                                <option>Craft Beer</option>
                                                                <option>Mah Jong</option>
                                                                <option>Sport of Kings</option>
                                                                <option>Cricket</option>
                                                                <option>Outdoorbowls</option>
                                                                <option>Theatre & Events</option>
                                                                <option>Darts</option>
                                                                <option>Pool</option>
                                                                <option>Travel</option>
                                                                <option>Fishing</option>
                                                                <option>Seniors</option>
                                                                <option>Wine Club</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="row">
                                                        <div class="col-md-3 my-label multi-dd-label">Activities</div>
                                                        <div class="col-md-9">
                                                            <select id="activities-picker" class="selectpicker" multiple
                                                                    data-style="form-control btn-secondary"
                                                                    title="Activities">
                                                                <option>House</option>
                                                                <option>Table Tennis</option>
                                                                <option>Upper Hutt RSA</option>
                                                                <option>Scrabble</option>
                                                                <option>Trivia Quiz</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="row">
                                                        <div class="col-md-3 my-label multi-dd-label">Interest</div>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="interest" type="text"
                                                                           title="Interest"
                                                                           placeholder="Enter Interests"/>
                                                                <span class="input-group-btn">
                                                                            <button id="add-interest"
                                                                                    class="btn btn-info" type="button">
                                                                                <i class="fa fa-plus show-480"></i>
                                                                                <span class="hide-480">Add</span>
                                                                            </button>
                                                                         </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="m-t-20 m-b-20">
                                            <div class="row">

                                                <div class="col-lg-4 text-center">
                                                    <h4 class="text-muted">Adjuncts</h4>

                                                    <div class="multi-dd-list">
                                                        <ul id="adjuncts-list" class="list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 text-center">
                                                    <h4 class="text-muted">Activities</h4>
                                                    <div class="multi-dd-list">
                                                        <ul id="activities-list" class="list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 text-center">
                                                    <h4 class="text-muted">Interests</h4>
                                                    <div class="multi-dd-list">
                                                        <ul id="interest-list" class="list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="subscription" role="tabpanel">
                                        <div class="subscription-tab-content">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                                        href="#subscription-details" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-info"></i></span> <span
                                                                class="hidden-xs-down">Subscription Details</span></a></li>
                                                <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                        href="#payments" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-credit-card"></i></span>
                                                <span
                                                        class="hidden-xs-down">Payments</span></a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane active" id="subscription-details" role="tabpanel">
                                                    <div class="p-20">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="row">
                                                                    <div class="col-md-6 my-label">
                                                                        <p>Subscription: </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        Standard Member
                                                                    </div>
                                                                </div>
                                                                <a id="update-subscription-btn" class="btn btn-info">
                                                                    <i class="fa fa-pencil"></i> Update Subscription
                                                                </a>

                                                                <div class="row m-t-20">
                                                                    <div class="col-md-6 my-label">
                                                                        <p>Current Status: </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <a id="current-status" data-type="select"
                                                                           data-pk="1"
                                                                           data-title="Select Status"
                                                                           data-placeholder="Select Status"
                                                                           class="editable editable-click"></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane p-20" id="payments" role="tabpanel">
                                                    <div class="payments-tab-content">
                                                        <h4 class="text-center text-muted">Payment Details</h4>
                                                        <div class="table-responsive">
                                                            <table class="table color-table">
                                                                <thead>
                                                                <tr>
                                                                    <th class="date-column">Date</th>
                                                                    <th>Invoice #</th>
                                                                    <th>Amount</th>
                                                                    <th>Type</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>10/08/17</td>
                                                                    <td><a href="#" class="show-invoice">12345</a></td>
                                                                    <td>$50</td>
                                                                    <td>Subscription</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>01/08/17</td>
                                                                    <td><a href="#" class="show-invoice">35214</a></td>
                                                                    <td>$100</td>
                                                                    <td>Event</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="events" role="tabpanel">Events</div>
                                    <div class="tab-pane p-t-20 p-b-20" id="comms" role="tabpanel">Comms</div>
                                    <div class="tab-pane p-t-20 p-b-20" id="notes" role="tabpanel">
                                        <div class="add-note full-x-editable">
                                            <div class="row">
                                                <div class="col-md-11 col-sm-10">
                                                    <a data-type="text" id="note-field"
                                                       data-title="Enter Note"
                                                       data-placeholder="Write you note here...."
                                                       class="editable editable-click">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table color-table">
                                                <thead>
                                                <tr>
                                                    <th class="date-column">Date</th>
                                                    <th>Note</th>
                                                    <th>User</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>10/08/17 1:08pm</td>
                                                    <td>Send Home for been drunk.</td>
                                                    <td>John Smith</td>
                                                </tr>
                                                <tr>
                                                    <td>01/08/17 3:10pm</td>
                                                    <td>Sent replacement membership card.</td>
                                                    <td>John Smith</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="identity" role="tabpanel">
                                        <div class="text-center">
                                            <div class="virtual-card-wrapper m-t-30">

                                                <div class="div-profile-img">
                                                    <div class="profile-img">
                                                        <img src="../assets/images/users/5.jpg" alt="user"/>
                                                        <div class="button-overlay">
                                                            <button id="upload-identity" type="button"
                                                                    class="btn btn-success btn-rounded pull-right">
                                                                <i class="fa fa-camera"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="img-update-date pull-left"> Updated: 02/08/2017 </p>
                                                    <input class="hide" id="identity-card-btn" type="file"
                                                           title="Upload Image"
                                                           accept="image/*"/>
                                                </div>

                                                <div class="div-virtual-card">
                                                    <div class="virtual-card">
                                                        <img src="../assets/images/background/virtual-card-bg.jpg"
                                                             alt="Card Image">
                                                        <div class="virtual-card-content">
                                                            <div class="row">
                                                                <div class="text-content col-7">
                                                                    <h3 class="name"> Brent Thomson </h3>
                                                                    <h5 class="designation"> MEMBER </h5>
                                                                    <h5 class="id-number"> 7398</h5>
                                                                    <h5 class="expiry"> Expires 31-Dec-2017 </h5>
                                                                </div>

                                                                <div class="card-image col-5">
                                                                    <img src="../assets/images/users/5.jpg" alt="user"
                                                                         class="img-responsive"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="button-overlay">
                                                            <button id="upload-card-bg-btn" type="button"
                                                                    class="btn btn-primary btn-rounded pull-right">
                                                                <i class="fa fa-picture-o"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="img-update-date pull-left"> Updated: 02/08/2017 </p>
                                                    <input class="hide" id="upload-card-bg" type="file" title="Upload Image"
                                                           accept="image/*"/>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-t-20 p-b-20" id="logs" role="tabpanel">
                                        <div class="logs-tab-content">
                                            <ul class="nav nav-tabs customtab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                                        href="#change-log" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-info"></i></span> <span
                                                                class="hidden-xs-down">Change Log</span></a></li>
                                                <li class="nav-item"><a class="nav-link" data-toggle="tab"
                                                                        href="#view-log" role="tab"
                                                                        aria-expanded="false"><span
                                                                class="hidden-sm-up"><i class="fa fa-eye"></i></span> <span
                                                                class="hidden-xs-down">View Log</span></a></li>
                                            </ul>

                                            <div class="tab-content">
                                                <div class="tab-pane logs  active" id="change-log" role="tabpanel">
                                                    <div class="p-20">
                                                        <div class="change-log-content">

                                                            <div class="table-responsive">
                                                                <table class="table color-table">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="date-column">Date</th>
                                                                        <th>User</th>
                                                                        <th>Validate Admin</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>31/12/16 04:20</td>
                                                                        <td>Validate Admin</td>
                                                                        <td>Viewed Details, Subscription, Events</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane logs  p-20" id="view-log" role="tabpanel">
                                                    <div class="view-log-content">
                                                        <table class="table color-table">
                                                            <thead>
                                                            <tr>
                                                                <th class="date-column">Date</th>
                                                                <th>User</th>
                                                                <th>Validate Admin</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>31/12/16 04:20</td>
                                                                <td>Validate Admin</td>
                                                                <td>Viewed Details, Subscription, Events</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!--Reports Modal -->
            <div id="reports-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="reports">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="reports" class="modal-title text-center">
                                Reports
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <h4 class="text-center text-muted">Members</h4>
                                    <div class="reports-link"><a href="#"> New Members </a></div>
                                    <div class="reports-link"><a href="#"> Expired </a></div>
                                    <div class="reports-link"><a href="#"> Deceased</a></div>
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="text-center text-muted">Payments</h4>
                                    <div class="reports-link"><a href="#"> Latest </a></div>
                                    <div class="reports-link"><a href="#"> Due </a></div>
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="text-center text-muted">Events</h4>
                                    <div class="reports-link"><a href="#"> Current </a></div>
                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">
                            <div class="row">
                                <div class="col-lg-4">
                                    <h4 class="text-center text-muted">Comms</h4>
                                    <div class="reports-link"><a href="#"> Email </a></div>
                                    <div class="reports-link"><a href="#"> SMS </a></div>
                                    <div class="reports-link"><a href="#"> Delivery Report</a></div>
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="text-center text-muted">Custom Report</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Invoice Modal-->
            <div id="invoice-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="invoice">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="invoice" class="modal-title">
                                INVOICE # 18365
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="payment-invoice">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <div>
                                                <h3> &nbsp;<b class="text-danger">Upper Hutt Cossie Club</b></h3>
                                                <p class="text-muted m-l-5">11 Logan Street
                                                    <br> Upper Hutt
                                                    <br> Wellington 5018</p>
                                            </div>
                                        </div>
                                        <div class="pull-right text-right">
                                            <div>
                                                <h3>Invoice To</h3>
                                                <h4 class="font-bold">Brent Thomson</h4>
                                                <p class="text-muted m-l-30">PO Box 48046
                                                    <br> Silverstream
                                                    <br> Upper Hutt 5142</p>
                                                <p class="m-t-30"><b>Invoice Date :</b> <i class="fa fa-calendar"></i> 1st
                                                    Aug 2017</p>
                                                <p><b>Due Date :</b> <i class="fa fa-calendar"></i>1st Sept 2017</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive p-t-20" style="clear: both;">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Description</th>
                                                    <th class="text-right">Quantity</th>
                                                    <th class="text-right">Unit Cost</th>
                                                    <th class="text-right">Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td>Standard Membership</td>
                                                    <td class="text-right">1</td>
                                                    <td class="text-right"> $31.30</td>
                                                    <td class="text-right"> $31.30</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-right m-t-30 text-right">
                                            <p>Sub - Total amount: $31.30</p>
                                            <p>GST (15%) : $4.70 </p>
                                            <hr>
                                            <h3><b>Total :</b> $36.00</h3>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr>
                                        <div class="text-right">
                                            <button class="btn btn-danger" type="submit"> Proceed to payment</button>
                                            <button id="print" class="btn btn-default btn-outline" type="button"><span><i
                                                            class="fa fa-print"></i> Print</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Gateway Modal-->
            <div id="gateway-detail-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="gateway-detail">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="gateway-detail" class="modal-title">
                                Gateway Details
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body my-height">
                            <div id="gateway-detail-form">
                                <div class="row">
                                    <label class="col-md-4 my-label">Select Gateway :</label>
                                    <div class="col-md-7">
                                        <a data-type="select" data-pk="1"
                                           data-title="Select Option"
                                           class="editable editable-click"
                                           id="select-gateway"></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 my-label">Account #
                                        :</label>
                                    <div class="col-md-7">
                                        <a data-type="text"
                                           data-title="Enter Account Number"
                                           data-placeholder="Account Number"
                                           class="editable editable-click inline-text">
                                            65437658
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Site Settings Modal-->
            <div id="site-settings-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="site-setttings">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="site-setttings" class="modal-title">
                                Site Settings
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="site-settings-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <label class="col-md-4 my-label">MailChimp Key:</label>
                                            <div class="col-md-7">
                                                <a data-type="text"
                                                   data-title="MailChimp Key"
                                                   data-placeholder="Enter Key Here"
                                                   data-emptytext="Enter Key Here"
                                                   class="editable editable-click inline-text"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <label class="col-md-4 my-label">Mandrill Key:</label>
                                            <div class="col-md-7">
                                                <a data-type="text"
                                                   data-title="Mandrill Key"
                                                   data-placeholder="Enter Key Here"
                                                   data-emptytext="Enter Key Here"
                                                   class="editable editable-click inline-text"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-md-4 my-label multi-dd-label">Industry</div>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <div class="input-group">
                                                        <input class="form-control" id="add-industry" type="text"
                                                               title="Interest"
                                                               placeholder="Enter Industry">
                                                    <span class="input-group-btn">
                                                        <button id="add-industry-btn"
                                                                class="btn btn-info" type="button">
                                                            <i class="fa fa-plus show-480"></i>
                                                            <span class="hide-480">Add</span>
                                                        </button>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="m-t-20 m-b-20">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h4 class="text-muted">Industry</h4>
                                        <div class="multi-dd-list">
                                            <ul id="industry-list" class="list-group">

                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Site Settings Modal-->
            <div id="show-card-modal" class="modal fade" role="dialog"
                 aria-labelledby="show-virtual-card">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="show-virtual-card" class="modal-title">
                                Virtual Card
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center p-20">
                                <div class="virtual-card">
                                    <img src="../assets/images/background/virtual-card-bg.jpg" alt="Card Image">
                                    <div class="virtual-card-content">
                                        <div class="row">
                                            <div class="text-content col-7">
                                                <h3 class="name"> Brent Thomson </h3>
                                                <h5 class="designation"> MEMBER </h5>
                                                <h5 class="id-number"> 7398</h5>
                                                <h5 class="expiry"> Expires 31-Dec-2017 </h5>
                                            </div>

                                            <div class="card-image col-5">
                                                <img src="../assets/images/users/5.jpg" alt="user"
                                                     class="img-responsive"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Payments Modal-->
            <div id="payments-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="show-payments">
                <div class="modal-dialog modal-lg div-my-preloader">
                    <div class="modal-content">
                        <div class="my-preloader preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none"
                                        stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="modal-header">
                            <h4 id="show-payments" class="modal-title">
                                Payments
                            </h4>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="user-tabs">
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab"
                                           href="#billing-tab" role="tab">
                                            <span class="hidden-sm-up"><i class="fa fa-dollar"></i></span>
                                        <span
                                                class="hidden-xs-down">Billing</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#invoices-tab"
                                           role="tab">
                                                                <span class="hidden-sm-up"><i
                                                                            class="fa fa-file-text"></i></span> <span
                                                    class="hidden-xs-down">Invoices</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#receipts-tab"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="ti-receipt"></i>
                                </span> <span class="hidden-xs-down">Receipts</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#statement-tab"
                                           role="tab">
                                <span class="hidden-sm-up">
                                    <i class="fa fa-newspaper-o"></i>
                                </span> <span class="hidden-xs-down">Statement</span>
                                        </a>
                                    </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane p-t-20 active" id="billing-tab"
                                         role="tabpanel">
                                        <div class="pull-right detail-modal-btns inline-block">
                                            <button type="button"
                                                    class="btn btn-primary btn-circle btn-sm"
                                                    title="Print"><i
                                                        class="fa fa-print"></i></button>
                                            <button type="button"
                                                    class="btn btn-danger btn-circle btn-sm"
                                                    title="Delete"><i
                                                        class="fa fa-trash"></i></button>
                                        </div>

                                        <h3> Subscription Terms</h3>
                                        <p>Expiry is the last day of the month where birthday falls.
                                            <br/>
                                            Payment is due before expiry or subscription will be
                                            overdue and will expire if
                                            payment not made 30 days after due date.
                                        </p>
                                        <div class="table-responsive m-t-20">
                                            <table class="table table-bordered color-bordered-table inverse-bordered-table billing-table text-center">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th colspan="2"> Subscription Duration</th>
                                                    <th colspan="7"> Payment Date: 20/08/2017</th>
                                                </tr>
                                                <tr class="compact-table-header">
                                                    <th class="member-id-field">Member ID</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Subscription</th>
                                                    <th>Name</th>
                                                    <th>Amount</th>
                                                    <th>GST</th>
                                                    <th>Card</th>
                                                    <th>Email</th>
                                                    <th></th>
                                                </tr>

                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>7398</td>
                                                    <td>01/01/2018</td>
                                                    <td>31/12/2018</td>
                                                    <td>Standard Member</td>
                                                    <td>Brent Thomson</td>
                                                    <td>$36.00</td>
                                                    <td>$4.70</td>
                                                    <td class="text-center">
                                                        <div class="checkbox checkbox-info checkbox-circle">
                                                            <input id="isCard" type="checkbox"
                                                                   checked="">
                                                            <label for="isCard"> </label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="checkbox checkbox-info checkbox-circle">
                                                            <input id="isEmail" type="checkbox">
                                                            <label for="isEmail"> </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-danger btn-circle btn-sm"
                                                                title="Delete Row"><i
                                                                    class="fa fa-times"></i></button>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input id="member-id"
                                                               class="form-control member-id-field"
                                                               name="member-id"
                                                               placeholder="Enter Member ID" type="text"
                                                               title="Member ID">
                                                    </td>
                                                    <td colspan="9" class="text-left">
                                                        <button type="button" class="btn btn-info btn-rounded"
                                                                id="add-billing">
                                                            <i class="fa fa-plus"></i> Add
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="billing-calculations m-t-20">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Pro Rata
                                                        </div>
                                                        <div class="col-md-5">
                                                            <span>12 Months</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Joining Fee
                                                        </div>
                                                        <div class="col-md-5">
                                                            <span>0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Due
                                                        </div>
                                                        <div class="col-md-5">
                                                            $<span>38.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Discount
                                                        </div>
                                                        <div class="col-md-5">
                                                            <span>0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row m-t-10">
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Points
                                                        </div>
                                                        <div class="col-md-5">
                                                            <span>3.55</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Pay by Points
                                                        </div>
                                                        <div class="col-md-5">
                                                            <span>3.55</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-3">
                                                    <div class="row">
                                                        <div class="col-md-7 my-label">
                                                            Total
                                                        </div>
                                                        <div class="col-md-5">
                                                            $<span>32.45</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right m-r-30 m-t-20">
                                                <button type="button"
                                                        class="btn btn-primary btn-rounded m-r-10">
                                                    <i
                                                            class="fa fa-money"></i> Cash
                                                </button>
                                                <button type="button"
                                                        class="btn btn-info btn-rounded"><i
                                                            class="fa fa-credit-card"></i> Online
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane p-t-20" id="invoices-tab"
                                         role="tabpanel">
                                        Invoices
                                    </div>
                                    <div class="tab-pane  p-t-20" id="receipts-tab" role="tabpanel">
                                        Receipts
                                    </div>
                                    <div class="tab-pane  p-t-20" id="statement-tab"
                                         role="tabpanel">
                                        Statement
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Profile Setting Modal-->
            <div id="profile-setting-modal" class="modal fade my-detail-modal" role="dialog"
                 aria-labelledby="profile-setting">
                <div class="modal-dialog modal-lg div-my-preloader">
                    <div class="modal-content">
                        <div class="my-preloader preloader">
                            <svg class="circular" viewBox="25 25 50 50">
                                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="modal-header">
                            <h4 id="profile-setting" class="modal-title">
                                Profile Detail
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab"
                                                        href="#profile"
                                                        role="tab">Profile</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile" role="tabpanel">
                                    <div class="card-block">
                                        <div id="user-profile-details">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">First Name:</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text"
                                                               data-title="Enter First Name"
                                                               data-placeholder="Enter First Name"
                                                               class="editable editable-click inline-text"
                                                               data-emptytext="Enter First Name" style="">
                                                                Brent
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Last Name :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text" data-title="Enter Last Name"
                                                               data-placeholder="Enter Last Name"
                                                               class="editable editable-click inline-text"
                                                               data-emptytext="Enter Last Name" style="">
                                                                Thomson
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Date Added:</div>
                                                        <div class="col-md-6">
                                                    <span>
                                                        23/08/2017 11:35am
                                                    </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Full Name:</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text" data-title="Full Name"
                                                               data-placeholder="Enter Full Name"
                                                               data-emptytext="Enter Full Name"
                                                               class="editable editable-click inline-text"
                                                               style="">
                                                                Brent Thomson
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row email">
                                                <div class="col-lg-2 col-md-5 my-label">Email :</div>
                                                <div class="col-lg-10 col-md-6">
                                                    <a data-type="email" data-title="Enter Email"
                                                       data-placeholder="Enter Email"
                                                       data-emptytext="Enter Email"
                                                       class="editable editable-click inline-text" style="">
                                                        brent@validate.co.nz
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Password :</div>
                                                        <div class="col-md-6">
                                                                        <span class="user-password"
                                                                              data-password="pw-value"> *********** </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Phone :</div>
                                                        <div class="col-md-6">
                                                            <a data-type="text" data-title="Enter Phone"
                                                               data-placeholder="Enter Phone"
                                                               class="editable editable-click inline-text"
                                                               data-value="027 223 4868">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-md-5 my-label">Update Password:
                                                        </div>
                                                        <div class="col-md-6">
                                                            <button type="button"
                                                                    class="btn btn-info btn-sm btn-rounded">
                                                                <i
                                                                        class="fa fa-envelope"></i> Send
                                                                Email
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row full-x-editable">
                                                <div class="col-lg-2 my-label">Note:</div>
                                                <div class="col-lg-9 col-md-12">
                                                    <a id="profile-note" data-type="textarea"
                                                       data-placeholder="Your Note here..."
                                                       data-title="Enter Note"
                                                       data-emptytext="Enter Note here..."
                                                       class="editable editable-pre-wrapped editable-click inline-text"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!--======================================== Overview Option ==================================-->
                <section class="overview-options">
                    <div class="row">
                        <div class="col-12">
                            <!-- Column -->
                            <div class="card">
                                <div class="card-block">
                                    <div class="overview-options">
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#add-new-member"><i class="fa fa-plus"></i> Add Member
                                        </button>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target="#payments-modal"><i class="fa fa-credit-card"></i> Payment
                                        </button>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target=""><i class="fa fa-level-up"></i> Upgrade Subs
                                        </button>
                                        <button type="button" class="btn btn-info btn-rounded" data-toggle="modal"
                                                data-target=""><i class="fa fa-download"></i> Import
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <!-- Column -->
                        </div>

                    </div>
                    <!-- Column -->
                </section>

                <section class="member-search">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-block">
                                    <h2 class="card-title">Members Search</h2>
                                    <div id="add-field-clone" style="display: none">
                                        <div class="row field-to-search">
                                            <div class="col-md-4">
                                                <select class="selectpicker" data-style="form-control btn-secondary"
                                                        title="Select Dropdown">
                                                    <option>Select Option</option>
                                                    <option>First Name</option>
                                                    <option>Last Name</option>
                                                    <option>Email</option>
                                                    <option>Member ID</option>
                                                    <option>Age</option>
                                                    <option>Date of Birth</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="selectpicker" data-style="form-control btn-secondary"
                                                        title="Select Dropdown">
                                                    <option>Select Option</option>
                                                    <option>Begins with</option>
                                                    <option>Ends in</option>
                                                    <option>Contains</option>
                                                    <option>Not contain</option>
                                                    <option>Is</option>
                                                    <option>Is not</option>
                                                    <option>Equals</option>
                                                    <option>Not Equal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input class="form-control" type="text" placeholder="Text Here"
                                                       title="Text here"
                                                       name="search-query">
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <button type="button"
                                                        class="btn btn-danger btn-circle btn-remove-field"><i
                                                            class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="member-search-fields">
                                        <div class="row field-to-search">
                                            <div class="col-md-4">
                                                <select class="selectpicker" data-style="form-control btn-secondary"
                                                        title="Select Dropdown">
                                                    <option>Select Option</option>
                                                    <option>First Name</option>
                                                    <option>Last Name</option>
                                                    <option>Email</option>
                                                    <option>Member ID</option>
                                                    <option>Age</option>
                                                    <option>Date of Birth</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="selectpicker" data-style="form-control btn-secondary"
                                                        title="Select Dropdown">
                                                    <option>Select Option</option>
                                                    <option>Begins with</option>
                                                    <option>Ends in</option>
                                                    <option>Contains</option>
                                                    <option>Not contain</option>
                                                    <option>Is</option>
                                                    <option>Is not</option>
                                                    <option>Equals</option>
                                                    <option>Not Equal</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input class="form-control" title="Enter Here" type="text"
                                                       placeholder="Text Here"
                                                       name="search-query">
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <button type="button"
                                                        class="btn btn-danger btn-circle btn-remove-field"><i
                                                            class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row member-add-field">
                                        <div class="col-md-6">
                                            <button id="add-new-field" type="button" class="btn btn-secondary"><i
                                                        class="fa fa-plus-circle"></i> Add New Field
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-info member-search">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Member Searched Row -->
                <div class="row dashboard-table">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-block">
                                <div class="table-responsive p-t-20">
                                    <table class="display nowrap table table-hover table-striped table-bordered my-dataTable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td>$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td>$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td>$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td>$372,000</td>
                                        </tr>
                                        <tr>
                                            <td>Herrod Chandler</td>
                                            <td>Sales Assistant</td>
                                            <td>San Francisco</td>
                                            <td>59</td>
                                            <td>2012/08/06</td>
                                            <td>$137,500</td>
                                        </tr>
                                        <tr>
                                            <td>Rhona Davidson</td>
                                            <td>Integration Specialist</td>
                                            <td>Tokyo</td>
                                            <td>55</td>
                                            <td>2010/10/14</td>
                                            <td>$327,900</td>
                                        </tr>
                                        <tr>
                                            <td>Colleen Hurst</td>
                                            <td>Javascript Developer</td>
                                            <td>San Francisco</td>
                                            <td>39</td>
                                            <td>2009/09/15</td>
                                            <td>$205,500</td>
                                        </tr>
                                        <tr>
                                            <td>Sonya Frost</td>
                                            <td>Software Engineer</td>
                                            <td>Edinburgh</td>
                                            <td>23</td>
                                            <td>2008/12/13</td>
                                            <td>$103,600</td>
                                        </tr>
                                        <tr>
                                            <td>Jena Gaines</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>30</td>
                                            <td>2008/12/19</td>
                                            <td>$90,560</td>
                                        </tr>
                                        <tr>
                                            <td>Quinn Flynn</td>
                                            <td>Support Lead</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2013/03/03</td>
                                            <td>$342,000</td>
                                        </tr>
                                        <tr>
                                            <td>Charde Marshall</td>
                                            <td>Regional Director</td>
                                            <td>San Francisco</td>
                                            <td>36</td>
                                            <td>2008/10/16</td>
                                            <td>$470,600</td>
                                        </tr>
                                        <tr>
                                            <td>Haley Kennedy</td>
                                            <td>Senior Marketing Designer</td>
                                            <td>London</td>
                                            <td>43</td>
                                            <td>2012/12/18</td>
                                            <td>$313,500</td>
                                        </tr>
                                        <tr>
                                            <td>Tatyana Fitzpatrick</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>19</td>
                                            <td>2010/03/17</td>
                                            <td>$385,750</td>
                                        </tr>
                                        <tr>
                                            <td>Michael Silva</td>
                                            <td>Marketing Designer</td>
                                            <td>London</td>
                                            <td>66</td>
                                            <td>2012/11/27</td>
                                            <td>$198,500</td>
                                        </tr>
                                        <tr>
                                            <td>Paul Byrd</td>
                                            <td>Chief Financial Officer (CFO)</td>
                                            <td>New York</td>
                                            <td>64</td>
                                            <td>2010/06/09</td>
                                            <td>$725,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gloria Little</td>
                                            <td>Systems Administrator</td>
                                            <td>New York</td>
                                            <td>59</td>
                                            <td>2009/04/10</td>
                                            <td>$237,500</td>
                                        </tr>
                                        <tr>
                                            <td>Bradley Greer</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>41</td>
                                            <td>2012/10/13</td>
                                            <td>$132,000</td>
                                        </tr>
                                        <tr>
                                            <td>Dai Rios</td>
                                            <td>Personnel Lead</td>
                                            <td>Edinburgh</td>
                                            <td>35</td>
                                            <td>2012/09/26</td>
                                            <td>$217,500</td>
                                        </tr>
                                        <tr>
                                            <td>Jenette Caldwell</td>
                                            <td>Development Lead</td>
                                            <td>New York</td>
                                            <td>30</td>
                                            <td>2011/09/03</td>
                                            <td>$345,000</td>
                                        </tr>
                                        <tr>
                                            <td>Yuri Berry</td>
                                            <td>Chief Marketing Officer (CMO)</td>
                                            <td>New York</td>
                                            <td>40</td>
                                            <td>2009/06/25</td>
                                            <td>$675,000</td>
                                        </tr>
                                        <tr>
                                            <td>Caesar Vance</td>
                                            <td>Pre-Sales Support</td>
                                            <td>New York</td>
                                            <td>21</td>
                                            <td>2011/12/12</td>
                                            <td>$106,450</td>
                                        </tr>
                                        <tr>
                                            <td>Doris Wilder</td>
                                            <td>Sales Assistant</td>
                                            <td>Sidney</td>
                                            <td>23</td>
                                            <td>2010/09/20</td>
                                            <td>$85,600</td>
                                        </tr>
                                        <tr>
                                            <td>Angelica Ramos</td>
                                            <td>Chief Executive Officer (CEO)</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2009/10/09</td>
                                            <td>$1,200,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gavin Joyce</td>
                                            <td>Developer</td>
                                            <td>Edinburgh</td>
                                            <td>42</td>
                                            <td>2010/12/22</td>
                                            <td>$92,575</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer Chang</td>
                                            <td>Regional Director</td>
                                            <td>Singapore</td>
                                            <td>28</td>
                                            <td>2010/11/14</td>
                                            <td>$357,650</td>
                                        </tr>
                                        <tr>
                                            <td>Brenden Wagner</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>28</td>
                                            <td>2011/06/07</td>
                                            <td>$206,850</td>
                                        </tr>
                                        <tr>
                                            <td>Fiona Green</td>
                                            <td>Chief Operating Officer (COO)</td>
                                            <td>San Francisco</td>
                                            <td>48</td>
                                            <td>2010/03/11</td>
                                            <td>$850,000</td>
                                        </tr>
                                        <tr>
                                            <td>Shou Itou</td>
                                            <td>Regional Marketing</td>
                                            <td>Tokyo</td>
                                            <td>20</td>
                                            <td>2011/08/14</td>
                                            <td>$163,000</td>
                                        </tr>
                                        <tr>
                                            <td>Michelle House</td>
                                            <td>Integration Specialist</td>
                                            <td>Sidney</td>
                                            <td>37</td>
                                            <td>2011/06/02</td>
                                            <td>$95,400</td>
                                        </tr>
                                        <tr>
                                            <td>Suki Burks</td>
                                            <td>Developer</td>
                                            <td>London</td>
                                            <td>53</td>
                                            <td>2009/10/22</td>
                                            <td>$114,500</td>
                                        </tr>
                                        <tr>
                                            <td>Prescott Bartlett</td>
                                            <td>Technical Author</td>
                                            <td>London</td>
                                            <td>27</td>
                                            <td>2011/05/07</td>
                                            <td>$145,000</td>
                                        </tr>
                                        <tr>
                                            <td>Gavin Cortez</td>
                                            <td>Team Leader</td>
                                            <td>San Francisco</td>
                                            <td>22</td>
                                            <td>2008/10/26</td>
                                            <td>$235,500</td>
                                        </tr>
                                        <tr>
                                            <td>Martena Mccray</td>
                                            <td>Post-Sales support</td>
                                            <td>Edinburgh</td>
                                            <td>46</td>
                                            <td>2011/03/09</td>
                                            <td>$324,050</td>
                                        </tr>
                                        <tr>
                                            <td>Unity Butler</td>
                                            <td>Marketing Designer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/12/09</td>
                                            <td>$85,675</td>
                                        </tr>
                                        <tr>
                                            <td>Howard Hatfield</td>
                                            <td>Office Manager</td>
                                            <td>San Francisco</td>
                                            <td>51</td>
                                            <td>2008/12/16</td>
                                            <td>$164,500</td>
                                        </tr>
                                        <tr>
                                            <td>Hope Fuentes</td>
                                            <td>Secretary</td>
                                            <td>San Francisco</td>
                                            <td>41</td>
                                            <td>2010/02/12</td>
                                            <td>$109,850</td>
                                        </tr>
                                        <tr>
                                            <td>Vivian Harrell</td>
                                            <td>Financial Controller</td>
                                            <td>San Francisco</td>
                                            <td>62</td>
                                            <td>2009/02/14</td>
                                            <td>$452,500</td>
                                        </tr>
                                        <tr>
                                            <td>Timothy Mooney</td>
                                            <td>Office Manager</td>
                                            <td>London</td>
                                            <td>37</td>
                                            <td>2008/12/11</td>
                                            <td>$136,200</td>
                                        </tr>
                                        <tr>
                                            <td>Jackson Bradshaw</td>
                                            <td>Director</td>
                                            <td>New York</td>
                                            <td>65</td>
                                            <td>2008/09/26</td>
                                            <td>$645,750</td>
                                        </tr>
                                        <tr>
                                            <td>Olivia Liang</td>
                                            <td>Support Engineer</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2011/02/03</td>
                                            <td>$234,500</td>
                                        </tr>
                                        <tr>
                                            <td>Bruno Nash</td>
                                            <td>Software Engineer</td>
                                            <td>London</td>
                                            <td>38</td>
                                            <td>2011/05/03</td>
                                            <td>$163,500</td>
                                        </tr>
                                        <tr>
                                            <td>Sakura Yamamoto</td>
                                            <td>Support Engineer</td>
                                            <td>Tokyo</td>
                                            <td>37</td>
                                            <td>2009/08/19</td>
                                            <td>$139,575</td>
                                        </tr>
                                        <tr>
                                            <td>Thor Walton</td>
                                            <td>Developer</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2013/08/11</td>
                                            <td>$98,540</td>
                                        </tr>
                                        <tr>
                                            <td>Finn Camacho</td>
                                            <td>Support Engineer</td>
                                            <td>San Francisco</td>
                                            <td>47</td>
                                            <td>2009/07/07</td>
                                            <td>$87,500</td>
                                        </tr>
                                        <tr>
                                            <td>Serge Baldwin</td>
                                            <td>Data Coordinator</td>
                                            <td>Singapore</td>
                                            <td>64</td>
                                            <td>2012/04/09</td>
                                            <td>$138,575</td>
                                        </tr>
                                        <tr>
                                            <td>Zenaida Frank</td>
                                            <td>Software Engineer</td>
                                            <td>New York</td>
                                            <td>63</td>
                                            <td>2010/01/04</td>
                                            <td>$125,250</td>
                                        </tr>
                                        <tr>
                                            <td>Zorita Serrano</td>
                                            <td>Software Engineer</td>
                                            <td>San Francisco</td>
                                            <td>56</td>
                                            <td>2012/06/01</td>
                                            <td>$115,000</td>
                                        </tr>
                                        <tr>
                                            <td>Jennifer Acosta</td>
                                            <td>Junior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>43</td>
                                            <td>2013/02/01</td>
                                            <td>$75,650</td>
                                        </tr>
                                        <tr>
                                            <td>Cara Stevens</td>
                                            <td>Sales Assistant</td>
                                            <td>New York</td>
                                            <td>46</td>
                                            <td>2011/12/06</td>
                                            <td>$145,600</td>
                                        </tr>
                                        <tr>
                                            <td>Hermione Butler</td>
                                            <td>Regional Director</td>
                                            <td>London</td>
                                            <td>47</td>
                                            <td>2011/03/21</td>
                                            <td>$356,250</td>
                                        </tr>
                                        <tr>
                                            <td>Lael Greer</td>
                                            <td>Systems Administrator</td>
                                            <td>London</td>
                                            <td>21</td>
                                            <td>2009/02/27</td>
                                            <td>$103,500</td>
                                        </tr>
                                        <tr>
                                            <td>Jonas Alexander</td>
                                            <td>Developer</td>
                                            <td>San Francisco</td>
                                            <td>30</td>
                                            <td>2010/07/14</td>
                                            <td>$86,500</td>
                                        </tr>
                                        <tr>
                                            <td>Shad Decker</td>
                                            <td>Regional Director</td>
                                            <td>Edinburgh</td>
                                            <td>51</td>
                                            <td>2008/11/13</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Michael Bruce</td>
                                            <td>Javascript Developer</td>
                                            <td>Singapore</td>
                                            <td>29</td>
                                            <td>2011/06/27</td>
                                            <td>$183,000</td>
                                        </tr>
                                        <tr>
                                            <td>Donna Snider</td>
                                            <td>Customer Support</td>
                                            <td>New York</td>
                                            <td>27</td>
                                            <td>2011/01/25</td>
                                            <td>$112,000</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
