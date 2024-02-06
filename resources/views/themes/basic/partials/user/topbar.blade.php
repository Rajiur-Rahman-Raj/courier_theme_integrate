<!-- navbar -->
<nav class="navbar navbar-expand-lg">
	<div class="container-fluid">
		<a class="navbar-brand" href="javascript:void(0)"> <img
				src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
				alt="{{config('basic.site_title')}}"/></a>

		<button class="sidebar-toggler" onclick="toggleSideMenu()">
			<i class="far fa-bars"></i>
		</button>
		<div class="search_area"></div>
		<!-- navbar text -->

		<span class="navbar-text" id="pushNotificationArea">
			 @auth
				@if(basicControl()->push_notification)
					@include($theme.'partials.pushNotify')
				@endif
			@endauth
			@include($theme.'partials.userDropdown')
		</span>
	</div>
</nav>
