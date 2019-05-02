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
            <div class="card-header"><h4>{{ __('Reset Password') }}</h4></div>

            <div class="card-body">

              @if (session('status'))
                <div class="alert alert-success" role="alert">
                  {{ session('status') }}
                </div>
              @endif

              <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                  <div class="d-block">
                    <label for="email">{{ __('E-Mail Address') }}</label>
                    <div class="float-right">
                      @if (Route::has('login'))
                      <a href="{{ route('login') }}" class="text-small">
                        {{ __('Login') }}
                      </a>
                      @endif
                    </div>
                  </div>
                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" tabindex="1" value="{{ old('email') }}" required autofocus>

                  @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    {{ __('Send Password Reset Link') }}
                  </button>
                </div>
              </form>

            </div>
          </div>
          <div class="simple-footer">
            Copyright &copy; {{ config('app.name', 'Laravel') }} 2019
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@include('layouts.footer')