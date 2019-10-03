<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('home') }}">{{ config('app.shortname', 'LV') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">{{ __('Dashboard') }}</li>
      <li><a class="nav-link" href="{{ route('home') }}"><i class="fa fa-home"></i> <span>{{ __('Home') }}</span></a></li>
      <li class="menu-header">{{ __('Menu') }}</li>
      <!-- School -->
      <li><a class="nav-link" href="{{ route('school.index') }}"><i class="fa fa-university"></i> <span>{{ __('School') }}</span></a></li>
      <!-- Teacher -->
      <li class="{{ (request()->is('teacher')||request()->is('teacher/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('teacher.index') }}">
          <i class="fa fa-user-check"></i> <span>{{ __('Teacher') }}</span>
        </a>
      </li>
      <!-- Student -->
      <li><a class="nav-link" href="{{ route('class.index') }}"><i class="fa fa-user-tie"></i> <span>{{ __('Student') }}</span></a></li>
      <!-- Activity -->
      <li><a class="nav-link" href="{{ route('activity.index') }}"><i class="fas fa-city"></i> <span>{{ __('Activity Submission') }}</span></a></li>
      <!-- Subsidy -->
      <li><a class="nav-link" href="{{ route('subsidy.index') }}"><i class="fa fa-briefcase"></i> <span>{{ __('Submission of Assistance') }}</span></a></li>
      <!-- Training -->
      <li><a class="nav-link" href="{{ route('training.index') }}"><i class="fas fa-business-time"></i> <span>{{ __('Training') }}</span></a></li>
      <!-- Exam Readiness -->
      <li><a class="nav-link" href="{{ route('exam.readiness.index') }}"><i class="far fa-list-alt"></i> <span>{{ __('Exam Readiness') }}</span></a></li>
      <!-- Confirmation -->
      <li class="dropdown {{ (request()->is('payment')||request()->is('payment/*')?'active':'') }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-receipt"></i> <span>{{ __('Confirmation') }}</span></a>
        <ul class="dropdown-menu">
          <li class="{{ (request()->is('attendance')||request()->is('attendance/*')?'active':'') }}"><a class="nav-link" href="{{ route('attendance.index') }}">{{ __('Attendance') }}</a></li>
          <li class="{{ (request()->is('payment')||request()->is('payment/*')?'active':'') }}"><a class="nav-link" href="{{ route('payment.index') }}">{{ __('Payment') }}</a></li>
        </ul>
      </li>
      <!-- Account -->
      <li class="menu-header">{{ __('User') }}</li>
      <li><a class="nav-link" href="{{ route('account.index') }}"><i class="fa fa-user"></i> <span>{{ __('Account') }}</span></a></li>
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