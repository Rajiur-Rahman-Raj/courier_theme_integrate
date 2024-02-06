<style>
	.banner_area {
		background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url({{ getFile(config('basic.default_file_driver'),config('basic.breadcrumb')) }});
	}
</style>

@if(!request()->routeIs('home'))
	<!-- banner_area_start -->
	<div class="banner_area">
		<div class="container">
			<div class="row ">
				<div class="col">
					<div class="breadcrumb_area">
						<h3>@yield('banner_main_heading')</h3>
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
							<li class="breadcrumb-item active" aria-current="page">@yield('banner_heading')</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- banner_area_end -->
@endif
