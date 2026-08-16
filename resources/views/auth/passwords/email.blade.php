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
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <div class="card-body login-card-body">
                <p class="login-box-msg">{{ __('Forgot your password? Enter your email and we will send you a reset link.') }}</p>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Send password reset link') }}</button>
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
    <!-- /.login-box -->
@endsection