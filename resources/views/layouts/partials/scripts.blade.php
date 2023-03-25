

        <!-- jQuery  -->
        <script src="{{asset("assets/js/jquery.min.js")}}"></script>
        <script src="{{asset("assets/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{asset("assets/js/metismenu.min.js")}}"></script>
        <script src="{{asset("assets/js/waves.js")}}"></script>
        <script src="{{asset("assets/js/feather.min.js")}}"></script>
        <script src="{{asset("assets/js/simplebar.min.js")}}"></script>
        <script src="{{asset("assets/js/moment.js")}}"></script>
        <script src="{{asset("assets/plugins/daterangepicker/daterangepicker.js")}}"></script>
        <script src="{{asset("assets/plugins/sweet-alert2/sweetalert2.min.js")}}"></script>
        <script src="{{asset("assets/pages/jquery.sweet-alert.init.js")}}"></script>

        {{-- <script src="{{asset("assets/plugins/apex-charts/apexcharts.min.js")}}"></script> --}}
        <script src="{{asset("assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js")}}"></script>
        <script src="{{asset("assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js")}}"></script>
        {{-- <script src="{{asset("assets/pages/jquery.analytics_dashboard.init.js")}}"></script> --}}
        <script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script>
        <script src="{{asset('assets/plugins/toast-master/js/jquery.toast.js')}}"></script>
        {{-- <script src="{{asset('assets/pages/jquery.forms-advanced.js')}}"></script> --}}
        <!-- App js -->
               <!-- Parsley js -->
       <script src="{{asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
       <script src="{{asset('assets/pages/jquery.validation.init.js')}}"></script>
        <script src="{{asset("assets/js/app.js")}}"></script>
      
 <!-- Required datatable js -->
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(".select2").select2()
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    function toastAlert(heading, text, icon) {
    // icon mean type
    //You can use four different alert info, warning, success, and error message.
        $.toast({
            heading,
            text,
            position: 'top-right',
            loaderBg: '#ff6849',
            icon,
            hideAfter: 8000,
            stack: 6
        });
    }
    
</script>
<script src="{{asset('assets/js/my-custom.js')}}?_={{rand(1,1000)}}" type="text/javascript"></script>









