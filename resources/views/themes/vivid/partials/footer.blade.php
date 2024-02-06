<!-- Footer Section start -->
<section class="footer-section">
	<div class="footer-shape">
		<img src="{{ asset($themeTrue.'img/background/footer-shape.png') }}" alt="">
	</div>
	<div class="container">
		<div class="row gy-4 gy-sm-5">
			<div class="col-lg-4 col-sm-6">
				<div class="footer-widget">
					<div class="widget-logo mb-30">
						<a href="{{ route('home') }}"><img class="logo"
														   src="{{ getFile(config('basic.default_file_driver'),config('basic.logo_image')) }}"
														   alt="@lang('logo')"></a>
					</div>
					<p>
						{{optional($contactUs->description)->about_company}}
					</p>

					@if(isset($contentDetails['social-links']))
						<div class="social-area mt-50">
							<ul class="d-flex">
								@foreach($contentDetails['social-links'] as $data)
									<li><a href="{{@$data->content->contentMedia->description->social_link}}"><i
												class="{{@$data->content->contentMedia->description->social_icon}}"></i></a>
									</li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
			</div>
			<div class="col-lg-2 col-sm-6">
				<div class="footer-widget">
					<h5 class="widget-title">@lang('Quick Links')</h5>
					<ul>
						<li><a class="widget-link"
							   href="{{ route('coverageArea', 'operator-country') }}">@lang('Coverage Area')</a></li>
						<li><a class="widget-link" href="{{ route('faq') }}">@lang('FAQ')</a></li>
						<li><a class="widget-link" href="{{ route('blog') }}">@lang('Blog')</a></li>
					</ul>
				</div>
			</div>
			@if(isset($contentDetails['extra-pages']))
				<div class="col-lg-3 col-sm-6 pt-sm-0 pt-3 ps-lg-5">
					<div class="footer-widget">
						<h5 class="widget-title">@lang('Company Policy')</h5>
						<ul>
							@foreach($contentDetails['extra-pages'] as $data)
								<li><a class="widget-link" href="{{route('getLink', [slug($data->description->title),$data->content_id])}}">@lang(optional($data->description)->title)</a></li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif
			<div class="col-lg-3 col-sm-6 pt-sm-0 pt-3">
				<div class="footer-widget">
					<h5 class="widget-title">Newsletter</h5>
					<p>Subscribe To Our Mailing List
						And Stay Up To Date</p>
					<form class="newsletter-form">
						<input type="text" class="form-control" placeholder="Your email">
						<button type="button" class="subscribe-btn"><i
								class="fa-regular fa-paper-plane"></i></button>
					</form>
				</div>
			</div>
		</div>
		<hr class="cmn-hr">
		<!-- Copyright-area-start -->
		<div class="copyright-area">
			<div class="row gy-4">
				<div class="col-sm-6">
					<p>Copyright ©2023 <a class="highlight" href="index.html">Email Template</a> All Rights
						Reserved
					</p>
				</div>
				<div class="col-sm-6">
					<div class="language">
						<a href="" class="language">English</a>
						<a href="" class="language">Spanish</a>
						<a href="" class="language">Germany</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Copyright-area-end -->
	</div>
</section>
<!-- Footer Section end -->
