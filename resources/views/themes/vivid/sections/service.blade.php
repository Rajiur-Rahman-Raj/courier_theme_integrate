<!-- service_start -->
<section class="service_area pt-0">
	@if(isset($templates['services'][0]) && $service = $templates['services'][0])
		<div class="service_top">
			<div class="container">
				<div class="row">
					<div class="section_header text-center">
						<div class="section_subtitle">{{ optional($service->description)->slogan }}</div>
						<h2 class="cmn_title mx-auto">{{ optional($service->description)->title }}</h2>
						<p class="para_text m-auto">{{ optional($service->description)->short_description }}</p>
					</div>
				</div>
			</div>
		</div>
	@endif

	@if(isset($contentDetails['services']) && $serviceContents = $contentDetails['services'])
		<div class="service_bottom">
			<div class="container">
				<div class="row g-5">
					@foreach($serviceContents as $key => $service)
						<div class="col-lg-3 col-md-4 col-sm-6">
							<div class="cmn_box2 text-center">
								<div class="image_area">
									<img src="{{ getFile(optional($service->content->contentMedia)->driver,optional($service->content->contentMedia->description)->image) }}" alt="">
								</div>
								<h5 class="mt-20">@lang(optional( $service->description)->title)</h5>
								<p>{!! __(optional($service->description)->short_description) !!}</p>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	@endif
</section>
<!-- service_end -->
