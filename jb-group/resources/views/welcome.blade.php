<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta name="description" content="Responsive Admin Template"/>
    <meta name="author" content="SmartUniversity"/>
    <title>CORE 247 Platform</title>
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css"/>
    <!-- icons -->
    <link href="{{ URL::to('/') }}/fonts/material-design-icons/material-icon.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/kanban/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!--bootstrap -->
    <link href="{{ URL::to('/') }}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/plugins/summernote/summernote.css" rel="stylesheet">
    <!-- morris chart -->
    <link href="{{ URL::to('/') }}/assets/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/plugins/flatpicker/css/flatpickr.min.css" rel="stylesheet" type="text/css"/>
    <!-- Material Design Lite CSS -->
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/plugins/material/material.min.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/assets/css/material_style.css">
    <!-- data tables -->
    <link href="{{ URL::to('/') }}/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css" rel="stylesheet"
          type="text/css" />
    <!-- animation -->
    <link href="{{ URL::to('/') }}/assets/css/pages/animate_page.css" rel="stylesheet">
    <!-- Theme Styles -->
    <link href="{{ URL::to('/') }}/assets/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/theme-color.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::to('/') }}/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::to('/') }}/assets/css/smartwizard/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ URL::to('/') }}/assets/img/favicon.ico"/>
</head>
<!-- END HEAD -->

<body
    class="page-header-fixed sidemenu-closed-hidelogo page-content-white page-md header-white red-sidebar-color logo-white">
<div class="page-wrapper">
    <!-- start header -->
@extends('header')
<!-- end header -->
    <!-- start page container -->
    <div class="page-container">
        <!-- start sidebar menu -->
        <div class="sidebar-container">
            <div class="sidemenu-container navbar-collapse collapse fixed-menu">
                <div id="remove-scroll">
                    <ul class="sidemenu page-header-fixed p-t-20" data-keep-expanded="false" data-auto-scroll="true"
                        data-slide-speed="200">
                        <li class="sidebar-toggler-wrapper hide">
                            <div class="sidebar-toggler">
                                <span></span>
                            </div>
                        </li>
                        <li class="sidebar-user-panel">
                            <div class="user-panel">
                                <div class="pull-left image">
                                    <img src="{{ URL::to('/') }}/assets/img/dp.jpg" class="img-circle user-img-circle"
                                         alt="User Image"/>
                                </div>
                                <div class="pull-left info">
                                    @auth
                                        <p> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
                                    @endauth
                                </div>
                            </div>
                        </li>
                        <li class="menu-heading">
                        </li>
                        @auth
                            @if (Auth::user()->is_engineer)
                                <li class="nav-item">
                                    <a href="{{url('/inventory/client-index')}}" class="nav-link nav-toggle">
                                        <i class="material-icons">people</i>
                                        <span class="title">Systems</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{url('inventory/client-index')}}" class="nav-link ">
                                                <span class="title">Clients</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('inventory/all-servers')}}" class="nav-link ">
                                                <span class="title">Servers</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('inventory/all-vms')}}" class="nav-link ">
                                                <span class="title">Vms</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('ip-addresses')}}" class="nav-link nav-toggle">
                                        <i class="material-icons">people</i>
                                        <span class="title">IP plan</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{url('ip-addresses')}}" class="nav-link ">
                                                <span class="title">List</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('roles')}}" class="nav-link nav-toggle">
                                        <i class="material-icons">people</i>
                                        <span class="title">Roles</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{url('roles')}}" class="nav-link ">
                                                <span class="title">List</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('roles/create')}}" class="nav-link ">
                                                <span class="title">Add</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('employees.index') }}" class="nav-link">
                                        <i class="material-icons">people</i>
                                        <span class="title">Employee</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('organizations')}}" class="nav-link nav-toggle">
                                        <i class="material-icons">account_balance</i>
                                        <span class="title">Organization</span>
                                        <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="nav-item">
                                            <a href="{{url('organizations')}}" class="nav-link ">
                                                <span class="title">List</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('organizations/create')}}" class="nav-link ">
                                                <span class="title">Add</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('organizations')}}" class="nav-link nav-toggle">
                                        <i class="material-icons">account_balance</i>
                                        <span class="title">Server location</span>
                                        <span class="arrow"></span>
                                    </a>

                                </li>
                            @endif
                        @endauth


                        @can('FORM_INVENTORY')
                            <li class="nav-item">
                                <a href="{{url('roles')}}" class="nav-link nav-toggle">
                                    <i class="material-icons">people</i>
                                    <span class="title">Inventory</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{url('inventory/client-index')}}" class="nav-link ">
                                            <span class="title">List</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('FORM_ROLES')
                            <li class="nav-item">
                                <a href="{{url('roles')}}" class="nav-link nav-toggle">
                                    <i class="material-icons">people</i>
                                    <span class="title">Roles</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{url('roles')}}" class="nav-link ">
                                            <span class="title">List</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('roles/create')}}" class="nav-link ">
                                            <span class="title">Add</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('FORM_EMPLOYEE')
                            <li class="nav-item">
                                <a href="{{ route('employees.index') }}" class="nav-link">
                                    <i class="material-icons">people</i>
                                    <span class="title">Employee</span>
                                </a>
                            </li>
                        @endcan

                        @can('FORM_ACCESS')
                            <li class="nav-item">
                                <a href="{{url('accesses')}}" class="nav-link nav-toggle">
                                    <i class="material-icons">security</i>
                                    <span class="title">Access</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{url('accesses')}}" class="nav-link ">
                                            <span class="title">List</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('accesses/create')}}" class="nav-link ">
                                            <span class="title">Add</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('FORM_ROLES')
                            <li class="nav-item">
                                <a href="#" class="nav-link nav-toggle">
                                    <i class="material-icons">person</i>
                                    <span class="title">Users</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                </ul>
                            </li>
                        @endcan
                        @can('FORM_ORGANIZATION')
                            <li class="nav-item">
                                <a href="{{ route('ip-addresses.index') }}" class="nav-link">
                                    <i class="material-icons">people</i>
                                    <span class="title">IP план</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('jira.board.show') }}" class="nav-link">
                                    <i class="material-icons">account_balance</i>
                                    <span class="title">Jira</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('organizations')}}" class="nav-link nav-toggle">
                                    <i class="material-icons">account_balance</i>
                                    <span class="title">Organization</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{url('organizations')}}" class="nav-link ">
                                            <span class="title">List</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('organizations/create')}}" class="nav-link ">
                                            <span class="title">Add</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('FORM_TEAM')
                            <li class="nav-item">
                                <a href="{{url('teams')}}" class="nav-link nav-toggle">
                                    <i class="material-icons">account_balance</i>
                                    <span class="title">Teams</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="nav-item">
                                        <a href="{{url('teams')}}" class="nav-link ">
                                            <span class="title">List</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('teams/create')}}" class="nav-link ">
                                            <span class="title">Add</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                        @can('FORM_MY_TEAM')
                            <li class="nav-item">
                                <a href="" class="nav-link nav-toggle">
                                    <i class="material-icons">account_balance</i>
                                    <span class="title">My team</span>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{url('server-locations')}}" class="nav-link nav-toggle">
                                <i class="material-icons">account_balance</i>
                                <span class="title">Server location</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('server-locations')}}" class="nav-link ">
                                        <span class="title">List</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('server-locations/create')}}" class="nav-link ">
                                        <span class="title">Add</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{url('server-groups')}}" class="nav-link nav-toggle">
                                <i class="material-icons">account_balance</i>
                                <span class="title">Server group</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('server-groups')}}" class="nav-link ">
                                        <span class="title">List</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('server-groups/create')}}" class="nav-link ">
                                        <span class="title">Add</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end sidebar menu -->
        <!-- start page content -->
        <div class="page-content-wrapper">
            @yield('content')
        </div>
        <!-- end page content -->
        <!-- start chat sidebar -->
        <div class="chat-sidebar-container" data-close-on-body-click="false">
            <div class="chat-sidebar">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#quick_sidebar_tab_1" class="nav-link active tab-icon" data-bs-toggle="tab">Theme
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#quick_sidebar_tab_2" class="nav-link tab-icon" data-bs-toggle="tab"> Settings
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane chat-sidebar-settings in show active animated shake" role="tabpanel"
                         id="quick_sidebar_tab_1">
                        <div class="slimscroll-style">
                            <div class="theme-light-dark">
                                <h6>Sidebar Theme</h6>
                                <button type="button" data-theme="white"
                                        class="btn lightColor btn-outline btn-circle m-b-10 theme-button">Light
                                    Sidebar
                                </button>
                                <button type="button" data-theme="dark"
                                        class="btn dark btn-outline btn-circle m-b-10 theme-button">Dark
                                    Sidebar
                                </button>
                            </div>
                            <div class="theme-light-dark">
                                <h6>Sidebar Color</h6>
                                <ul class="list-unstyled">
                                    <li class="complete">
                                        <div class="theme-color sidebar-theme">
                                            <a href="#" data-theme="white"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="dark"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="blue"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="indigo"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="cyan"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="green"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="red"><span class="head"></span><span
                                                    class="cont"></span></a>
                                        </div>
                                    </li>
                                </ul>
                                <h6>Header Brand color</h6>
                                <ul class="list-unstyled">
                                    <li class="theme-option">
                                        <div class="theme-color logo-theme">
                                            <a href="#" data-theme="logo-white"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-dark"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-blue"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-indigo"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-cyan"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-green"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="logo-red"><span class="head"></span><span
                                                    class="cont"></span></a>
                                        </div>
                                    </li>
                                </ul>
                                <h6>Header color</h6>
                                <ul class="list-unstyled">
                                    <li class="theme-option">
                                        <div class="theme-color header-theme">
                                            <a href="#" data-theme="header-white"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-dark"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-blue"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-indigo"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-cyan"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-green"><span class="head"></span><span
                                                    class="cont"></span></a>
                                            <a href="#" data-theme="header-red"><span class="head"></span><span
                                                    class="cont"></span></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Start Setting Panel -->
                    <div class="tab-pane chat-sidebar-settings animated slideInUp" id="quick_sidebar_tab_2">
                        <div class="chat-sidebar-settings-list slimscroll-style">
                            <div class="chat-header">
                                <h5 class="list-heading">Layout Settings</h5>
                            </div>
                            <div class="chatpane inner-content ">
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Sidebar Position</div>
                                        <div class="setting-set">
                                            <select
                                                class="sidebar-pos-option form-control input-inline input-sm input-small ">
                                                <option value="left" selected="selected">Left</option>
                                                <option value="right">Right</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Header</div>
                                        <div class="setting-set">
                                            <select
                                                class="page-header-option form-control input-inline input-sm input-small ">
                                                <option value="fixed" selected="selected">Fixed</option>
                                                <option value="default">Default</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Sidebar Menu</div>
                                        <div class="setting-set">
                                            <select
                                                class="sidebar-menu-option form-control input-inline input-sm input-small ">
                                                <option value="accordion" selected="selected">Accordion</option>
                                                <option value="hover">Hover</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Footer</div>
                                        <div class="setting-set">
                                            <select
                                                class="page-footer-option form-control input-inline input-sm input-small ">
                                                <option value="fixed">Fixed</option>
                                                <option value="default" selected="selected">Default</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-header">
                                    <h5 class="list-heading">Account Settings</h5>
                                </div>
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Notifications</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-1">
                                                    <input type="checkbox" id="switch-1" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Show Online</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-7">
                                                    <input type="checkbox" id="switch-7" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Status</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-2">
                                                    <input type="checkbox" id="switch-2" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">2 Steps Verification</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-3">
                                                    <input type="checkbox" id="switch-3" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-header">
                                    <h5 class="list-heading">General Settings</h5>
                                </div>
                                <div class="settings-list">
                                    <div class="setting-item">
                                        <div class="setting-text">Location</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-4">
                                                    <input type="checkbox" id="switch-4" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Save Histry</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-5">
                                                    <input type="checkbox" id="switch-5" class="mdl-switch__input"
                                                           checked>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-text">Auto Updates</div>
                                        <div class="setting-set">
                                            <div class="switch">
                                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect"
                                                       for="switch-6">
                                                    <input type="checkbox" id="switch-6" class="mdl-switch__input"
                                                           checked>
                                                </label>
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
        <!-- end chat sidebar -->
    </div>
    <!-- end page container -->
    <!-- start footer -->
    <div class="page-footer">
        <div class="page-footer-inner"> 2018 &copy; ECab Taxi Admin Template By
            <a href="" target="_top" class="makerCss">Redstartheme</a>
        </div>
        <div class="scroll-to-top">
            <i class="material-icons">eject</i>
        </div>
    </div>
    <!-- end footer -->
</div>
<!-- start js include path -->
<script src="{{ URL::to('/') }}/assets/plugins/jquery/jquery.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/popper/popper.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/jquery-blockui/jquery.blockui.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- sweetalert -->
<script src="{{ URL::to('/') }}/assets/js/pages/sweetalert/sweetalert2.all.min.js"></script>
<!-- bootstrap -->
<script src="{{ URL::to('/') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/jquery-mask/jquery.mask.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/jquery-inputmask/jquery.inputmask.js"></script>
<script src="{{ URL::to('/') }}/assets/js/pages/sparkline/sparkline-data.js"></script>
<!-- Common js-->
<script src="{{ URL::to('/') }}/assets/js/app.js"></script>
<script src="{{ URL::to('/') }}/assets/js/layout.js"></script>
<script src="{{ URL::to('/') }}/assets/js/theme-color.js"></script>
<!-- data tables -->
<script src="{{ URL::to('/') }}/assets/plugins/datatables/datatables.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/pages/table/table_data.js"></script>
<!-- Material -->
<script src="{{ URL::to('/') }}/assets/plugins/material/material.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/pages/material_select/getmdl-select.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/flatpicker/js/flatpicker.min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/pages/datepicker/datetimepicker.js"></script>
<script src="{{ URL::to('/') }}/assets/js/main.js"></script>
<!-- dropzone -->
<script src="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone-call.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/dropzone/dropzone-call.js"></script>
<!-- animation -->
<script src="{{ URL::to('/') }}/assets/js/pages/ui/animations.js"></script>
<!-- morris chart -->
<script src="{{ URL::to('/') }}/assets/plugins/morris/morris.min.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/morris/raphael-min.js"></script>
<script src="{{ URL::to('/') }}/assets/js/pages/chart/morris/morris_home_data.js"></script>
<!-- google map -->
<script src="{{ URL::to('/') }}/assets/plugins/modernizr/modernizr.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtPIcsjNx-GEuJDPmiXOVyB3G9k1eulX0&callback=initMap"
        async defer></script>
<script src="{{ URL::to('/') }}/assets/js/pages/map/gmap-home.js"></script>
<script src="{{ URL::to('/') }}/assets/plugins/flatpicker/js/flatpicker.min.js"></script>
<script src="{{ URL::to('/') }}/assets/smartwizard/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
<!-- end js include path -->
</body>

</html>
