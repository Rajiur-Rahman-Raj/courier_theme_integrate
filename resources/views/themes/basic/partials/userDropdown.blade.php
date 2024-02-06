<!-- user panel -->
<div class="user-panel">
	<span class="profile">
		<img
			src="{{ getFile(optional(Auth::user()->profile)->driver,optional(Auth::user()->profile)->profile_picture) }}"
			class="img-fluid" alt="@lang('user profile')"/>
	</span>
	<ul class="user-dropdown shadow2">
		<li>
			<a href="{{route('user.dashboard')}}"> <i class="fal fa-border-all"></i> @lang(' Dashboard') </a>
		</li>
		<li>
			<a href="{{route('user.profile')}}"> <i class="fal fa-user"></i> @lang('My Profile') </a>
		</li>
		<li>
			<a href="{{ route('logout') }}"
			   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
				<i class="fal fa-sign-out-alt"></i> @lang('Log Out') </a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
				@csrf
			</form>
		</li>
	</ul>
</div>
