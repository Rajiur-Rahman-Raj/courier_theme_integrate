@extends('admin.layouts.master')
@section('page_title', __('Languages'))
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Languages')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Languages')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-4 col-lg-3">
						@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
					</div>
					<div class="col-12 col-md-8 col-lg-9">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Languages')</h6>
											@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.add')))
												<span>
												<a href="{{ route('language.create') }}"
												   class="btn btn-sm btn-outline-primary"><i
														class="fas fa-plus-circle"></i> @lang('Add New')</a>
											</span>
											@endif
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-hover align-items-center table-flush">
													<thead class="thead-light">
													<tr>
														<th>@lang('Name')</th>
														<th>@lang('Short Name')</th>
														<th>@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Control_Panel.Control_Panel.permission.edit'), config('permissionList.Control_Panel.Control_Panel.permission.delete'))))
															<th>@lang('Action')</th>
														@endif
													</tr>
													</thead>
													<tbody>
													@foreach($languages as $key => $language)
														<tr>
															<td data-label="@lang('Name')">
																<div class="d-flex no-block align-items-center">
																	<div class="mr-2">`
																		<img
																			class="img-profile-custom rounded-circle"
																			src="{{ getFile($language->driver,$language->flag )}}"
																			alt="{{ __($language->name) }}" width="35"
																			height="35">
																	</div>
																	<div class="">
																		<p class="text-dark mb-0 font-weight-medium">{{ __($language->name) }} @if($language->default_status)
																				<span
																					class="fa fa-check-circle text-success"></span>
																			@endif</p>


																	</div>
																</div>
															</td>
															<td data-label="@lang('Short Name')">{{ __($language->short_name) }}</td>
															<td data-label="@lang('Status')">
																@if($language->is_active)
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-success"></i>@lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
																		<i class="fa fa-circle text-danger"></i>@lang('Inactive')
																	</span>
																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Control_Panel.Control_Panel.permission.edit'), config('permissionList.Control_Panel.Control_Panel.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.edit')))
																		<a href="{{ route('language.edit',$language) }}"
																		   data-toggle="tooltip"
																		   data-original-title="@lang('Edit')"
																		   class="btn btn-sm rounded-circle btn-outline-primary"><i
																				class="fas fa-edit"></i></a>
																		<a href="{{ route('language.keyword.edit',$language) }}"
																		   data-toggle="tooltip"
																		   data-original-title="@lang('Edit Keywords')"
																		   class="btn btn-sm rounded-circle btn-outline-primary m-1"><i
																				class="fas fa-code"></i>
																		</a>
																	@endif

																	@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.delete')))
																		@if(!$language->default_status)
																			<a href="javascript:void(0)"
																			   data-route="{{ route('language.delete',$language) }}"
																			   data-toggle="modal"
																			   data-target="#delete-modal"
																			   class="btn btn-outline-danger btn-sm delete rounded-circle">
																				<i data-toggle="tooltip"
																				   data-original-title="@lang('Delete')"
																				   class="fas fa-trash-alt"></i>
																			</a>
																		@else
																		@endif
																	@endif
																</td>
															@endif
														</tr>
													@endforeach
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

	<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title text-danger" id="primary-header-modalLabel">@lang('Confirmation !')</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body text-center">
					<p>@lang('Are you sure to delete this?')</p>
				</div>
				<form action="" method="post" class="deleteRoute">
					@csrf
					@method('delete')
					<div class="modal-footer">
						<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$(document).on('click', '.delete', function () {
				let url = $(this).data('route');
				$('.deleteRoute').attr('action', url);
			})
		});
	</script>
@endsection


