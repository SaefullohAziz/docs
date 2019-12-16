<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('home') }}">
        @if (setting('site_logo'))
          <img src="{{ asset('storage/' . setting('site_logo')) }}" class="img-fluid p-2" alt="{{ __('Site Logo') }}">
        @else
          {{ config('app.name', 'Laravel') }}
        @endif
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('home') }}">{{ config('app.shortname', 'LV') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">{{ __('Dashboard') }}</li>
      <li class="{{ (request()->is('/')?'active':'') }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="fa fa-home"></i> <span>{{ __('Home') }}</span>
        </a>
      </li>
      <li class="menu-header">{{ __('Menu') }}</li>
      <!-- School -->
      <li class="{{ (request()->is('school')||request()->is('school/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('school.index') }}">
          <i class="fa fa-university"></i> <span>{{ __('School') }}</span>
        </a>
      </li>
      <!-- Teacher -->
      <li class="{{ (request()->is('teacher')||request()->is('teacher/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('teacher.index') }}">
          <i class="fa fa-user-check"></i> <span>{{ __('Teacher') }}</span>
        </a>
      </li>
      @if (auth()->user()->hasLevel(['C', 'B', 'A']))
      <!-- Student -->
      <li class="{{ (request()->is('student')||request()->is('student/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('class.index') }}">
          <i class="fa fa-user-tie"></i> <span>{{ __('Student') }}</span>
        </a>
      </li>
      @endif
      @if (auth()->user()->hasLevel(['C', 'B', 'A']))
      <!-- Activity -->
      <li class="{{ (request()->is('activity')||request()->is('activity/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('activity.index') }}">
          <i class="fas fa-city"></i> <span>{{ __('Activity Submission') }}</span>
        </a>
      </li>
      @endif
      @if (auth()->user()->hasLevel(['C', 'B', 'A']))
      <!-- Subsidy -->
      <li class="{{ (request()->is('subsidy')||request()->is('subsidy/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('subsidy.index') }}">
          <i class="fa fa-briefcase"></i> <span>{{ __('Submission of Assistance') }}</span>
        </a>
      </li>
      @endif
      @if (auth()->user()->hasLevel(['C', 'B', 'A']))
      <!-- Training -->
      <li class="{{ (request()->is('training')||request()->is('training/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('training.index') }}">
          <i class="fas fa-business-time"></i> <span>{{ __('Training') }}</span>
        </a>
      </li>
      @endif
      @if (auth()->user()->hasLevel(['C', 'B', 'A']))
      <!-- Exam Readiness -->
      <li class="{{ (request()->is('exam/readiness')||request()->is('exam/readiness/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('exam.readiness.index') }}">
          <i class="far fa-list-alt"></i> <span>{{ __('Exam Readiness') }}</span>
        </a>
      </li>
      @endif
      <!-- Confirmation -->
      <li class="dropdown {{ (request()->is('payment')||request()->is('payment/*')||request()->is('attendance')||request()->is('attendance/*')?'active':'') }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-receipt"></i> <span>{{ __('Confirmation') }}</span></a>
        <ul class="dropdown-menu">
          @if (auth()->user()->hasLevel('Dalam proses'))
          <li class="{{ (request()->is('attendance')||request()->is('attendance/*')?'active':'') }}"><a class="nav-link" href="{{ route('attendance.index') }}">{{ __('Attendance') }}</a></li>
          @endif
          @if (auth()->user()->hasLevel(['C', 'B', 'A']))
          <li class="{{ (request()->is('payment')||request()->is('payment/*')?'active':'') }}"><a class="nav-link" href="{{ route('payment.index') }}">{{ __('Payment') }}</a></li>
          @endif
        </ul>
      </li>
      <!-- Certification -->
      <li>
        <a class="nav-link" href="http://certificate.axiooclassprogram.org" target="blank">
          <i class="far fa-list-alt"></i> <span>{{ __('Certification') }}</span>
        </a>
      </li>
      <!-- Service -->
      <li>
        <a class="nav-link" href="https://service.mitraabadi.info/" target="blank">
          <i class="fas fa-laptop"></i> <span>{{ __('Service (RMA)') }}</span>
        </a>
      </li>
      <!-- Account -->
      <li class="menu-header">{{ __('User') }}</li>
      <li class="{{ (request()->is('account')||request()->is('account/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('account.index') }}">
          <i class="fa fa-user"></i> <span>{{ __('Account') }}</span>
        </a>
      </li>
      <li class="menu-header">{{ __('Logout') }}</li>
      <li>
        <a class="nav-link text-danger" href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> <span>{{ __('Logout') }}</span>
        </a>
      </li>
    </ul>
  </aside>
</div>