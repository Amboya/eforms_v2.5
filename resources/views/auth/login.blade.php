@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="row justify-content-center" >
        <div class="col-md-4">


            <div class="row justify-content-center ">
             <img src="{{asset('dashboard/dist/img/ZESCO_removebg.png')}}" width="50%">
            </div>

            <div class="card" >
                <div class="card-header">{{ __('Login to E-Forms') }} </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="staff_no" class="col-md-12 col-form-label text-md-left">{{ __('Man Number') }}</label>
                            <div class="col-md-12">
                                <input id="staff_no" type="text" class="form-control @error('staff_no') is-invalid @enderror" name="staff_no" value="{{ old('staff_no') }}" required autocomplete="staff_no" autofocus>
                                @error('staff_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-12 col-form-label text-md-left">{{ __('Password') }}</label>
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
{{--                        <div class="form-group row">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <div class="form-check">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}
{{--                                    <label class="form-check-label" for="remember">--}}
{{--                                        {{ __('Remember Me') }}--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                    <a class="btn btn-link" href="{{ route('register') }}">Click Here to Register
                                    </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
