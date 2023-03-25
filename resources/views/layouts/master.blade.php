
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{env('APP_NAME')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        @include('layouts.partials.css')
        @yield('css')
    </head>

    <body class="">
        @if(Request::path() !='login' )
        <!-- Left Sidenav -->
        @include('layouts.partials.sidebar')
        <!-- end left-sidenav-->


        <div   class="page-wrapper">
            <!-- Top Bar Start -->
            @include('layouts.partials.navbar')
            <!-- Top Bar End -->

            <!-- Page Content-->
            <div class="page-content">
             <div class="container-fluid">
              @yield('content')
            </div><!-- container -->
              @include('layouts.footer')
            </div>
            <!-- end page content -->
        </div>
        @else


            <!-- Page Content-->
            <div class="account-body accountbg">
             <div class="container">
              @yield('content')
            </div><!-- container -->
        </div>
            <!-- end page content -->
    
        @endif
        <!-- end page-wrapper -->



        <!-- Scrpts  -->
        @include('layouts.partials.scripts')
        @yield('scripts')
        @include('sweetalert::alert')
    </body>


</html>
