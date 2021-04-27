@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="row justify-content-center ">
            <img src="{{asset('dashboard/dist/img/ZESCO_removebg.png')}}" width="50%">
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register to E-Forms') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Man No') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('staff_no') is-invalid @enderror" name="staff_no" value="{{ old('staff_no') }}" required autocomplete="staff_no" autofocus>

                                @error('staff_no')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user_unit" class="col-md-4 col-form-label text-md-right">{{ __('User Unit') }}</label>

                            <div class="col-md-6">

                                <select id="user_unit" class="form-control select2 " name="user_unit"  >
                                    <option value="" selected disabled >Select User Unit 1</option>
                                    @foreach($user_unit as $item)
                                        <option value="{{$item->id}}" >{{$item->user_unit_code}} : {{$item->user_unit_description}}</option>
                                    @endforeach
                                </select>
{{--                                <input list="user_unit_list" id="user_unit" class="form-control " name="user_unit" required >--}}
{{--                                <datalist id="user_unit_list"   >--}}
{{--                                    <option value="" selected disabled >Select User Unit</option>--}}
{{--                                    @foreach($user_unit as $item)--}}
{{--                                        <option value="{{$item->id}}" >{{$item->user_unit_code}} : {{$item->user_unit_description}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </datalist>--}}

                                @error('user_unit')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Extension/Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        {{--HIDDEN FIELS--}}
                        <input id="profile_id" type="hidden"  name="profile_id" value="{{config('constants.user_profiles.initiator')}}">
                        <input id="type_id" type="hidden"  name="type_id" value="{{config('constants.user_types.normal')}}">

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('login') }}">
                                    {{ __('Back to Login') }}
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
