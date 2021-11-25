@extends($templatePathAdmin.'Layout.main')

@section('main')
    {{-- <div class="col-md-10 text-center ml-auto mr-auto">
        <h3 class="mb-5">Log in to see how you can speed up your web development with out of the box CRUD for #User Management and more.</h3>
    </div> --}}
    <div class="container">
        <div class="col-lg-4 col-md-6 ml-auto mr-auto">
            <form class="form" method="post" action="{{ bc_route_admin('admin.login') }}">
                @csrf
                <div class="card card-login card-white">
                    <div class="card-header">
                        <img src="{{ asset('admin/black/img/card-primary.png') }}" alt="">
                        <h2 class="card-title">{{$title}}</h1>
                    </div>
                    <div class="card-body">
                        
                        <div class="input-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-email-85"></i>
                                </div>
                            </div>
                            <input type="text" name="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="{{ trans('admin.username') }}" value="{{ old('username') }}">
                            @include($templatePathAdmin.'Component.feedback', ['field' => 'username'])
                        </div>
                        <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" placeholder="{{ __('Password') }}" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                            @include($templatePathAdmin.'Component.feedback', ['field' => 'password'])
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="remember" {{ (old('remember')) ? 'checked' : '' }}>
                                {{ trans('admin.remember_me') }}
                                <span class="form-check-sign">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">{{ __('Get Started') }}</button>
                        {{-- <div class="pull-left">
                            <h6>
                                <a href="{{ route('admin.register') }}" class="link footer-link">{{ __('Create Account') }}</a>
                            </h6>
                        </div>
                        <div class="pull-right">
                            <h6>
                                <a href="{{ route('admin.password.request') }}" class="link footer-link">{{ __('Forgot password?') }}</a>
                            </h6>
                        </div> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
