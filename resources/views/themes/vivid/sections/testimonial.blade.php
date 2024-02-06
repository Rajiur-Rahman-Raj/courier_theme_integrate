<!-- testimonial_start -->
<section class="testimonial_area">
	<div class="container-fluid">
		<div class="row align-items-center">
			@if(isset($templates['testimonial'][0]) && $tesimonial = $templates['testimonial'][0])
				<div class="col-md-5 ps-0">
					<div class="testimonial_inner">
						<div class="section_header">
							<div class="section_subtitle">{{ optional($tesimonial->description)->slogan }}</div>
							<h2>{{ optional($tesimonial->description)->title }}</h2>
						</div>
					</div>
				</div>
			@endif

			@if(isset($contentDetails['testimonial']) && $testimonials = $contentDetails['testimonial'])
				<div class="col-md-7">
					<div class="owl-carousel owl-theme testimonial_carousel">
						@foreach($testimonials as $key => $tesimonial)
							<div class="item">
								<div class="testimonial_box box1 custom_zindex shadow2">
									<div class="quote_area"><i class="fas fa-quote-left"></i></div>
									<div class="star">
										<ul class="d-flex">
											@for($i = 1; $i <= optional($tesimonial->description)->rating; $i++)
												<li><i class="fas fa-star"></i></li>
											@endfor
										</ul>
									</div>
									<div class="text_area mt-25">
										<p>@lang(optional($tesimonial->description)->feedback)</p>

									</div>
									<div class="profile d-flex mt-30">
										<div class="image_area">
											<img
												src="{{ getFile(optional($tesimonial->content->contentMedia)->driver,optional($tesimonial->content->contentMedia->description)->image) }}"
												alt="">
										</div>
										<div class="text_area">
											<div class="pro_title">@lang(optional($tesimonial->description)->name)</div>
											<p>@lang(optional($tesimonial->description)->designation) </p>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endif
		</div>
	</div>
</section>
<!-- testimonial_end -->
