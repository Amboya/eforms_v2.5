
@extends('layouts.main.master')


@push('custom-styles')
    <!-- -->

    <style type="text/css">
        /*body {*/
        /*    background-color:#ce3635;*/
        /*    text-align: center;*/
        /*    color:#fff;*/
        /*    padding-top:10em;*/
        /*}*/

        /** { color:#fff; text-decoration: none;}*/
    </style>
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-secondary text-bold " style="color: #F59C32 ">e-FORMS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">e-Forms Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container">


            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-right ">
                        <a href="" class="typewrite" style="color: #1C984F; font-family: 'Arial Black'" data-period="2000" data-type='[ "Hi {{\Illuminate\Support\Facades\Auth::user()->name}}, Welcome to eForms.", "Access all Digitized Corporate Processes.", "Moving Towards Paperless Operations.", "I&CSS the Business Enablers." ]'>
                            <span class="wrap"></span>
                        </a>
                    </h1>
                </div><!--col-md-12-->

            </div>
            <!-- /.row -->
            <!-- END ACCORDION & CAROUSEL-->


        </div>
        <!-- /.row -->


        <!-- Main row -->
        <div class="row">
        @foreach($categories as $item)
            <!-- Left col -->
                <div class="col-md-4">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent  bg-gradient-default " style="opacity: .9">
                            <h3 class="card-title " style="color: #F59C32 ; font-family: Arial">{{$item->name}} Category</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-1">

                            <!-- Info boxes -->
                            <div class="row">
                                @foreach($item->eforms->where('category_id', $item->id ) as $eforms_item)
{{--                                    @if($eforms_item->category_id == $item->id)--}}
                                        @if($eforms_item->id == config('constants.eforms_id.petty_cash') )
                                            <div class="col-12 col-sm-6   ">
                                                <hr>
                                                <a href="{{ route($eforms_item->url ?? 'main-home')}}">
                                                    <i class="{{$eforms_item->icon ?? "fas fa-file"}} mr-1" style="color:#1C984F " ></i>
                                                    <span class="text-center" style="color:#1C984F ;font-family: 'Roboto Light'">{{$eforms_item->name}}</span>
                                                </a>
                                                <hr>
                                            </div>
                                            <!-- fix for small devices only -->
                                            <div class="clearfix hidden-md-up"></div>
                                            <!-- /.col -->

                                        @else

                                            <div class="col-12 col-sm-6    ">
                                                <hr>
                                                <a href="{{ route($eforms_item->test_url ?? 'main-home')}}">
                                                <i class="{{$eforms_item->icon ?? "fas fa-file"}} mr-1" style="color:#969696 " ></i>
                                                <span class="text-center" style="color:#969696 ;font-family: 'Roboto Light'">{{$eforms_item->name}}</span>
                                                </a>
                                                <hr>
                                            </div>
                                            <!-- fix for small devices only -->
                                            <div class="clearfix hidden-md-up"></div>
                                            <!-- /.col -->
                                        @endif
{{--                                    @endif--}}
                                @endforeach
                            </div>
                            <!-- /.row -->

                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->


    <!-- /.POP UP MODEL TO FORCE USER TO CHANGE PASSWORD -->
    <div class="modal fade" id="modal-change-password">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Change Password</h4>
                </div>
                <!-- form start -->
                <form method="POST" action="{{ route('main.user.change.password') }}">
                    @csrf
                    <div class="p-4">
                        <div class="row justify-content-center ">
                            <img src="{{asset('dashboard/dist/img/ZESCO_removebg.png')}}" width="50%">
                        </div>

                        <div class="form-group row">
                            <label for="old_password"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Old Password') }}</label>
                            <div class="col-md-6">
                                <input id="old_password" type="password"
                                       class="form-control @error('old_password') is-invalid @enderror" name="old_password"
                                       value="{{ old('old_password') }}" required autocomplete="current-password" autofocus>
                                @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror" name="password"
                                       value="{{ old('password') }}" required autocomplete="current-password" autofocus>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm"
                                   class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password"
                                       class="form-control @error('confirm_password') is-invalid @enderror" name="password_confirmation"
                                       required autocomplete="new-password">
                                @error('confirm_password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Change Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.end modal -->




@endsection


@push('custom-scripts')
    <!-- jQuery Mapael -->
    <script src="{{ asset('dashboard/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('dashboard/plugins/chart.js/Chart.min.js')}}"></script>

    <!-- PAGE SCRIPTS -->
    <script src="{{ asset('dashboard/dist/js/pages/dashboard2.js')}}"></script>

    <script type="text/javascript">
        var TxtType = function(el, toRotate, period) {
            this.toRotate = toRotate;
            this.el = el;
            this.loopNum = 0;
            this.period = parseInt(period, 10) || 2000;
            this.txt = '';
            this.tick();
            this.isDeleting = false;
        };

        TxtType.prototype.tick = function() {
            var i = this.loopNum % this.toRotate.length;
            var fullTxt = this.toRotate[i];

            if (this.isDeleting) {
                this.txt = fullTxt.substring(0, this.txt.length - 1);
            } else {
                this.txt = fullTxt.substring(0, this.txt.length + 1);
            }

            this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

            var that = this;
            var delta = 200 - Math.random() * 100;

            if (this.isDeleting) { delta /= 2; }

            if (!this.isDeleting && this.txt === fullTxt) {
                delta = this.period;
                this.isDeleting = true;
            } else if (this.isDeleting && this.txt === '') {
                this.isDeleting = false;
                this.loopNum++;
                delta = 500;
            }

            setTimeout(function() {
                that.tick();
            }, delta);
        };

        window.onload = function() {
            var elements = document.getElementsByClassName('typewrite');
            for (var i=0; i<elements.length; i++) {
                var toRotate = elements[i].getAttribute('data-type');
                var period = elements[i].getAttribute('data-period');
                if (toRotate) {
                    new TxtType(elements[i], JSON.parse(toRotate), period);
                }
            }
            // INJECT CSS
            var css = document.createElement("style");
            css.type = "text/css";
            css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff }";
            document.body.appendChild(css);
        };
    </script>


    <script>
        // Get the modal on windows page load
        window.onload = function () {
            //check if the user password is changed
            var pwd_change ={!! json_encode(  config('constants.password_not_changed')  ) !!} ;
            var user_pwd_change ={!! json_encode(  \Auth::user()->password_changed ) !!} ;
            var user_pwd_ezesco ={!! json_encode(  \Auth::user()->password ) !!} ;
            //check if the user has user unit
            var user_unit ={!! json_encode(  \Auth::user()->user_unit_code ) !!} ;

            if (Number(pwd_change) == Number(user_pwd_change)) {
                $('#modal-change-password').modal({backdrop: 'static', keyboard: false});
                $('#modal-change-password').modal('show');
            }
            else
            if ( user_pwd_ezesco == "$2y$10$IEb9UtrGydjucN3uD4VWZ.us5bKNTNxmwUVgpwHWGm.ids9j6q/IC" ){
                alert("Sorry your password has been detected amongst the list of very weak passwords");
                $('#modal-change-password').modal({backdrop: 'static', keyboard: false});
                $('#modal-change-password').modal('show');
            }

            if ( user_unit == null) {
                $('#modal-change-unit').modal({backdrop: 'static', keyboard: false});
                $('#modal-change-unit').modal('show');
            }

        }

    </script>

@endpush
