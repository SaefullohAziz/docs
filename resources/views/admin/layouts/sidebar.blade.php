<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('admin.home') }}">
        @if (setting('site_logo'))
          <img src="{{ asset('storage/' . setting('site_logo')) }}" class="img-fluid p-2" alt="{{ __('Site Logo') }}">
        @else
          {{ config('app.name', 'Laravel') }}
        @endif
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('admin.home') }}">{{ config('app.shortname', 'LV') }}</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">{{ __('Dashboard') }}</li>
      <!-- Dashboard -->
      <li class="{{ (request()->is('admin')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.home') }}">
          <i class="fa fa-home"></i> <span>{{ __('Home') }}</span>
        </a>
      </li>
      <li class="menu-header">{{ __('Menu') }}</li>
      <!-- School -->
      <li class="{{ (request()->is('admin/school')||request()->is('admin/school/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.school.index') }}">
          <i class="fa fa-university"></i> <span>{{ __('School') }}</span>
        </a>
      </li>
      <!-- Teacher -->
      <li class="{{ (request()->is('admin/teacher')||request()->is('admin/teacher/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.teacher.index') }}">
          <i class="fa fa-user-check"></i> <span>{{ __('Teacher') }}</span>
        </a>
      </li>
      <!-- Class: student -->
      <li class="{{ (request()->is('admin/class')||request()->is('admin/class/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.class.index') }}">
          <i class="fa fa-user-tie"></i> <span>{{ __('Student') }}</span>
        </a>
      </li>
      <!-- Activity Submission -->
      <li class="{{ (request()->is('admin/activity')||request()->is('admin/activity/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.activity.index') }}">
          <i class="fas fa-city"></i> <span>{{ __('Activity Submission') }}</span>
        </a>
      </li>
      <!-- Subsidy -->
      <li class="{{ (request()->is('admin/subsidy')||request()->is('admin/subsidy/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.subsidy.index') }}">
          <i class="fa fa-briefcase"></i> <span>{{ __('Submission of Assistance') }}</span>
        </a>
      </li>
      <!-- Training -->
      <li class="{{ (request()->is('admin/training')||request()->is('admin/training/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.training.index') }}">
          <i class="fas fa-business-time"></i> <span>{{ __('Training') }}</span>
        </a>
      </li>
      <!-- Exam: readiness -->
      <li class="{{ (request()->is('admin/exam/readiness')||request()->is('admin/exam/readiness/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.exam.readiness.index') }}">
          <i class="far fa-list-alt"></i> <span>{{ __('Exam Readiness') }}</span>
        </a>
      </li>
      <!-- Confirmation -->
      <li class="dropdown {{ (request()->is('admin/payment')||request()->is('admin/payment/*')||request()->is('admin/attendance')||request()->is('admin/attendance/*')?'active':'') }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-receipt"></i> <span>{{ __('Confirmation') }}</span></a>
        <ul class="dropdown-menu">
          <li class="{{ (request()->is('admin/attendance')||request()->is('admin/attendance/*')?'active':'') }}"><a class="nav-link" href="{{ route('admin.attendance.index') }}">{{ __('Attendance') }}</a></li>
          <li class="{{ (request()->is('admin/payment')||request()->is('admin/payment/*')?'active':'') }}"><a class="nav-link" href="{{ route('admin.payment.index') }}">{{ __('Payment') }}</a></li>
        </ul>
      </li>
      <!-- Certification -->
      <li>
        <a class="nav-link" href="http://certificate.axiooclassprogram.org" target="blank">
          <i class="far fa-list-alt"></i> <span>{{ __('Certification') }}</span>
        </a>
      </li>
      <!-- Update -->
      <li class="menu-header">{{ __('Update') }}</li>
      <li class="{{ (request()->is('admin/update')||request()->is('admin/update/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.update.index') }}">
          <i class="fas fa-clipboard-list"></i> <span>{{ __('Update Data') }}</span>
        </a>
      </li>
      <!-- Setting -->
      <li class="menu-header">{{ __('Setting') }}</li>
      <li class="{{ (request()->is('admin/setting')||request()->is('admin/setting/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.setting.index') }}">
          <i class="fa fa-cogs"></i> <span>{{ __('Setting') }}</span>
        </a>
      </li>
      <!-- Account -->
      <li class="menu-header">{{ __('User') }}</li>
      <li class="{{ (request()->is('admin/account')||request()->is('admin/account/*')?'active':'') }}">
        <a class="nav-link" href="{{ route('admin.account.index') }}">
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