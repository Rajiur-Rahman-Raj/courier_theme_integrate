@extends('admin.layouts.master')
@section('page_title', sizeof($allStates) ? optional($allStates[0]->country)->name.' '.$title : $title)

@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang(sizeof($allStates) ? optional($allStates[0]->country)->name.' '.$title : $title)</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div
						class="breadcrumb-item">@lang(sizeof($allStates) ? optional($allStates[0]->country)->name.' '.$title : $title)</div>
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
															<input placeholder="@lang('State')" name="name"
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
												<button class="btn btn-sm  btn-dark dropdown-toggle" type="button"
														id="dropdownMenuButton"
														data-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
													<span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
												</button>
												<div class="dropdown-menu drop-left-24"
													 aria-labelledby="dropdownMenuButton">
													<a class="dropdown-item text-success bulkStateEnable"
													   href="javascript:void(0)" data-toggle="modal"
													   data-target="#bulk-state-enable"><i
															class="fas fa-check mr-2"></i> @lang('Bulk Enable')</a>
													<a class="dropdown-item text-danger bulkStateDisable" href="javascript:void(0)"
													   data-toggle="modal"
													   data-target="#bulk-state-disable"><i
															class="fas fa-ban mr-2"></i> @lang('Bulk Disable')</a>

												</div>
												<a href="{{route('stateList', ['state-list'])}}"
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
														<th scope="col">@lang('State')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.State_List.permission.edit'), config('permissionList.Manage_Locations.State_List.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allStates as $key => $state)
														<tr>
															<td class="text-center">
																<input type="checkbox" id="chk-{{ $state->id }}"
																	   class="form-check-input row-tic tic-check"
																	   name="check"
																	   value="{{$state->id}}"
																	   data-id="{{ $state->id }}">
																<label for="chk-{{ $state->id }}"></label>
															</td>
															<td data-label="@lang('State')">
																@lang($state->name)
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($state->status == 1)
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-success font-12"></i> @lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-danger font-12"></i> @lang('Deactive')
																	</span>
																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.State_List.permission.edit'), config('permissionList.Manage_Locations.State_List.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Manage_Locations.State_List.permission.edit')))
																		<button data-target="#editStateModal"
																				data-toggle="modal"
																				data-route="{{route('stateUpdate', $state->id)}}"
																				data-property="{{ $state }}"
																				data-countries="{{ $allCountires }}"
																				class="btn btn-sm rounded-circle btn-outline-primary editState">
																			<i class="fas fa-edit" data-toggle="tooltip"
																			   data-original-title="@lang('Edit')"></i>
																		</button>
																	@endif

																	@if($state->status == 1)
																		<a href="javascript:void(0)"
																		   data-target="#state-disable-modal"
																		   data-toggle="modal"
																		   data-route="{{ route('stateDisable',$state->id) }}"
																		   data-property="{{ $state }}"
																		   class="btn btn-sm rounded-circle btn-outline-danger ml-1 mb-1 stateDisable"><i
																				data-toggle="tooltip"
																				data-original-title="@lang('Disable')"
																				class="fas fa-ban"></i></a>
																	@else
																		<a href="javascript:void(0)"
																		   data-target="#state-enable-modal"
																		   data-toggle="modal"
																		   data-route="{{ route('stateEnable',$state->id) }}"
																		   data-property="{{ $state }}"
																		   class="btn btn-sm rounded-circle btn-outline-success ml-1 mb-1 stateEnable"><i
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
												class="card-footer d-flex justify-content-center">{{ $allStates->links() }}</div>
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


	{{-- Add State Modal --}}
	<div id="add-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add State')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('stateStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Country')</label>
							<select name="country_id" class="form-control @error('country_id') is-invalid @enderror">
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
							<label for="">@lang('State Name')</label>
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

	{{-- Edit State Modal --}}
	<div id="editStateModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit State')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editStateForm">
					@csrf
					@method('put')
					<div class="modal-body">

						<div class="col-12 mt-3">
							<label for="">@lang('Select Country')</label>
							<select name="country_id" class="form-control @error('country_id') is-invalid @enderror"
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
							<label for="">@lang('State Name')</label>
							<input
								type="text"
								class="form-control stateName" name="name"
								placeholder="@lang('Name')" required/>
							<div class="invalid-feedback">
								@error('name') @lang($message) @enderror
							</div>
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


	{{-- Disable State Modal --}}
	<div id="state-disable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="disableStateForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="state_name"></p>
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
	<div id="state-enable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="enableStateForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="state_name"></p>
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
	<div id="bulk-state-enable" class="modal fade" tabindex="-1" role="dialog"
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
					<p>@lang("Are you sure to enable these states?")</p>
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
	<div id="bulk-state-disable" class="modal fade" tabindex="-1" role="dialog"
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
					<p>@lang("Are you sure to disable these states?")</p>
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

	@if($errors->has('country_id') || $errors->has('name'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("editStateModal"), {});
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
			$(document).on('click', '.editState', function () {

				let dataRoute = $(this).data('route');
				$('#editStateForm').attr('action', dataRoute)

				let dataProperty = $(this).data('property');
				let dataCountries = $(this).data('countries');

				$('.stateName').val(dataProperty.name);
				$('#countryId').val(dataProperty.country_id);

				$(dataProperty.status == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);
			});


			$(document).on("click", ".stateDisable", function () {
				let route = $(this).data('route');
				let dataProperty = $(this).data('property');
				$('.state_name').text(`Are you sure to disable state ${dataProperty.name}?`);
				$('#disableStateForm').attr('action', route);
			});

			$(document).on("click", ".stateEnable", function () {
				let route = $(this).data('route');
				let dataProperty = $(this).data('property');
				$('.state_name').text(`Are you sure to enable state ${dataProperty.name}?`);
				$('#enableStateForm').attr('action', route);
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
					url: "{{ route('multiple-state-enabled') }}",
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
					url: "{{ route('multiple-state-disabled') }}",
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
