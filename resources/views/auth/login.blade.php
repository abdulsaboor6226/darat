
  @extends('layouts.master')
  @section('content')
        <!-- Log In page -->

        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="index.html" class="logo logo-admin">
                                        {{-- <img src="{{asset('assets/images/50x50.png')}}" height="50" alt="logo" class="auth-logo"> --}}
                                        {{-- <img src="{{asset('assets/images/logo-sm-dark.png')}}" height="50" alt="logo" class="auth-logo"> --}}
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Let's Get Started Darat</h4>
                                    <p class="text-muted  mb-0">Sign in to continue to Darat.</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="nav-border nav nav-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#LogIn_Tab" role="tab">Log In</a>
                                    </li>
                                </ul>
                                 <!-- Tab panes -->
                                <div class="tab-content">

                                    @if(isset ($errors) && count($errors) > 0)
                                        <div class="alert alert-warning" role="alert">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @if (session('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('message') }}
                                </div>
                                @endif
                                    <div class="tab-pane active p-3" id="LogIn_Tab" role="tabpanel">
                                       <form class="form-horizontal auth-form" method="POST" action="{{ route('login') }}">
                                             @csrf
                                            <div class="form-group mb-2">
                                                <label class="form-label" for="name">Username</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="name" id="name" value="" placeholder="Enter Username" required>
                                                </div>
                                                <small id="emailHelp" class="form-text text-danger">{{$errors->first('name')}}</small>
                                            </div><!--end form-group-->

                                            <div class="form-group mb-2">
                                                <label class="form-label" for="password">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password" value=""  id="password" placeholder="Enter password" required>
                                                </div>
                                                <small id="emailHelp" class="form-text text-danger">{{$errors->first('password')}}</small>
                                            </div><!--end form-group-->

                                            <div class="form-group row my-3">
                                                <div class="col-sm-6">
                                                    <div class="custom-control custom-switch switch-success">
                                                        {{-- <input type="checkbox" class="custom-control-input" id="customSwitchSuccess">
                                                         <label class="form-label text-muted" for="customSwitchSuccess">Remember me</label> --}}
                                                    </div>
                                                </div><!--end col-->
                                                {{-- <div class="col-sm-6 text-end">
                                                    <a href="auth-recover-pw.html" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                                </div> --}}
                                                <!--end col-->
                                            </div><!--end form-group-->

                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                  <button type="submit" class="btn btn-primary w-100 waves-effect waves-light" o  >Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div><!--end col-->
                                            </div> <!--end form-group-->
                                        </form>
                                            <!--end form-->
                                        {{-- <div class="m-3 text-center text-muted">
                                            <p class="mb-0">Don't have an account ?  <a href="auth-register.html" class="text-primary ms-2">Free Resister</a></p>
                                        </div>--}}
                                        <div class="account-social">
                                            <h6 class="mb-3">Or Login With</h6> </div>
                                        <div class="btn-group w-100">
{{--                                            <button type="button" class="btn btn-sm btn-outline-secondary">Facebook</button>--}}
{{--                                            <a type="button" class="btn btn-sm btn-outline-primary">Apple</a>--}}
{{--                                            <a type="button" class="btn btn-sm btn-outline-primary">Google</a>--}}
                                            <a href="{{ route('google') }}">
                                                <img src="{{asset('assets/images/google_signin_btn.png')}}">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                            <div class="card-body bg-light-alt text-center">
                                <span class="text-muted d-none d-sm-inline-block">Darat © <script>
                                    document.write(new Date().getFullYear())
                                </script></span>
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end col-->
        </div><!--end row-->

    <!-- End Log In page -->



   @endsection





