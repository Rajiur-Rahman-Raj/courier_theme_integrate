@extends($theme.'layouts.app')
@section('title',trans('Coverage Area'))

@section('banner_main_heading')
	@lang('Coverage Area')
@endsection

@section('banner_heading')
	@lang('Coverage Area')
@endsection

@section('content')
	<!-- courier-map-start -->
	<section class="courier-map section-2 p-5 mb-4">
		<div class="container">
			<div class="courier-top text-center">
				<h1 class="m-0">@lang('Coverage') <span>@lang('Area')</span></h1>
				<h3>@lang('We are currently available in') @if($type == 'operator-country') {{ count(optional(basicControl()->operatorCountry)->state()) }} @lang('Districts') @else {{ count($allCountries) }}  @lang('Countries') @endif </h3>
			</div>
			<div>
				<div class="description-tab mt-5">
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link {{ $type == 'operator-country' ? 'active' : '' }}" id="home-tab" href="{{ route('coverageArea', 'operator-country') }}"><i class="fas fa-truck"></i> {{ optional(basicControl()->operatorCountry)->name }}</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link {{ $type == 'internationally' ? 'active' : '' }}" id="contact-tab" href="{{ route('coverageArea', 'internationally') }}"><i class="fas fa-plane-departure"></i> @lang('internationally')</a>
						</li>
					</ul>
				</div>
				<div class="courier-map-list">
					<div class="tab-content" id="myTabContent">

						<div class="tab-pane fade {{ $type == 'operator-country' ? 'show active' : ''  }}" id="home-tab-pane" role="tabpanel"
							 aria-labelledby="home-tab" tabindex="0">
							<ul>
								@foreach(optional(basicControl()->operatorCountry)->state() as $state)
									<li>
										<a href="javascript:void(0)">@lang($state->name)</a>
									</li>
								@endforeach
							</ul>
						</div>

						<div class="tab-pane fade {{ $type == 'internationally' ? 'show active' : ''  }}" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab"
							 tabindex="0">
							<ul>
								@foreach($allCountries as $country)
								<li>
									<a href="javascript:void(0)">@lang($country->name)</a>
								</li>
								@endforeach
							</ul>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- courier-map-end -->
@endsection

