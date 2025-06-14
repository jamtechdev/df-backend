@extends('layouts.guest')

<style>
    /* login page css  */

.log-in-dc {
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    width: 500px; 
    max-width: 90%; 
    box-shadow: rgb(68 98 245 / 5%) 0px 2px 8px 0px;
}

.decorative-bg, .decorative-top {
    position: absolute;
    width: 200px;
    height: 200px;
    background: #B3D9FF;
    border-radius: 50%;
    z-index: 1;
}

.decorative-top {
    top: -125px;
    left: -86px;
}

.decorative-bg:before, .decorative-top:before {
    content: '';
    position: absolute;
    top: 30px;
    left: 30px;
    width: 140px;
    height: 140px;
    background: #99C2FF;
    border-radius: 50%;
}

.decorative-bg {
    bottom: -160px;
    right: -40px;
}

.log-bg {
    background: #f8fbff;
}

button.btn-primary {
      width: 100%;
      padding: 12px;
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
}

button.btn-primary:hover {
    background: #4A9BFF;
}
</style>

@section('content')
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 log-bg">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 rounded-4 log-in-dc overflow-hidden">
                <div class="decorative-top"></div>
                    <div class="card-body position-relative p-4 text-center">

                        {{-- Logo and Project Name --}}
                        <div class="mb-4">
                            <img src="{{ asset('img/df.png') }}" alt="Logo" width="80" height="80" class="mb-2">
                            <h4 class="fw-bold rounded-2xl">{{ config('app.name', 'My Project') }}</h4>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">
                                    
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check text-start">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                <div class="decorative-bg"></div>
            </div>
        </div>
    </div>
@endsection
