<!-- why_choose_us_start -->
<section class="why_choose_us_area">
	<div class="container">

		@if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0])
			<div class="row">
				<div class="section_header mb-50">
					<div class="section_subtitle">{{ optional($whyChooseUs->description)->slogan }}</div>
					<h2 class="cmn_title">{{ optional($whyChooseUs->description)->title }}</h2>
				</div>
			</div>
		@endif
		<div class="row g-5 align-items-center">
			<div class="col-md-6">
				@if(isset($contentDetails['why-choose-us']) && $whyChooseUsContents = $contentDetails['why-choose-us'])
					@foreach($whyChooseUsContents as $key => $content)
						<div class="single_item  d-flex mb-50">
							<div class="number">{{ ++$key }}</div>
							<div class="feature_content">
								<h5>{{ optional($content->description)->title }}</h5>
								<p class="w-75">{!! optional($content->description)->short_description !!} </p>
							</div>
						</div>
					@endforeach
				@endif
			</div>
			@if(isset($templates['why-choose-us'][0]) && $whyChooseUs = $templates['why-choose-us'][0])
				<div class="col-md-6">
					<div class="image_area">
						<img src="{{getFile(optional($whyChooseUs->media)->driver,$whyChooseUs->templateMedia()->image)}}" alt="">
					</div>
				</div>
			@endif
		</div>
	</div>
</section>
<!-- why_choose_us_end -->
