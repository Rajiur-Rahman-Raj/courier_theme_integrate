<!-- blog_area_start -->
<section class="blog_area">
	<div class="container">
		@if(isset($templates['blog'][0]) && $blog = $templates['blog'][0])
			<div class="row">
				<div class="section_header mb-50 text-center">
					<div class="section_subtitle">@lang(optional($blog->description)->slogan)</div>
					<h2>@lang(optional($blog->description)->title)</h2>
				</div>
			</div>
		@endif

		@if (count($blogs) > 0)
			<div class="row justify-content-center g-lg-4 gy-5">
				@foreach ($blogs as $key => $blog)
					<div class="col-lg-4 col-sm-6">
						<div class="blog_box box1">
							<div class="thum_inner">
								<div class="image_area">
									<img src="{{ getFile($blog->driver, $blog->image) }}"
										 alt="{{config('basic.site_title')}}">
								</div>

								<div class="date">
									<p class="mb-0">{{ dateTime($blog->created_at, 'M') }}</p>
									<h3 class="pb-0">{{ dateTime($blog->created_at, 'd') }}</h3>
								</div>
							</div>
							<div class="text_area mt-30">
								<h5>
									<a href="{{route('blogDetails',[slug(optional($blog->details)->title), $blog->id])}}">{{ \Illuminate\Support\Str::limit(optional($blog->details)->title, 50) }}</a>
								</h5>
							</div>
							<div class="btn_area">
								<a href="{{route('blogDetails',[slug(optional($blog->details)->title), $blog->id])}}">@lang('READ MORE')
									<i class="fal fa-long-arrow-right"></i></a>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@endif
	</div>
</section>
<!-- blog_area_end -->

