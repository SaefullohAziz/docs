@include('layouts.header')

<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="login-brand">
            {{ config('app.name', 'Laravel') }}
          </div>

          <div class="card card-primary">
            <div class="card-header"><h4>{{ __('Login') }}</h4></div>

            <div class="card-body">
              <form method="POST" action="{{ route('login') }}">

                @csrf

                <div class="form-group">
                  <label for="username">{{ __('Username') }} / {{ __('E-Mail') }}</label>
                  <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" tabindex="1" value="{{ old('username') }}" required autofocus>

                  @if ($errors->has('username'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="form-group">
                  <div class="d-block">
                    <label for="password" class="control-label">{{ __('Password') }}</label>
                    <div class="float-right">
                      @if (Route::has('password.request'))
                      <a href="{{ route('password.request') }}" class="text-small">
                        {{ __('Forgot Your Password?') }}
                      </a>
                      @endif
                    </div>
                  </div>
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" tabindex="2" required>

                  @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="remember-me">{{ __('Remember Me') }}</label>
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    {{ __('Login') }}
                  </button>
                </div>
              </form>

            </div>
          </div>
          <div class="simple-footer">
            Copyright &copy; {{ config('app.name', 'Laravel') }} &middot; 2016 - {{ date('Y') }}
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('layouts.footer')