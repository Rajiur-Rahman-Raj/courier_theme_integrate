@extends('admin.layouts.master')
@section('page_title', __('All Country List'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('All Country List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Country List')</div>
				</div>
			</div>

			<div class="row mb-3">
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
													<input placeholder="@lang('Country')" name="country"
														   value="{{ old('country',request()->country) }}" type="text"
														   class="form-control form-control-sm">
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group search-currency-dropdown">
													<select name="status"
															class="form-control form-control-sm select2">
														<option value="all">@lang('All Status')</option>
														<option
															value="active" {{  old('status', request()->status) == 'active' ? 'selected' : '' }}>@lang('Active')</option>
														<option
															value="deactive" {{  old('status', request()->status) == 'deactive' ? 'selected' : '' }}>@lang('Deactive')</option>
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


					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Country List')</h6>

									<div class="dropdown mb-2 text-right">
										@if(adminAccessRoute(config('permissionList.Manage_Locations.Country_List.permission.add')))

											@if(!File::exists('assets/worldSeeder.txt'))
												<button class="btn btn-sm btn-outline-primary" id="run-task"><i
														class="fas fa-upload"></i> @lang('Import All Countries')
												</button>
											@else
												<button class="btn btn-sm  btn-dark dropdown-toggle" type="button"
														id="dropdownMenuButton"
														data-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
													<span><i class="fas fa-bars pr-2"></i> @lang('Action')</span>
												</button>
											@endif
											<div class="dropdown-menu drop-left-24"
												 aria-labelledby="dropdownMenuButton">
												<a class="dropdown-item text-success bulkCountryEnable"
												   href="javascript:void(0)" data-toggle="modal"
												   data-target="#bulk-country-enable"><i
														class="fas fa-check mr-2"></i> @lang('Bulk Enable')</a>
												<a class="dropdown-item text-muted bulkCountryDisable" href="javascript:void(0)"
												   data-toggle="modal"
												   data-target="#bulk-country-disable"><i
														class="fas fa-ban mr-2"></i> @lang('Bulk Disable')</a>

												<a class="dropdown-item text-danger bulkCountryDelete" href="javascript:void(0)"
												   data-toggle="modal"
												   data-target="#bulk-country-delete"><i
														class="fas fa-trash mr-2"></i> @lang('Bulk Delete')</a>
											</div>
											@if(adminAccessRoute(config('permissionList.Manage_Locations.Country_List.permission.add')))
												<button class="btn btn-sm btn-outline-primary" data-target="#add-modal"
														data-toggle="modal"><i
														class="fas fa-plus-circle"></i> @lang(' Add Country')</button>
											@endif
										@endif

									</div>


								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
											class="table table-striped table-hover align-items-center table-borderless">
											<thead class="thead-light">
											<tr>
												<th scope="col" class="text-center">
													<input type="checkbox" class="form-check-input check-all tic-check"
														   name="check-all"
														   id="check-all">
													<label for="check-all"></label>
												</th>

												<th>@lang('Country')</th>
												<th scope="col">@lang('Status')</th>
												@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.Country_List.permission.edit'), config('permissionList.Manage_Locations.Country_List.permission.delete'))))
													<th scope="col" class="text-center">@lang('Action')</th>
												@endif
											</tr>
											</thead>
											<tbody id="loadingData">
											@forelse($allCountries as $key => $country)
												<tr>
													<td class="text-center">
														<input type="checkbox" id="chk-{{ $country->id }}"
															   class="form-check-input row-tic tic-check" name="check"
															   value="{{$country->id}}"
															   data-id="{{ $country->id }}">
														<label for="chk-{{ $country->id }}"></label>
													</td>

													<td data-label="@lang('Country')">
														@lang($country->name)
													</td>

													<td data-label="@lang('Status')"
														class="font-weight-bold text-dark">
														@if($country->status == 1)
															<span class="badge badge-light">
																		<i class="fa fa-circle text-success font-12"></i> @lang('Active')
																	</span>
														@else
															<span class="badge badge-light">
																		<i class="fa fa-circle text-danger font-12"></i> @lang('Deactive')
																	</span>
														@endif
													</td>
													@if(adminAccessRoute(array_merge(config('permissionList.Manage_Locations.Country_List.permission.edit'), config('permissionList.Manage_Locations.Country_List.permission.delete'))))
														<td data-label="@lang('Action')" class="text-center">
															@if(adminAccessRoute(config('permissionList.Manage_Locations.Country_List.permission.edit')))
																<button
																	data-target="#editCountryModal"
																	data-toggle="modal"
																	data-route="{{route('countryUpdate', $country->id)}}"
																	data-property="{{ $country }}"
																	class="btn btn-sm rounded-circle btn-outline-primary mb-1 editCountry">
																	<i class="fas fa-edit" data-toggle="tooltip"
																	   data-original-title="@lang('Edit')"></i></button>
															@endif

															@if($country->status == 1)
																<a href="javascript:void(0)"
																   data-target="#country-disable-modal"
																   data-toggle="modal"
																   data-route="{{ route('countryDisable',$country->id) }}"
																   data-property="{{ $country }}"
																   class="btn btn-sm rounded-circle btn-outline-warning ml-1 mb-1 countryDisable"><i
																		data-toggle="tooltip"
																		data-original-title="@lang('Disable')"
																		class="fas fa-ban"></i></a>
															@else
																<a href="javascript:void(0)"
																   data-target="#country-enable-modal"
																   data-toggle="modal"
																   data-route="{{ route('countryEnable',$country->id) }}"
																   data-property="{{ $country }}"
																   class="btn btn-sm rounded-circle btn-outline-success ml-1 mb-1 countryEnable"><i
																		data-toggle="tooltip"
																		data-original-title="@lang('Enable')"
																		class="fas fa-check"></i></a>
															@endif
															<a href="javascript:void(0)"
															   data-target="#country-delete-modal"
															   data-toggle="modal"
															   data-route="{{ route('countryDelete',$country->id) }}"
															   data-property="{{ $country }}"
															   class="btn btn-sm rounded-circle btn-outline-danger ml-1 mb-1 countryDelete"><i
																	data-toggle="tooltip"
																	data-original-title="@lang('Delete')"
																	class="fas fa-trash"></i></a>

														</td>
													@endif
												</tr>
											@empty
												<tr>
													<td colspan="100%" class="text-center">
														<img class="not-found-img"
															 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
															 alt="">
														<p class="text-center no-data-found-text">@lang('No found data')</p>
													</td>
												</tr>
											@endforelse
											</tbody>
										</table>
									</div>
									<div
										class="card-footer d-flex justify-content-center">{{ $allCountries->appends($_GET)->links() }}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>


	{{-- Add Country Modal --}}
	<div id="add-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add Country')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('countryStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Country Name')</label>
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

	{{-- Edit Country Modal --}}
	<div id="editCountryModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit Country')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editCountryForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Country Name')</label>
							<input
								type="text"
								class="form-control countryName" name="name"
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

	{{-- Disable Country Modal --}}
	<div id="country-disable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="disableCountryForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="country_name"></p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Enable Country Modal --}}
	<div id="country-enable-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="enableCountryForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<p class="country_name"></p>
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
	<div id="bulk-country-enable" class="modal fade" tabindex="-1" role="dialog"
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
					<p>@lang("Are you sure to enable these countries?")</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post">
						@csrf
						<a href="javascript:void(0)" class="btn btn-primary enable-yes"><span>@lang('Yes')</span></a>
					</form>
				</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Bulk Disable Country Modal --}}
	<div id="bulk-country-disable" class="modal fade" tabindex="-1" role="dialog"
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
					<p>@lang("Are you sure to disable these countries?")</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post">
						@csrf
						<a href="javascript:void(0)" class="btn btn-primary disable-yes"><span>@lang('Yes')</span></a>
					</form>
				</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Country Delete Modal --}}
	<div id="country-delete-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Confirmation')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="deleteCountryForm">
					@csrf
					@method('delete')
					<div class="modal-body">
						<p class="country_name"></p>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	{{-- Bulk Delete Country Modal --}}
	<div id="bulk-country-delete" class="modal fade" tabindex="-1" role="dialog"
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
					<p>@lang("Are you sure to delete these countries?")</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post">
						@csrf
						<a href="javascript:void(0)" class="btn btn-primary delete-yes"><span>@lang('Yes')</span></a>
					</form>
				</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
	@if($errors->has('name'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("add-modal"), {});
			document.onreadystatechange = function () {
				myModal.show();
			};
		</script>
	@endif

	@if($errors->has('name'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("editCountryModal"), {});
			document.onreadystatechange = function () {
				myModal.show();
			};
		</script>
	@endif

	<script>
		'use strict'

		if(document.getElementById('run-task')){
			document.getElementById('run-task').addEventListener('click', runTask);
		}

		async function runTask() {
			showLoadingBlock('Please wait few moments...', 'hourglass');

			try {
				const response = await axios.post("{{ route('importCountries') }}", {
					headers: {
						'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
					},
				});
			} catch (error) {
				console.error(error);
			} finally {
				hideLoadingBlock();
				window.location.reload();
			}
		}


		function showLoadingBlock(message, iconType) {
			Notiflix.Block[iconType]('#loadingData', message,{
				backgroundColor: 'rgba(0,0,0,0.8)',
				svgColor: '#32c682',
				messageColor: '#fff',
				clickable: true,
				borderRadius: '5px',
				messageFontSize: '18px',
				svgSize: '70px',
				zIndex: 5000,
			});
		}

		function hideLoadingBlock() {
			Notiflix.Block.remove('#loadingData');
		}



		$(document).on('click', '.editCountry', function () {
			let dataRoute = $(this).data('route');
			$('#editCountryForm').attr('action', dataRoute)
			let dataProperty = $(this).data('property');
			$('.countryName').val(dataProperty.name);
			$(dataProperty.status == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);
		});


		$(document).on("click", ".countryDisable", function () {
			let route = $(this).data('route');
			let dataProperty = $(this).data('property');
			$('.country_name').text(`Are you sure to disable country ${dataProperty.name}?`);
			$('#disableCountryForm').attr('action', route);
		});

		$(document).on("click", ".countryEnable", function () {
			let route = $(this).data('route');
			let dataProperty = $(this).data('property');
			$('.country_name').text(`Are you sure to enable country ${dataProperty.name}?`);
			$('#enableCountryForm').attr('action', route);
		});

		$(document).on("click", ".countryDelete", function () {
			let route = $(this).data('route');
			let dataProperty = $(this).data('property');
			$('.country_name').text(`Are you sure to delete country ${dataProperty.name}?`);
			$('#deleteCountryForm').attr('action', route);
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
				url: "{{ route('multiple-country-enabled') }}",
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
				url: "{{ route('multiple-country-disabled') }}",
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

		//multiple Delete
		$(document).on('click', '.delete-yes', function (e) {
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
				url: "{{ route('multiple-country-deleted') }}",
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

	</script>
@endsection
