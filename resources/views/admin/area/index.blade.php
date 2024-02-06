@extends('admin.layouts.master')
@section('page_title', $title)
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('All Area List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Area List')</div>
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
													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('Country')" name="country"
																   value="{{ old('country',request()->country) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('State')" name="state"
																   value="{{ old('state',request()->state) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('City')" name="city"
																   value="{{ old('state',request()->city) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('Area')" name="name"
																   value="{{ old('name',request()->name) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-12">
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
											<h6 class="m-0 font-weight-bold text-primary">@lang('Area List')</h6>
											@if(adminAccessRoute(config('permissionList.Manage_Locations.Area_List.permission.add')))
												<button class="btn btn-sm btn-outline-primary" data-target="#add-modal"
														data-toggle="modal"><i
														class="fas fa-plus-circle"></i> @lang('Add Area')</button>
											@endif
										</div>

										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Country')</th>
														<th scope="col">@lang('State')</th>
														<th scope="col">@lang('City')</th>
														<th scope="col">@lang('Area')</th>
														<th scope="col">@lang('Action')</th>
													</tr>
													</thead>

													<tbody>
													@forelse($allAreas as $key => $area)
														<tr>
															<td data-label="@lang('Country')">
																@lang(optional($area->country)->name)
															</td>

															<td data-label="@lang('State')">
																@lang(optional($area->state)->name)
															</td>

															<td data-label="@lang('City')">
																@lang(optional($area->city)->name)
															</td>

															<td data-label="@lang('Area')">
																<a href="{{ route('areaList', ['show-area-list', optional($area->city)->id]) }}"
																   class="text-decoration-underline">
																	<span
																		class="badge badge-light">{{ optional($area->city)->getTotalArea() }}</span>
																</a>

															</td>

															<td data-label="@lang('Action')">
																<a href="{{ route('areaList', ['show-area-list', optional($area->city)->id]) }}"
																   class="btn btn-outline-primary rounded-circle btn-sm"
																   data-toggle="tooltip"
																   data-original-title="@lang('Show')"
																   title="@lang('Show')"><i class="fa fa-eye"
																							aria-hidden="true"></i>
																</a>
															</td>
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img"
																	 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
																	 alt="">
																<p class="text-center no-data-found-text">@lang('No Area Found')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $allAreas->links() }}</div>
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


	{{-- Add City Modal --}}
	<div id="add-modal" class="modal fade" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add Area')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('areaStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Country')</label>
							<select name="country_id"
									class="form-control @error('country_id') is-invalid @enderror selectedCountry select2">
								<option value="" disabled selected>@lang('Select Country')</option>
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
									class="form-control @error('state_id') is-invalid @enderror selectedState select2">

							</select>
							<div class="invalid-feedback">
								@error('state_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="city_id">@lang('Select City')</label>
							<select name="city_id"
									class="form-control @error('city_id') is-invalid @enderror selectedCity select2">

							</select>
							<div class="invalid-feedback">
								@error('city_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Area Name')</label>
							<input
								type="text"
								class="form-control @error('name') is-invalid @enderror" name="name"
								placeholder="@lang('Name')" required/>
							<div class="invalid-feedback">
								@error('name') @lang($message) @enderror
							</div>
						</div>

						<div class="col-md-12 my-3">
							<label for="">@lang('Status') </label>
							<div class="selectgroup w-100">
								<label class="selectgroup-item">
									<input type="radio" name="status" value="0" class="selectgroup-input">
									<span class="selectgroup-button">@lang('OFF')</span>
								</label>
								<label class="selectgroup-item">
									<input type="radio" name="status" value="1" class="selectgroup-input" checked>
									<span class="selectgroup-button">@lang('ON')</span>
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('save')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	@include('partials.locationJs')


	@if($errors->has('country_id') || $errors->has('state_id') || $errors->has('name'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("add-modal"), {});
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

@endsection
