@extends('admin.layouts.master')
@section('page_title')
	{{ trans($page_title) }}
@endsection
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Manual Payment Methods')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Manual Payment Methods')</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Method List')</h6>
									@if(adminAccessRoute(config('permissionList.Payment_Settings.Manual_Gateway.permission.add')))
										<a href="{{route('admin.deposit.manual.create')}}"
										   class="btn btn-success btn-sm float-right mb-3"><i
												class="fa fa-plus-circle"></i> {{trans('Add New')}}</a>
									@endif
								</div>
								<div class="card-body">
									<table class="table ">
										<thead class="thead-dark">
										<tr>
											<th scope="col">@lang('Name')</th>
											<th scope="col">@lang('Status')</th>
											@if(adminAccessRoute(array_merge(config('permissionList.Payment_Settings.Manual_Gateway.permission.edit'), config('permissionList.Payment_Settings.Manual_Gateway.permission.delete'))))
												<th scope="col">@lang('Action')</th>
											@endif
										</tr>

										</thead>
										<tbody id="sortable">
										@if(count($methods) > 0)
											@foreach($methods as $method)
												<tr data-code="{{ $method->code }}">
													<td data-label="@lang('Name')">
														<a href="javascript:void(0)"
														   class="text-decoration-none">
															<div class="d-lg-flex d-block align-items-center ">
																<div class="mr-3"><img
																		src="{{getFile($method->driver,$method->image) }}"
																		alt="user" class="rounded-circle"
																		width="40" data-toggle="tooltip"
																		title=""
																		data-original-title="{{ __($method->name) }}">
																</div>
																<div
																	class="d-inline-flex d-lg-block align-items-center ms-2">
																	<p class="text-dark mb-0 font-16 font-weight-medium">
																		{{ __($method->name) }}</p>
																</div>
															</div>
														</a>
													</td>
													<td data-label="@lang('Status')">

														{!!  $method->status == 1 ? '<span class="badge badge-light"><i class="fa fa-circle text-success font-12"></i> '.trans('Active').'</span>' : '<span class="badge badge-light"><i class="fa fa-circle text-danger font-12"></i> '.trans('DeActive').'</span>' !!}
													</td>
													@if(adminAccessRoute(array_merge(config('permissionList.Payment_Settings.Manual_Gateway.permission.edit'), config('permissionList.Payment_Settings.Manual_Gateway.permission.delete'))))
														<td data-label="@lang('Action')">
															@if(adminAccessRoute(config('permissionList.Payment_Settings.Manual_Gateway.permission.edit')))
																<a href="{{ route('admin.deposit.manual.edit', $method->id) }}"
																   class="btn btn-outline-primary rounded-circle btn-circle"
																   data-toggle="tooltip"
																   data-placement="top"
																   data-original-title="@lang('Edit')">
																	<i class="fa fa-edit"></i></a>
															@endif

														</td>
													@endif
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="100%" class="text-center">
													<img class="not-found-img"
														 src="{{ asset('assets/dashboard/images/empty-state.png') }}"
														 alt="">
													<p class="text-center no-data-found-text">@lang('No found data')</p>
												</td>
											</tr>
										@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	</div>
@endsection


@push('js')
	<script>
		"use strict";
		$('.disableBtn').on('click', function () {
			var status = $(this).data('status');
			$('.messageShow').text($(this).data('message'));
			var modal = $('#disableModal');
			modal.find('input[name=code]').val($(this).data('code'));
		});
	</script>
@endpush
