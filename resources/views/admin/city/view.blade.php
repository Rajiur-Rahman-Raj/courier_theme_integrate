@extends('admin.layouts.master')
@section('page_title', sizeof($allCities) ? optional($allCities[0]->state)->name.' '.$title : $title)
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang(sizeof($allCities) ? optional($allCities[0]->state)->name.' '.$title : $title)</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div
						class="breadcrumb-item">@lang(sizeof($allCities) ? optional($allCities[0]->state)->name.' '.$title : $title)</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">
							<div class="row">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow-sm">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Search')</h6>
										</div>

										<div class="card-body">
											<form action="" method="get">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<input placeholder="@lang('City')" name="name"
																   value="{{ old('name',request()->name) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group search-currency-dropdown">
															<select name="status"
																	class="form-control form-control-sm select2">
																<option value="all">@lang('All Status')</option>
																<option
																	value="active" {{  request()->status == 'active' ? 'selected' : '' }}>@lang('Active')</option>
																<option
																	value="deactive" {{  request()->status == 'deactive' ? 'selected' : '' }}>@lang('Deactive')</option>
															</select>
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<button type="submit"
																	class="btn btn-primary btn-sm btn-block"><i
																	class="fas fa-search"></i> @lang('Search')</button>
														</div>
													</div>
												</div>

											</form>
										</div>
									</div>
								</div>
							</div>

							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('All List')</h6>

											<div class="dropdown mb-2 text-right">
												<button class="btn btn-sm btn-dark dropdown-toggle" type="button"
														id="dropdownMenuButton"
														data-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
													<span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
												</button>
												<div class="dropdown-menu drop-left-24"
													 aria-labelledby="dropdownMenuButton">
													<a class="dropdown-item text-success bulkCityEnable"
													   href="javascript:void(0)" data-toggle="modal"
													   data-target="#bulk-city-enable"><i
															class="fas fa-check mr-2"></i> @lang('Bulk Enable')</a>
													<a class="dropdown-item text-danger bulkCityDisable" href="javascript:void(0)"
													   data-toggle="modal"
													   data-target="#bulk-city-disable"><i
															class="fas fa-ban mr-2"></i> @lang('Bulk Disable')</a>
												</div>
												<a href="{{route('cityList', ['city-list'])}}"
												   class="btn btn-sm  btn-primary mr-2">
													<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
												</a>
											</div>

										</div>

										<div class="card-body">
											@include('errors.error')
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col" class="text-center">
															<input type="checkbox"
																   class="form-check-input check-all tic-check"
																   name="check-all"
																   id="check-all">
															<label for="check-all"></label>
														</th>
														<th scope="col">@lang('City')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.City_List.permission.edit'), config('permissionList.Manage_Locations.City_List.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allCities as $key => $city)
														<tr>
															<td class="text-center">
																<input type="checkbox" id="chk-{{ $city->id }}"
																	   class="form-check-input row-tic tic-check"
																	   name="check"
																	   value="{{$city->id}}"
																	   data-id="{{ $city->id }}">
																<label for="chk-{{ $city->id }}"></label>
															</td>
															<td data-label="@lang('City')">
																@lang($city->name)
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($city->status == 1)
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-success font-12"></i> @lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-danger font-12"></i> @lang('Deactive')
																	</span>

																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.City_List.permission.edit'), config('permissionList.Manage_Locations.City_List.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Manage_Locations.City_List.permission.edit')))
																		<button data-target="#editCityModal"
																				data-toggle="modal"
																				data-route="{{route('cityUpdate', $city->id)}}"
																				data-property="{{ $city }}"
																				data-countries="{{ $allCountires }}"
																				data-states="{{ $allStates }}"
																				class="btn btn-sm btn-outline-primary rounded-circle editCity">
																			<i class="fas fa-edit" data-toggle="tooltip"
																			   data-original-title="@lang('Edit')"></i>
																		</button>
																	@endif

																	@if($city->status == 1)
																		<a href="javascript:void(0)"
																		   data-target="#city-disable-modal"
																		   data-toggle="modal"
																		   data-route="{{ route('cityDisable',$city->id) }}"
																		   data-property="{{ $city }}"
																		   class="btn btn-sm rounded-circle btn-outline-danger ml-1 mb-1 cityDisable"><i
																				data-toggle="tooltip"
																				data-original-title="@lang('Disable')"
																				class="fas fa-ban"></i></a>
																	@else
																		<a href="javascript:void(0)"
																		   data-target="#city-enable-modal"
																		   data-toggle="modal"
																		   data-route="{{ route('cityEnable',$city->id) }}"
																		   data-property="{{ $city }}"
																		   class="btn btn-sm rounded-circle btn-outline-success ml-1 mb-1 cityEnable"><i
																				data-toggle="tooltip"
																				data-original-title="@lang('Enable')"
																				class="fas fa-check"></i></a>
																	@endif
																</td>
															@endif
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No found data')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $allCities->links() }}</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	{{-- Edit City Modal --}}
	<div id="editCityModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit City')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editCityForm">
					@csrf
					@method('put')
					<div class="modal-body">

						<div class="col-12 mt-3">
							<label for="">@lang('Select Country')</label>
							<select name="country_id"
									class="form-control @error('country_id') is-invalid @enderror selectedCountry"
									id="countryId">
								@foreach($allCountires as $country)
									<option value="{{ $country->id }}">@lang($country->name)</option>
								@endforeach
							</select>
							<div class="invalid-feedback">
								@error('country_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Select State')</label>
							<select name="state_id"
									class="form-control @error('state_id') is-invalid @enderror selectedState"
									id="stateId">
								@foreach($allStates as $state)
									<option value="{{ $state->id }}">@lang($state->name)</option>
								@endforeach
							</select>
							<div class="invalid-feedback">
								@error('state_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('City Name')</label>
							<input
								type="text"
								class="form-control cityName" name="name"
								placeholder="@lang('Name')" required/>
						</div>

						<div class="col-md-12 my-3">
							<label for="">@lang('Status') </label>
							<div class="selectgroup w-100">
								<label class="selectgroup-item">
									<input type="radio" name="status" value="0"
										   class="selectgroup-input status_disabled">
									<span class="selectgroup-button">@lang('OFF')</span>
								</label>
								<label class="selectgroup-item">
									<input type="radio" name="status" value="1"
										   class="selectgroup-input status_enabled">
									<span class="selectgroup-button">@lang('ON')</span>
								</label>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Update')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Disable City Modal --}}
	<div id="city-disable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="disableCityForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="city_name"></p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Enable State Modal --}}
	<div id="city-enable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="enableCityForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="city_name"></p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Bulk Enable Country Modal --}}
	<div id="bulk-city-enable" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>

				<div class="modal-body">
					<p>@lang("Are you sure to enable these cities?")</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post">
						@csrf
						<a href="javascript:void(0)" class="btn btn-primary enable-yes"><span>@lang('Yes')</span></a>
					</form>
				</div>
			</div>
		</div>
	</div>

	{{-- Bulk Disable Country Modal --}}
	<div id="bulk-city-disable" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>

				<div class="modal-body">
					<p>@lang("Are you sure to disable these cities?")</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post">
						@csrf
						<a href="javascript:void(0)" class="btn btn-primary disable-yes"><span>@lang('Yes')</span></a>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')

	@if($errors->has('country_id') || $errors->has('state_id') || $errors->has('name'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("editCityModal"), {});
			document.onreadystatechange = function () {
				myModal.show();
			};
		</script>
	@endif

	@if ($errors->any())
		@php
			$collection = collect($errors->all());
			$errors = $collection->unique();
		@endphp
		<script>
			"use strict";
			@foreach ($errors as $error)
			Notiflix.Notify.failure("{{ trans($error) }}");
			@endforeach
		</script>
	@endif

	<script>
		'use strict'

		$(document).ready(function () {
			$(document).on('click', '.editCity', function () {

				let dataRoute = $(this).data('route');
				$('#editCityForm').attr('action', dataRoute)

				let dataProperty = $(this).data('property');
				let countries = $(this).data('countries');
				let states = $(this).data('states');

				$('#countryId').val(dataProperty.country_id);
				$('#stateId').val(dataProperty.state_id);
				$('.cityName').val(dataProperty.name);

				let change = false;
				let stateId = dataProperty.state_id;

				getSeletedCountryState(dataProperty.country_id, change, stateId);

				$(dataProperty.status == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);

			});

			$('.selectedCountry').on('change', function () {
				let selectedValue = $(this).val();
				let change = true;
				getSeletedCountryState(selectedValue, change);
			})

			function getSeletedCountryState(value, change, stateId = null) {
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: '{{ route('getSeletedCountryState') }}',
					method: 'POST',
					data: {
						id: value,
					},
					success: function (response) {
						$('.selectedState').empty();
						let responseData = response;
						responseData.forEach(res => {
							$('.selectedState').append(`<option value="${res.id}">${res.name}</option>`)
						})
						if (change == true) {
							$('.selectedState').prepend(`<option value="" selected disabled>@lang('Select State')</option>`)
						}
						$('#stateId').val(stateId);
					},
					error: function (xhr, status, error) {
						console.log(error)
					}
				});
			}




			$(document).on("click", ".cityDisable", function () {
				let route = $(this).data('route');
				let dataProperty = $(this).data('property');
				$('.city_name').text(`Are you sure to disable city ${dataProperty.name}?`);
				$('#disableCityForm').attr('action', route);
			});

			$(document).on("click", ".cityEnable", function () {
				let route = $(this).data('route');
				let dataProperty = $(this).data('property');
				$('.city_name').text(`Are you sure to enable city ${dataProperty.name}?`);
				$('#enableCityForm').attr('action', route);
			});

			$(document).on('click', '#check-all', function () {
				$('input:checkbox').not(this).prop('checked', this.checked);
			});

			$(document).on('change', ".row-tic", function () {
				let length = $(".row-tic").length;
				let checkedLength = $(".row-tic:checked").length;
				if (length == checkedLength) {
					$('#check-all').prop('checked', true);
				} else {
					$('#check-all').prop('checked', false);
				}
			});


			//multiple Enabled
			$(document).on('click', '.enable-yes', function (e) {

				e.preventDefault();
				var allVals = [];
				$(".row-tic:checked").each(function () {
					allVals.push($(this).attr('data-id'));
				});
				var strIds = allVals;
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "{{ route('multiple-city-enabled') }}",
					data: {
						strIds: strIds,
					},
					datatType: 'json',
					type: "POST",
					success: function (data) {
						location.reload();
					},
				});
			});

			//multiple Disabled
			$(document).on('click', '.disable-yes', function (e) {
				e.preventDefault();
				var allVals = [];
				$(".row-tic:checked").each(function () {
					allVals.push($(this).attr('data-id'));
				});

				var strIds = allVals;

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: "{{ route('multiple-city-disabled') }}",
					data: {
						strIds: strIds,
					},
					datatType: 'json',
					type: "POST",
					success: function (data) {
						location.reload();
					},
				});
			});


		})
	</script>
@endsection
