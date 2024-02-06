@extends($theme.'layouts.app')
@section('title',trans('Packaging Cost'))

@section('banner_main_heading')
	@lang('Packaging Cost')
@endsection

@section('banner_heading')
	@lang('Packaging Cost')
@endsection

@section('content')
	<!-- package-service-start -->
	<section class="packaging-cost-page package-service section-2">
		<div class="container">

			@if(isset($templates['packaging-cost'][0]) && $aboutUs = $templates['packaging-cost'][0])
				@php
					$data = explode(' ',wordSplice(optional($aboutUs->description)->title)['lastTwoWord']);
				@endphp

				<div class="package-heading">
					<h2>{{ wordSplice(optional($aboutUs->description)->title)['withoutLastTwoWord'] }}
						<span>{{ $data[0] }} {{ $data[1] }}</span></h2>
					<p>{{ optional($aboutUs->description)->sub_title }}</p>
				</div>
			@endif

				@forelse($packages as $key => $package)
					<div class="row">
						<div class="">
							<h5 class="{{ $key != 0 ? 'mt-5' : ''  }} mb-10">@lang($package->package_name)</h5>
						</div>
					</div>
					<div class="row g-4">

						@foreach($package->variant as $variant)

							<div class="col-lg-4 col-md-6">
								<div class="package-item">
									<div class="thumbs-area">
										<img src="{{ getFile($variant->driver, $variant->image) }}" alt="">
									</div>
									<div class="content-area">
										<h5>@lang($variant->variant)</h5>
										<ul class="package-list">
											<li>@lang($variant->variant) @lang('Cost')
													: {{ $basic->currency_symbol }}{{ optional($variant->packingService)->cost }}
											</li>
											<li>@lang('You can
													send') {{ optional($variant->packingService)->weight }} @lang('KG parcel')</li>
										</ul>
									</div>
								</div>
							</div>
						@endforeach
					</div>

				@empty

					<div class="row g-4 justify-content-center text-center">

						<div class="col-lg-4 col-md-6">
							<div class="package-item">
								<div class="custom-not-found2">
									<img src="{{ asset($themeTrue.'images/no_data.png') }}" alt="ListPlace" class="img-fluid">

									<h5>@lang('No Data Found')</h5>
								</div>
							</div>
						</div>
					</div>

				@endforelse

		</div>
	</section>
	<!-- package-service-start -->
@endsection
