<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('admin.home') }}">{{ config('app.name', 'Laravel') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('admin.home') }}">{{ config('app.shortname', 'LV') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">{{ __('Dashboard') }}</li>
      <li><a class="nav-link" href="{{ route('admin.home') }}"><i class="fa fa-home"></i> <span>{{ __('Home') }}</span></a></li>
      <li class="menu-header">{{ __('Menu') }}</li>
      <li><a class="nav-link" href="{{ route('admin.school.index') }}"><i class="fa fa-university"></i> <span>{{ __('School') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.class.index') }}"><i class="fa fa-user-tie"></i> <span>{{ __('Student') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.activity.index') }}"><i class="fas fa-city"></i> <span>{{ __('Activity Submission') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.subsidy.index') }}"><i class="fa fa-briefcase"></i> <span>{{ __('Submission of Assistance') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.training.index') }}"><i class="fas fa-business-time"></i> <span>{{ __('Training') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.exam.readiness.index') }}"><i class="far fa-list-alt"></i> <span>{{ __('Exam Readiness') }}</span></a></li>
      <li><a class="nav-link" href="{{ route('admin.payment.index') }}"><i class="fas fa-receipt"></i> <span>{{ __('Payment Confirmation') }}</span></a></li>
      <li class="menu-header">{{ __('User') }}</li>
      <li><a class="nav-link" href="{{ route('admin.account.index') }}"><i class="fa fa-user"></i> <span>{{ __('Account') }}</span></a></li>
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