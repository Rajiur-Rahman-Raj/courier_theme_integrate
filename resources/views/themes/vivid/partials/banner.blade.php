<!-- Banner section start -->
@if(!request()->routeIs('home'))
	<div class="banner-section">
		<div class="banner-section-inner">
			<div class="container">
				<div class="row ">
					<div class="col">
						<div class="breadcrumb-area">
							<h3>@yield('banner_main_heading')</h3>
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-light fa-house"></i>
										@lang('Home')</a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">@yield('banner_heading')</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="banner-shape">
			<img src="{{ asset($themeTrue.'img/background/banner-bottom.png') }}" alt="">
		</div>
	</div>
@endif
<!-- Banner section end -->
