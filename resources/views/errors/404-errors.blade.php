
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Darat</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        @include('layouts.partials.css')
        @yield('css')
    </head>

    <body class="">
        <!-- Eror-404 page -->

        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content">
              <div class="container-fluid">
                <div class="row vh-100 d-flex justify-content-center">
                    <div class="col-12 align-self-center">
                        <div class="row">
                            <div class="col-lg-5 mx-auto">
                                <div class="card">
                                    <div class="card-body p-0 auth-header-box">
                                        <div class="text-center p-3">
                                            <a href="index.html" class="logo logo-admin">
                                                <img src="{{asset('assets/images/100x100.png')}}" height="100" alt="logo" class="auth-logo">
                                            </a>
                                            <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Oops! Sorry page does not found</h4>
                                            <p class="text-muted  mb-0">Back to dashboard of Dastone.</p>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="ex-page-content text-center">
                                            <img src="{{asset('assets/images/error.svg')}}" alt="0" class="" height="170">
                                            <h1 class="mt-5 mb-4">404!</h1>
                                            <h5 class="font-16 text-muted mb-5">Somthing went wrong</h5>
                                        </div>
                                        <a class="btn btn-primary w-100 waves-effect waves-light" href="{{url('/')}}">Back to Dashboard <i class="fas fa-redo ms-1"></i></a>
                                    </div>
                                    <div class="card-body bg-light-alt text-center">
                                        <span class="text-muted d-none d-sm-inline-block">Darat © 2021<script>
                                            document.write(new Date().getFullYear())
                                        </script></span>
                                    </div>
                                </div><!--end card-->
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end col-->
                </div><!--end row-->
               </div><!--end row-->
            </div><!--end row-->



        <!-- Scrpts  -->
        @include('layouts.partials.scripts')
        @yield('scripts')
    </body>


</html>




