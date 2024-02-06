@extends('admin.layouts.master')
@section('page_title', 'packaging Services')
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('packaging Services')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('packaging Services')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">

							<div class="row justify-content-md-between">
								<div class="col-lg-6">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Package List')</h6>
											@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.add')))
												<button class="btn btn-sm btn-outline-primary"
														data-target="#add-package-modal"
														data-toggle="modal"><i
														class="fas fa-plus-circle"></i> @lang('Add Package')</button>
											@endif
										</div>

										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Package Name')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Packaging_Service.Service_List.permission.edit'), config('permissionList.Packaging_Service.Service_List.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allPackages as $key => $package)
														<tr>
															<td data-label="@lang('Package Name')">
																@lang($package->package_name)
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																	<?php echo $package->statusMessage; ?>
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Packaging_Service.Service_List.permission.edit'), config('permissionList.Packaging_Service.Service_List.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit')))
																		<button data-route="{{ route('packageUpdate', $package->id) }}"
																				data-toggle="tooltip" data-placement="top" title="@lang('Edit')"
																				data-property="{{ $package }}"
																				class="btn btn-sm btn-outline-primary rounded-circle editPackage">
																			<i class="fas fa-edit"></i>
																		</button>
																	@endif
																</td>
															@endif

														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img mb-3" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No Package Found')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $allPackages->links() }}</div>
										</div>
									</div>
								</div>


								<div class="col-lg-6">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Package Variant')</h6>
											@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.add')))
												<button class="btn btn-sm btn-outline-primary"
														data-target="#add-variant-modal"
														data-toggle="modal"><i
														class="fas fa-plus-circle"></i> @lang('Add Variant')</button>
											@endif
										</div>

										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Package')</th>
														<th scope="col">@lang('Variant')</th>
														<th scope="col">@lang('Action')</th>
													</tr>
													</thead>

													<tbody>
													@forelse($allVariants as $key => $variant)
														<tr>
															<td data-label="@lang('Package')">
																@lang(optional($variant->package)->package_name)
															</td>

															<td data-label="@lang('Variant')">
																<a href="javascript:void(0)">
																	(@lang(optional($variant->package)->totalVariant()))
																</a>
															</td>
															<td data-label="@lang('Action')">
																<button data-target="#showVariantModal"
																		data-toggle="modal"
																		data-property="{{ $variant }}"
																		class="btn btn-sm btn-outline-primary rounded-circle showVariant">
																	<i class="fas fa-eye"></i> </button>
															</td>

														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img mb-3" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No Variant Found')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div
												class="card-footer d-flex justify-content-center">{{ $allVariants->links() }}</div>
										</div>
									</div>
								</div>
							</div>

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
															<input placeholder="@lang('package')" name="package"
																   value="{{ old('package',request()->package) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<input placeholder="@lang('variant')" name="variant"
																   value="{{ old('variant',request()->variant) }}"
																   type="text"
																   class="form-control form-control-sm">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group search-currency-dropdown">
															<select name="status" class="form-control form-control-sm">
																<option value="all">@lang('All Status')</option>
																<option
																	value="active" {{  request()->status == 'active' ? 'selected' : '' }}>@lang('Active')</option>
																<option
																	value="deactive" {{  request()->status == 'deactive' ? 'selected' : '' }}>@lang('Deactive')</option>
															</select>
														</div>
													</div>

													<div class="col-md-3">
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
											<h6 class="m-0 font-weight-bold text-primary">@lang('Packaging Services')</h6>
											@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.add')))
												<button class="btn btn-sm btn-outline-primary"
														data-target="#add-packingService-modal"
														data-toggle="modal"><i
														class="fas fa-plus-circle"></i> @lang('Add Service')</button>
											@endif
										</div>

										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('Package')</th>
														<th scope="col">@lang('Variant')</th>
														<th scope="col">@lang('Cost')</th>
														<th scope="col">@lang('Weight')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Packaging_Service.Service_List.permission.edit'), config('permissionList.Packaging_Service.Service_List.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>

													<tbody>
													@forelse($allPackingService as $key => $service)
														<tr>
															<td data-label="@lang('Package')">
																@lang(optional($service->package)->package_name)
															</td>

															<td data-label="@lang('Variant')">
																@lang(optional($service->variant)->variant)
															</td>

															<td data-label="@lang('Cost')">
																{{ config('basic.currency_symbol') . $service->cost }}
															</td>

															<td data-label="@lang('Weight')">
																@lang($service->weight) @lang('KG')
															</td>

															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																	<?php echo $service->statusMessage; ?>
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Packaging_Service.Service_List.permission.edit'), config('permissionList.Packaging_Service.Service_List.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit')))
																		<button data-route="{{route('packingServiceUpdate', $service->id)}}"
																				data-toggle="tooltip" data-placement="top" title="@lang('Edit')"
																				data-property="{{ $service }}"
																				data-packages="{{ $allPackages }}"
																				data-variants="{{ $allVariants }}"
																				class="btn btn-sm btn-outline-primary rounded-circle editPackingService">
																			<i class="fas fa-edit"></i>
																		</button>
																	@endif
																</td>
															@endif
														</tr>
													@empty
														<tr>
															<td colspan="100%" class="text-center">
																<img class="not-found-img" src="{{ asset('assets/dashboard/images/empty-state.png') }}" alt="">
																<p class="text-center no-data-found-text">@lang('No Service Found')</p>
															</td>
														</tr>
													@endforelse
													</tbody>
												</table>
											</div>
											<div class="card-footer d-flex justify-content-center">{{ $allPackingService->appends($_GET)->links() }}</div>
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


	{{-- Add Package Modal --}}
	<div id="add-package-modal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add Package')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('packageStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Package Name')</label>
							<input
								type="text"
								class="form-control @error('package_name') is-invalid @enderror" name="package_name"
								placeholder="@lang('package name')" required/>
							<div class="invalid-feedback">
								@error('package_name') @lang($message) @enderror
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

	{{-- Edit Package Modal --}}
	<div id="editPackageModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit Package')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="editPackageForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Package Name')</label>
							<input
								type="text"
								class="form-control packageName" name="package_name"
								placeholder="@lang('Package Name')" required/>
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

	{{-- Add Variant Modal --}}
	<div id="add-variant-modal" class="modal fade" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add Package Variant')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('variantStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Package') </label>
							<select name="package_id"
									class="form-control @error('package_id') is-invalid @enderror select2">
								<option value="" disabled selected>@lang('Select Package')</option>
								@foreach($allPackages as $package)
									<option value="{{ $package->id }}">@lang($package->package_name)</option>
								@endforeach
							</select>

							<div class="invalid-feedback">
								@error('package_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Variant')</label>
							<input
								type="text"
								class="form-control @error('variant') is-invalid @enderror" name="variant"
								placeholder="@lang('variant name')" required/>
							<div class="invalid-feedback">
								@error('variant') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<div class="form-group mb-4">
								<label for="">@lang('Variant Photo') <span
										class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
								<div id="image-preview" class="image-preview"
									 style="background-image: url({{ getFile(config('location.category.path'))}}">
									<label for="image-upload"
										   id="image-label" class="image-label">@lang('Choose File')</label>
									<input type="file" name="image" class="image-upload"
										   id="image-upload"/>
								</div>
								@error('image')
								<span class="text-danger">{{ $message }}</span>
								@enderror
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

	{{-- Show Variant Modal --}}
	<div id="showVariantModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold showPackageName"
						id="primary-header-modalLabel"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>

				<div class="modal-body">
					<div class="table-responsive">
						<table
							class="table table-striped table-hover align-items-center table-flush"
							id="data-table">
							<thead class="thead-light">
							<tr>
								<th scope="col">@lang('Variant')</th>
								<th scope="col">@lang('Image')</th>
								<th scope="col">@lang('Status')</th>
								@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit')))
									<th scope="col">@lang('Action')</th>
								@endif
							</tr>
							</thead>

							<tbody class="packageVariantTr">
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
				</div>
			</div>
		</div>
	</div>

	{{-- Uddate Variant Modal --}}
	<div id="updateVariantModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Edit Variant')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="updateVariantForm" enctype="multipart/form-data">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Package') </label>
							<select name="package_id" class="form-control @error('package_id') is-invalid @enderror"
									id="packageId">
								@foreach($allPackages as $package)
									<option value="{{ $package->id }}">@lang($package->package_name)</option>
								@endforeach
							</select>

							<div class="invalid-feedback">
								@error('package_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="" class="variant-label">@lang('Variant') </label>
							<input
								type="text"
								class="form-control @error('variant') is-invalid @enderror variant-name" name="variant"
								placeholder="@lang('variant name')" value="" required/>
							<div class="invalid-feedback">
								@error('variant') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<div class="form-group mb-4">
								<label for="">@lang('Variant Photo') <span
										class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
								<div id="image-preview" class="image-preview variantImage"
									 style="">
									<label for="image-upload"
										   id="image-label" class="image-label">@lang('Choose File')</label>
									<input type="file" name="image" class="image-upload"
										   id="image-upload"/>
								</div>
								@error('image')
								<span class="text-danger">{{ $message }}</span>
								@enderror
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
						@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit')))
							<button type="submit" class="btn btn-primary">@lang('Update')</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Add Packing Service Modal --}}
	<div id="add-packingService-modal" class="modal fade" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Add Packing Service')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="{{ route('packingServiceStore') }}" method="post" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Package') </label>
							<select name="package_id"
									class="form-control @error('package_id') is-invalid @enderror selectedPackage select2">
								<option value="" disabled selected>@lang('Select Package')</option>
								@foreach($allPackages as $package)
									<option value="{{ $package->id }}">@lang($package->package_name)</option>
								@endforeach
							</select>

							<div class="invalid-feedback">
								@error('package_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Select Variant') </label>
							<select name="variant_id"
									class="form-control @error('variant_id') is-invalid @enderror selectedVariant select2">
							</select>

							<div class="invalid-feedback">
								@error('variant_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Cost')</label>

							<div class="input-group">
								<input type="text" class="form-control @error('cost') is-invalid @enderror" name="cost"
									   placeholder="@lang('amount')" required/>
								<div class="input-group-append">
									<div class="form-control">
										{{ config('basic.base_currency') }}
									</div>
								</div>
								<div class="invalid-feedback">
									@error('cost') @lang($message) @enderror
								</div>
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Weight')</label>
							<div class="input-group">
								<input type="text" class="form-control @error('weight') is-invalid @enderror"
									   name="weight" placeholder="@lang('1')" required/>
								<div class="input-group-append">
									<div class="form-control">
										@lang('Kg')
									</div>
								</div>
								<div class="invalid-feedback">
									@error('cost') @lang($message) @enderror
								</div>
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

	{{-- Uddate Packing Service Modal --}}
	<div id="updatePackingServiceModal" class="modal fade" tabindex="-1" role="dialog"
		 aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-dark font-weight-bold"
						id="primary-header-modalLabel">@lang('Update Packing Service')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<form action="" method="post" id="updatePackingServiceForm">
					@csrf
					@method('put')
					<div class="modal-body">
						<div class="col-12 mt-3">
							<label for="">@lang('Select Package') </label>
							<select name="package_id"
									class="form-control @error('package_id') is-invalid @enderror selectedPackage"
									id="packId">
								@foreach($allPackages as $package)
									<option value="{{ $package->id }}">@lang($package->package_name)</option>
								@endforeach
							</select>

							<div class="invalid-feedback">
								@error('package_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Select Variant') </label>
							<select name="variant_id"
									class="form-control @error('variant_id') is-invalid @enderror selectedVariant"
									id="variId">
								@foreach($allPackageVariants as $variant)
									<option value="{{ $variant->id }}">@lang($variant->variant)</option>
								@endforeach
							</select>

							<div class="invalid-feedback">
								@error('variant_id') @lang($message) @enderror
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Cost')</label>

							<div class="input-group">
								<input type="text" class="form-control @error('cost') is-invalid @enderror variant-cost"
									   name="cost"
									   placeholder="@lang('amount')" required/>
								<div class="input-group-append">
									<div class="form-control">
										{{ config('basic.base_currency') }}
									</div>
								</div>
								<div class="invalid-feedback">
									@error('cost') @lang($message) @enderror
								</div>
							</div>
						</div>

						<div class="col-12 mt-3">
							<label for="">@lang('Weight') </label>
							<div class="input-group">
								<input type="text"
									   class="form-control @error('weight') is-invalid @enderror variant-weight"
									   name="weight" placeholder="@lang('1')" required/>
								<div class="input-group-append">
									<div class="form-control">
										@lang('Kg')
									</div>
								</div>
								<div class="invalid-feedback">
									@error('cost') @lang($message) @enderror
								</div>
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
						@if(adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit')))
							<button type="submit" class="btn btn-primary">@lang('Update')</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
@endpush

@section('scripts')
	@if($errors->has('package_name'))
		<script>
			$(document).ready(function () {
				$('#add-package-modal').modal({
					backdrop: 'static',
					keyboard: false
				});
				$('#add-package-modal').modal('show');
			});
		</script>
	@endif

	@if($errors->has('package_id') || $errors->has('variant'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("add-variant-modal"), {});
			document.onreadystatechange = function () {
				myModal.show();
			};
		</script>
	@endif

	@if($errors->has('package_id') || $errors->has('variant_id') || $errors->has('cost') || $errors->has('weight'))
		<script>
			var myModal = new bootstrap.Modal(document.getElementById("add-packingService-modal"), {});
			document.onreadystatechange = function () {
				myModal.show();
			};
		</script>
	@endif

	@include('partials.packageVariant')
	<script>
		'use strict'

		$(document).ready(function () {
			$.uploadPreview({
				input_field: ".image-upload",
				preview_box: ".image-preview",
				label_field: ".image-label",
				label_default: "Choose File",
				label_selected: "Change File",
				no_label: false
			});

			$(document).on('click', '.editPackage', function () {
				let dataRoute = $(this).data('route');
				$('#editPackageForm').attr('action', dataRoute);
				let dataProperty = $(this).data('property');
				$('.packageName').val(dataProperty.package_name);
				$(dataProperty.status == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);
				$('#editPackageModal').modal('show');
			});

			$(document).on('click', '.showVariant', function () {
				let dataProperty = $(this).data('property');
				let packageName = dataProperty.package.package_name;
				let packageId = dataProperty.package.id;
				$('.showPackageName').text(`${packageName} Variant`);
				const showVariant = true;
				getSelectedPackageVariant(packageId, showVariant);
			})

			$(document).on('click', '.editVariant', function () {

				let dataRoute = $(this).data('route');
				let dataPackageId = $(this).data('packageid');
				let variantName = $(this).data('name');
				let dataStatus = $(this).data('status');
				let dataImage = $(this).data('image');
				let imgUrl = `url(${dataImage})`;

				$('#updateVariantForm').attr('action', dataRoute);
				$('.variant-name').val(variantName);
				$('#packageId').val(dataPackageId);

				let setImage = document.querySelector('.variantImage');
				setImage.style.backgroundImage = imgUrl;

				$(dataStatus == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);
			});

			$(document).on('click', '.editPackingService', function () {
				let dataRoute = $(this).data('route');
				$('#updatePackingServiceForm').attr('action', dataRoute)
				let dataProperty = $(this).data('property');
				$('#packId').val(dataProperty.package_id);
				$('#variId').val(dataProperty.variant_id);
				$('.variant-cost').val(dataProperty.cost);
				$('.variant-weight').val(dataProperty.weight);
				$(dataProperty.status == 0 ? '.status_disabled' : '.status_enabled').prop('checked', true);
				let showVariant = false;
				let variantId = dataProperty.variant_id;
				getSelectedPackageVariant(dataProperty.package_id, showVariant, variantId);
				$('#updatePackingServiceModal').modal('show');

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
