@if(isset($contentDetails['feature'][0]) && $featureContents = $contentDetails['feature'][0])
	<!-- feature_start -->
	<section class="feature_area">
		<div class="container">
			@if(isset($contentDetails['feature']) && $featureContents = $contentDetails['feature'])
				<div class="row gy-5 justify-content-center">
					@foreach($featureContents as $featureContent)

						<div class="col-md-4 col-sm-6">
							<div class="cmn_box box1 text-center">
								<div class="image_area mb-30 mx-auto">
									<img src="{{ getFile(optional($featureContent->content->contentMedia)->driver, optional($featureContent->content->contentMedia->description)->image) }}" alt="@lang('feature image')">
								</div>
								<h4>@lang(optional( $featureContent->description)->title)</h4>
								<p class="w-75 mx-auto">{!! __(optional($featureContent->description)->short_description) !!}
								</p>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</section>
	<!-- feature_end -->
@endif
