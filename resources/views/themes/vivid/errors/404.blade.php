@extends($theme.'layouts.app')
@section('title','404')

@section('banner_heading')
	@lang('404')
@endsection

@section('content')
<section class="not-found-page p-0">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12 text-center">
				<div class="image_404">
					<img src="{{ asset($themeTrue.'images/Courier.png') }}" alt="not found">
					<div class="content_404">
						<h4 class="content_404_title"> @lang('The page you’re looking for can’t be found.')</h4>
						<p class="content_404_subtitle">@lang('Please check if your spelling is correct and try again')</p>
						<a class="cmn_btn mt-3" href="{{ route('home') }}">@lang('GO TO HOME')</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
