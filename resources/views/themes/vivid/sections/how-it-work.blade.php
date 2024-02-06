<!-- how_it_work_start -->
<section class="how_it_work_area">
	@if(isset($templates['how-it-work'][0]) && $howItWork = $templates['how-it-work'][0])
		<div class="how_it_work_top">
			<div class="container">
				<div class="row">
					<div class="section_header text-center">
						<div class="section_subtitle">@lang(optional($howItWork->description)->slogan)</div>
						<h2>@lang(optional($howItWork->description)->title)</h2>
						<p class="para_text m-auto">@lang(optional($howItWork->description)->short_description)</p>
					</div>
				</div>
			</div>
		</div>
	@endif

	@if(isset($contentDetails['how-it-work']) && $howItWork = $contentDetails['how-it-work'])
		<div class="how_it_work_bottom">
			<div class="container">
				<div class="row g-5">
					@foreach($howItWork as $key => $work)
						<div class="col-md-3 col-sm-6">
							<div class="cmn_box text-center">
								<div class="image_area mb-30 mx-auto">
									<img src="{{ getFile(optional($work->content->contentMedia)->driver,optional($work->content->contentMedia->description)->image) }}" alt="">
								</div>
								<h5 class="mt-20">@lang(optional($work->description)->title)</h5>
								<p>@lang(optional($work->description)->short_description)</p>
							</div>
						</div>
					@endforeach

				</div>
			</div>
		</div>
	@endif
</section>
<!-- how_delivery_work_end -->
