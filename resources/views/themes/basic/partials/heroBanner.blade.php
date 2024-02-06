@if(isset($templates['hero'][0]) && $hero = $templates['hero'][0])
	<!-- hero_start -->
	<div class="hero_area">
		<div class="container">
			@php
				$data = explode(' ',wordSplice(optional($hero->description)->title)['lastTwoWord']);
			@endphp
			<div class="row">
				<div class="col-lg-7">
					<h1>{{ wordSplice(optional($hero->description)->title)['withoutLastTwoWord'] }} <span
							class="highlight">{{ array_shift($data) }}</span> {{ wordSplice(optional($hero->description)->title)['lastWord'] }}
					</h1>
					<p class="para_text">{{ optional($hero->description)->sub_title }}</p>
					<div class="btn_area mt-50">
						<a class="cmn_btn"
						   href="{{ $hero->templateMedia()->button_link }}">{{ optional($hero->description)->button_name }}</a>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="image_area">
						<img class="shape1"
							 src="{{getFile(optional($hero->media)->driver,$hero->templateMedia()->image1)}}">
						<img class="shape2"
							 src="{{getFile(optional($hero->media)->driver,$hero->templateMedia()->image2)}}">
						<img class="shape3 "
							 src="{{getFile(optional($hero->media)->driver,$hero->templateMedia()->image3)}}">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- hero_end -->
@endif
