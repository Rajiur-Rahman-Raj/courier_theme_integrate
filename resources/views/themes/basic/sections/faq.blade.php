<!-- faq_start -->
<section class="faq_area">
	<div class="container">
		@if(isset($templates['faq'][0]) && $faq = $templates['faq'][0])
			<div class="row">
				<div class="section_header text-center mb-50">
					<div class="section_subtitle">@lang(optional($faq->description)->slogan)</div>
					<h2>@lang(optional($faq->description)->title)</h2>
					<p class="para_text mx-auto">@lang(optional($faq->description)->short_description)</p>
				</div>
			</div>
		@endif

		@if(isset($contentDetails['faq']) && $faqs = $contentDetails['faq'])
			<div class="row">
				<div class="col-md-8 col-12 mx-auto">
					<div class="accordion" id="accordionExample">
						@foreach($faqs as $key => $faq)
							<div class="accordion-item">
								<h2 class="accordion-header" id="heading{{ $key }}">
									<button class="accordion-button {{ $key != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
											data-bs-target="#collapse{{ $key }}"
											aria-expanded="true{{ $key == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $key }}">
										@lang(optional($faq->description)->title)
									</button>
								</h2>
								<div id="collapse{{ $key }}" class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
									 aria-labelledby="heading{{ $key }}"
									 data-bs-parent="#accordionExample">
									<div class="accordion-body">
										<div class="table-responsive">
											<p>@lang(optional($faq->description)->short_description)</p>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		@endif
	</div>
</section>
<!-- faq_end -->
