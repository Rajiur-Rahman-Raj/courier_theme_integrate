<!-- Nav section start -->
<nav class="navbar fixed-top navbar-expand-lg">
	<div class="container">
		<a class="navbar-brand logo" href="javascript:void(0)"><img
				src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}" alt=""></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
				aria-controls="offcanvasNavbar">
			<i class="fa-light fa-list"></i>
		</button>
		<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbar">
			<div class="offcanvas-header">
				<a class="navbar-brand" href="javascript:void(0)"><img class="logo"
																	   src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
																	   alt=""></a>
				<button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
						class="fa-light fa-arrow-right"></i></button>
			</div>

			@php
				$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
				$lastUriSegment = array_pop($uriSegments);
			@endphp

			<div class="offcanvas-body align-items-center justify-content-between">
				<ul class="navbar-nav m-auto">
					<li class="nav-item">
						<a class="nav-link @if($lastUriSegment == '') active @endif" aria-current="page"
						   href="{{ route('home') }}">@lang('Home')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if($lastUriSegment == 'about') active @endif"
						   href="{{ route('about') }}">@lang('About')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if($lastUriSegment == 'service') active @endif"
						   href="{{ route('service') }}">@lang('Service')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if($lastUriSegment == 'package-cost') active @endif"
						   href="{{ route('packagingCost') }}">@lang('Packaging')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if($lastUriSegment == 'tracking') active @endif"
						   href="{{ route('tracking') }}">@lang('Tracking')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link"
						   href="{{ route('shippingCalculator.operatorCountry') }}" {{ ($lastUriSegment == 'operator-country' || $lastUriSegment == 'internationally' ? 'active' : '')  }}>@lang('Calculator')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
					</li>

				</ul>
			</div>
		</div>
		<div class="nav-right">
			<ul class="custom-nav">
				<!-- <li class="nav-item">
					<div class="language-box">
						<i class="fa-regular fa-coins"></i>
						<select class="form-select" aria-label="Default select example">
							<option selected>USD</option>
							<option value="1">EUR</option>
							<option value="2">bdt</option>
						</select>
					</div>
				</li> -->
				@guest
					<li class="nav-item">
						<a class="nav-link login-btn" href="{{ route('login') }}">
							<i class="login-icon fa-light fa-right-to-bracket d-md-none"></i><span
								class="d-none d-md-block">@lang('login')</span></a>
					</li>
				@else
					<li class="nav-item">
						<div class="profile-box">
							<div class="profile">
								<img src="{{ asset($themeTrue.'img/Person/1.jpg') }}" class="img-fluid" alt="">
							</div>
							<ul class="user-dropdown">
								<li>
									<a href="user-panel.html"> <i class="fal fa-user"></i> View Profile </a>
								</li>
								<li>
									<a href="support-ticket.html"> <i class="fal fa-user-headset"></i> Support </a>
								</li>
								<li>
									<a href="edit-profile.html"> <i class="fal fa-user-cog"></i> Account Settings
									</a>
								</li>
								<li>
									<a href=""> <i class="fal fa-sign-out-alt"></i> Sign Out </a>
								</li>
							</ul>
						</div>
					</li>
				@endguest

			</ul>
		</div>
	</div>
</nav>
<!-- Nav section end -->

<!-- Bottom Mobile Tab nav section start -->
<ul class="nav bottom-nav fixed-bottom d-lg-none">
	<li class="nav-item">
		<a class="nav-link" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvasNavbar"
		   href="#offcanvasNavbar" aria-current="page"><i class="fa-light fa-list"></i></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#"><i class="fa-light fa-planet-ringed"></i></a>
	</li>
	<li class="nav-item">
		<a class="nav-link active"><i class="fa-light fa-house"></i></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#"><i class="fa-light fa-address-book"></i></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#"><i class="fa-light fa-user"></i></a>
	</li>
</ul>
<!-- Bottom Mobile Tab nav section end -->
