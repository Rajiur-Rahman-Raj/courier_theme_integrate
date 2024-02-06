<!-- footer_start -->
<section class="footer_area">
	<div class="container">
		<div class="row gy-4 gy-sm-5">
			@if(isset($contactUs))
				<div class="col-lg-4 col-sm-6">
					<div class="footer_widget">
						<div class="widget_logo mb-30">
							<a href="{{ route('home') }}" class="site_logo"><img
									src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
									alt="@lang('logo')"></a>
						</div>
						<p>{{optional($contactUs->description)->about_company}}</p>

						@if(isset($contentDetails['social-links']))
							<div class="social_area mt-50">
								<ul class="d-flex">
									@foreach($contentDetails['social-links'] as $data)
										<li>
											<a href="{{@$data->content->contentMedia->description->social_link}}">
												<i class="{{@$data->content->contentMedia->description->social_icon}}"></i>
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						@endif
					</div>
				</div>
			@endif

			<div class="col-lg-2 col-sm-6">
				<div class="footer_widget">
					<h5>@lang('Quick Links')</h5>
					<ul>
						<li><a href="{{ route('coverageArea', 'operator-country') }}">@lang('Coverage Area')</a></li>
						<li><a href="{{ route('faq') }}">@lang('FAQ')</a></li>
						<li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
						@if(isset($contentDetails['extra-pages']))
							@foreach($contentDetails['extra-pages'] as $data)
								<li>
									<a href="{{route('getLink', [slug($data->description->title),$data->content_id])}}">@lang(optional($data->description)->title)</a>
								</li>
							@endforeach
						@endif
					</ul>
				</div>
			</div>
			@if(isset($contactUs))
				<div class="col-lg-3 col-sm-6 pt-sm-0 pt-3 ps-lg-5">
					<div class="footer_widget">
						<h5>@lang('Contact us')</h5>
						<ul>
							<li><i class="fal fa-map-marker-alt"></i>
								<span>{{optional($contactUs->description)->address}}</span></li>
							<li><i class="fal fa-envelope"></i>
								<span>{{optional($contactUs->description)->email}}</span></li>
							<li><i class="fal fa-phone-alt"></i>
								<span>{{optional($contactUs->description)->phone}}</span></li>
						</ul>
					</div>
				</div>
			@endif

			<div class="col-lg-3 col-sm-6 pt-sm-0 pt-3">
				<div class="footer_widget">
					<h5>@lang('Newsletter')</h5>
					<form action="{{ route('subscribe') }}" method="post">
						@csrf
						<div class="col">
							<input type="email" class="form-control" name="email"
								   placeholder="@lang('enter email')" id="inputEmail4">
							<button type="submit" class="cmn_btn w-100">@lang('Subscribe')</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</section>
<!-- footer_end -->

<!-- copy_right_area_start -->

<div class="copy_right_area">
	<div class="container">
		<div class="row gy-4 ">
			<div class="col-sm-6 text-sm-start text-center">
				<p>@lang('All rights reserved') © {{date('Y')}} @lang('by') <a
						href="{{route('home')}}">@lang($basic->site_title)</a></p>
			</div>
			<div class="col-sm-6">
				<div class="language text-sm-end text-center">
					@forelse($languages as $item)
						<a href="{{route('language',$item->short_name)}}"
						   class="language {{ $item->short_name  == session()->get('lang') ? 'language_active' : ''}}">@lang($item->name)</a>
					@empty
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
<!-- copy_right_area_end -->


