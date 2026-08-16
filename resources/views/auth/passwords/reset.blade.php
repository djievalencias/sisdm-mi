@extends('auth.layouts.app')

@section('content')
    <div class="login-box">
        <div class="si-login-brand">
            <img src="{{ asset('assets/img/logo-full.png') }}" alt="Mebel International" class="si-login-logo">
            <div>
                <p class="si-login-title">{{ __('HRIS') }}</p>
                <p class="si-login-sub">{{ __('Human Resource Information System') }}</p>
            </div>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('You are one step away from your new password.') }}</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="input-group mb-3">
                        <input id="email" type="email" placeholder="E-Mail" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input id="password" type="password" placeholder="{{ __('New password') }}"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input id="password-confirm" type="password" placeholder="{{ __('Confirm password') }}" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Change password') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="{{ route('login') }}">{{ __('Back to sign in') }}</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
<!-- /.reset-password-box -->
@endsection