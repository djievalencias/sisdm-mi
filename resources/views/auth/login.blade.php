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

    <div class="card">
        <div class="card-body login-card-body">
            @php
                // Failed logins put trans('auth.failed') on the email field;
                // mark the password red too, since either value may be wrong.
                $authFailed = $errors->first('email') === __('auth.failed');
            @endphp
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input id="email" type="email" placeholder="you@company.com"
                        class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" placeholder="••••••••"
                            class="form-control {{ $errors->has('password') || $authFailed ? 'is-invalid' : '' }}" name="password" required
                            autocomplete="current-password">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword"
                                aria-label="{{ __('Show password') }}" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="icheck-primary mb-0">
                        <input type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" style="font-weight: 500;">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" style="font-size: 13px;">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>
    </div>

    <p class="si-login-foot">Employees clock in from the mobile app.<br>This portal is for HR administrators.</p>
</div>

@if (session('password_changed'))
    <!-- Password-changed confirmation popup -->
    <div class="modal fade" id="passwordChangedModal" tabindex="-1" aria-labelledby="passwordChangedTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body pt-4">
                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 44px;"></i>
                    <h5 id="passwordChangedTitle" class="mb-2">{{ __('Password changed successfully') }}</h5>
                    <p class="text-muted mb-0" style="font-size: 13px;">{{ __('You can now sign in with your new password. A confirmation email has been sent to you.') }}</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-primary px-4" data-dismiss="modal">{{ __('OK') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var input = document.getElementById('password');
        var icon = this.querySelector('i');
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !show);
        icon.classList.toggle('fa-eye-slash', show);
    });

    @if (session('password_changed'))
        // jQuery loads after this inline block (layout scripts sit at the end
        // of <body>), so wait for the full page load before opening the modal.
        window.addEventListener('load', function () {
            window.jQuery('#passwordChangedModal').modal('show');
        });
    @endif
</script>
@endsection
