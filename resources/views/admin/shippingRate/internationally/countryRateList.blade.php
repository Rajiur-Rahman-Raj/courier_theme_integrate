@extends('admin.layouts.master')
@section('page_title')
	@lang('Internationally Shipping Rate List')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1> @lang('Internationally Shipping Rate List') </h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item">@lang("Shipping Rates")</div>
					<div class="breadcrumb-item">@lang('Internationally')</div>
				</div>
			</div>
		</section>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h4>@lang('All Shipping Rate')</h4>
							@if(adminAccessRoute(config('permissionList.Shipping_Rates.Internationally_Rate.permission.add')))
								<a href="{{route('createShippingRateInternationally')}}"
								   class="btn btn-sm btn-outline-primary add"><i
										class="fas fa-plus-circle"></i> @lang('Add Shipping Rate')</a>
							@endif
						</div>

						<div class="card-body">
							<div class="switcher">
								<a href="{{ route('internationallyRate', 'country') }}">
									<button
										class="@if(lastUriSegment() == 'country') active @endif">@lang('Country List')</button>
								</a>
								<a href="{{ route('internationallyRate', 'state') }}">
									<button
										class="@if(lastUriSegment() == 'state') active @endif">@lang('State List')</button>
								</a>
								<a href="{{ route('internationallyRate', 'city') }}">
									<button
										class="@if(lastUriSegment() == 'city') active @endif"> @lang('City List')</button>
								</a>
							</div>

							{{-- Table --}}
							<div class="row justify-content-md-center mt-4">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang($title)</h6>
										</div>

										<div class="card-body">
											@include('errors.error')
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Parcel Type')</th>
														<th scope="col">@lang('Shipping Country')</th>
														<th scope="col">@lang('Action')</th>
													</tr>
													</thead>

													<tbody>
													@forelse($shippingRateList as $key => $countryList)
														<tr>
															<td data-label="Parcel Type"> @lang(optional($countryList->parcelType)->parcel_type) </td>
															<td data-label="@lang('Shipping Country')">
																<a href="{{ route('internationallyShowRate', ['country-list', $countryList->parcel_type_id]) }}"
																   class="text-decoration-underline">
																	<span class="badge badge-light">{{ $countryList->getTotalCountry($countryList->parcel_type_id) }}</span>
																</a>
															</td>

															<td data-label="@lang('Action')">
																<a href="{{ route('internationallyShowRate', ['country-list', $countryList->parcel_type_id]) }}"
																   class="btn btn-outline-primary btn-sm rounded-circle"
																   data-toggle="tooltip" data-placement="top" title="@lang('Show')"><i class="fa fa-eye"
																							aria-hidden="true"></i>
																</a>
															</td>
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No Shipping Rates Found')</p>
															</td>
														</tr>
													@endforelse

													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $shippingRateList->links() }}</div>
										</div>
									</div>
								</div>
							</div>
							{{-- Table --}}
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
