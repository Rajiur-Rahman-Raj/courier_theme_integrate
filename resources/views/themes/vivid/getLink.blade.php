@extends($theme.'layouts.app')
@section('title')
    @lang($title)
@endsection

@section('banner_heading')
	@lang($title)
@endsection

@section('content')

	<!-- Policy section start -->
	<section class="policy-section">
		<div class="container">
			<div class="row">
				<div class="policy-section-inner">
					<p>
						@lang(@$description)
					</p>
				</div>
			</div>
		</div>
	</section>
	<!-- Policy section end -->

@endsection
