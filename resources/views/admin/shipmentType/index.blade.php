@extends('admin.layouts.master')
@section('page_title', 'Shipment Types')
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Shipment Types')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Shipment Types')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Shipment Type')</th>
														<th scope="col">@lang('title')</th>
														@if(adminAccessRoute(config('permissionList.Shipment_Types.Shipment_Type_List.permission.edit')))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allShipmentType as $key => $type)
														@php
															$type = (object) $type;
														@endphp
														<tr>
															<td data-label="@lang('Shipment Type')">
																@lang($type->shipment_type)
															</td>

															<td data-label="@lang('title')">
																@lang($type->title)
															</td>
															@if(adminAccessRoute(config('permissionList.Shipment_Types.Shipment_Type_List.permission.edit')))
																<td data-label="@lang('Action')">
																	<button data-target="#updateShipmnetTypeModal"
																			data-toggle="modal"
																			data-route="{{route('shipmentTypeUpdate', $type->id)}}"
																			data-shipmenttype="{{$type->shipment_type}}"
																			data-title="{{$type->title}}"
																			class="btn btn-sm btn-outline-primary rounded-circle editShipmentType">
																		<i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="@lang('Update')"></i>
																	</button>
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


	{{-- Edit Parcel Type Modal --}}
	<div id="updateShipmnetTypeModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit Shipment Type')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editShipmentTypeForm">
					@csrf
					@method('put')
					<div class="modal-body">

						<div class="col-12 mt-3">
							<label for="">@lang('Shipment Type')</label>
							<input
								type="text"
								class="form-control shipment-type-name" name="shipment_type"
								placeholder="@lang('shipment type')" required/>
							<div class="invalid-feedback d-block">
								@error('shipment_type') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Title')</label>
							<input
								type="text"
								class="form-control shipment-type-title" name="title"
								placeholder="@lang('title')" required/>
							<div class="invalid-feedback d-block">
								@error('title') @lang($message) @enderror
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
@endsection

@section('scripts')

	@if($errors->has('shipment_type') || $errors->has('title'))
		<script>
			$(document).ready(function () {
				$('#updateShipmnetTypeModal').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('#updateShipmnetTypeModal').modal('show');
			});
		</script>
	@endif

	<script>
		'use strict'
		$(document).ready(function () {
			$(document).on('click', '.editShipmentType', function () {
				let dataRoute = $(this).data('route');
				$('#editShipmentTypeForm').attr('action', dataRoute);
				let shipmentType = $(this).data('shipmenttype');
				let title = $(this).data('title');
				$('.shipment-type-name').val(shipmentType);
				$('.shipment-type-title').val(title);
			});
		})
	</script>

	@if ($errors->any())
		@php
			$collection = collect($errors->all());
			$errors = $collection->unique();
		@endphp
		<script>
			"use strict"
			@foreach ($errors as $error)
			Notiflix.Notify.failure("{{ trans($error) }}");
			@endforeach
		</script>
	@endif

@endsection
