@if(isset($templates['about-us'][0]) && $aboutUs = $templates['about-us'][0])
	<section class="about_area">
		<div class="container">
			<div class="row">
				<div class="col-xl-5 col-lg-6 col-md-7">
					<div class="section_header pt-100">
						<div class="section_subtitle">@lang($aboutUs['description']->slogan)</div>
						<h2>@lang($aboutUs['description']->title)</h2>

					</div>
					<div class="text_area">
						<p class="para_text">@lang($aboutUs['description']->sub_title)</p>
					</div>
					<div class="text_bottom d-flex justify-content-lg-around justify-content-sm-between justify-content-around mt-50">
						<div class="cmn_box2 d-flex">
							<div class="image_area">
								<i class="fal fa-smile"></i>
							</div>
							<div class="text_area">
								<h3 class="mb-0">{{ $aboutUs['description']->happy_clients }}</h3>
								<h6>@lang('Happy Clients')</h6>
							</div>
						</div>
						<div class="cmn_box2 d-flex">
							<div class="image_area">
								<i class="fal fa-shipping-fast"></i>
							</div>
							<div class="text_area">
								<h3 class="mb-0">{{ $aboutUs['description']->total_shipments }}</h3>
								<h6>@lang('Total Shipments')</h6>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-5 position-relative ms-auto">
					<div class="section_right position-absolute">
						<div class="image_area img1">
							<img src="{{getFile(optional($aboutUs->media)->driver,$aboutUs->templateMedia()->image1)}}" alt="@lang('about_image')">
						</div>
						<div class="image_area img2">
							<img src="{{getFile(optional($aboutUs->media)->driver,$aboutUs->templateMedia()->image2)}}" alt="@lang('about_image')">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endif


