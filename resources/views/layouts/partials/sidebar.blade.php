<!-- Left Sidenav -->
    <div class="left-sidenav">
        <!-- LOGO -->
        <div class="brand">
            <a href="{{url('/')}}" class="logo">
                <span>
                    {{-- <img src="{{asset('assets/images/logo-sm.png')}}" alt="logo-small" class="logo-sm"> --}}
                    {{-- <img src="{{asset('assets/images/100x100.png')}}" alt="logo-small" style="height: 84px;" class="logo-sm"> --}}
                </span>
                <span>
                    <img src="{{asset('assets/images/100x100.png')}}" alt="logo-large" class="logo-lg logo-light">
                    {{-- <img src="{{asset('assets/images/100x100.png')}}" alt="logo-large" class="logo-lg logo-dark"> --}}
                    {{-- <img src="{{asset('assets/images/logo.png')}}" alt="logo-large" class="logo-lg logo-light">
                    <img src="{{asset('assets/images/logo-dark.png')}}" alt="logo-large" class="logo-lg logo-dark"> --}}
                </span>
            </a>
        </div>
        <!--end logo-->
        <div class="menu-content h-100" data-simplebar>
            <ul class="metismenu left-sidenav-menu">
                <li class="menu-label mt-0">Main</li>
                <li><a href="{{url('/dashboard')}}"> <i data-feather="home" class="align-self-center menu-icon"></i><span>Dashboard</span><span class="menu-arrow"></span></a></li>
                 <li><a href="{{url('projects')}}"> <i data-feather="file-text" class="align-self-center menu-icon"></i><span>Projects</span><span class="menu-arrow"></span></a></li>
                 <li><a href="{{url('packages')}}"> <i data-feather="package" class="align-self-center menu-icon"></i><span>Packages</span><span class="menu-arrow"></span></a></li>
                 <li><a href="{{url('products/create')}}"> <i data-feather="package" class="align-self-center menu-icon"></i><span>Add Product</span><span class="menu-arrow"></span></a></li>
                 <li><a href="{{url('rate-lists')}}"> <i data-feather="list" class="align-self-center menu-icon"></i><span>Rate Lists</span><span class="menu-arrow"></span></a></li>
                 <li><a href="{{url('users')}}"> <i data-feather="user" class="align-self-center menu-icon"></i><span>Users</span><span class="menu-arrow"></span></a></li>
        </div>
    </div>
<!-- end left-sidenav-->
