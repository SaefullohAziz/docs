@include('layouts.header')

<div id="app">
  <div class="main-wrapper main-wrapper-1">
    @auth('web')
      @include('layouts.navbar')

      @include('layouts.sidebar')
    @endauth

    @auth('admin')
      @include('admin.layouts.navbar')

      @include('admin.layouts.sidebar')
    @endauth

    <!-- Main Content -->
    <div class="main-content">
      <section class="section">
        <div class="section-header">
          <h1>{{ $title }}</h1>
          <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></div>
            @if ( ! empty($breadcrumbs))
              @foreach ($breadcrumbs as $url => $name)
                @if ($loop->last)
                  <div class="breadcrumb-item">{{ $name }}</div>
                  @break
                @endif
                <div class="breadcrumb-item"><a href="{{ $url }}">{{ $name }}</a></div>
              @endforeach
            @endif
          </div>
        </div>

        <div class="section-body">
          @isset($subtitle)
            <h2 class="section-title">
              {{ $subtitle }}
            </h2>
          @endisset
          @isset($description)
            <p class="section-lead">
              {{ $description }}
            </p>
          @endisset
          @yield('content')
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="footer-left">
        {{ __('Copyright') }} &copy; 2019
      </div>
      <div class="footer-right">

      </div>
    </footer>
  </div>
</div>

@include('layouts.footer')