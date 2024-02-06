@extends('admin.layouts.master')

@section('page_title')
	@lang('Create New Role')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang("Create New Role")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a href="{{route('admin.role')}}">@lang("Available Roles")</a></div>
					<div class="breadcrumb-item">@lang("Create Role")</div>
				</div>
			</div>
		</section>

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h5>@lang("Create Role")</h5>

							<a href="{{route('admin.role')}}" class="btn btn-sm  btn-primary mr-2">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>
						</div>
						<div class="card-body">
							<form method="post" action="{{ route('roleStore') }}"
								  class="mt-4" enctype="multipart/form-data">
								@csrf
								<div class="row">
									<div class="col-sm-12 col-md-6 mb-3">
										<h6 for="name" class="font-weight-bold text-dark"> @lang('Role Name') <span
												class="text-danger">*</span></h6>
										<input type="text" name="name"
											   style="height: 40px !important;"
											   placeholder="@lang('Enter role name')"
											   class="form-control @error('name') is-invalid @enderror"
											   value="{{ old('name') }}">
										<div class="invalid-feedback">
											@error('name') @lang($message) @enderror
										</div>
									</div>
									<div class="col-md-6 form-group">
										<label class="font-weight-bold text-dark">@lang('Status')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="status" value="0"
													   class="selectgroup-input" {{ old('status') == 0 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('OFF')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="status" value="1"
													   class="selectgroup-input"
													   checked {{ old('status') == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('ON')</span>
											</label>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="card mb-4 card-primary ">
											<div class="card-header d-flex align-items-center justify-content-between">
												<div class="title">
													<h5>@lang('Accessibility')</h5>
												</div>
											</div>

											<div class="card-body">
												<table width="100%" class="select-all-access">
													<thead>
													<tr>
														<th class="p-2">@lang('Permissions Group')</th>
														<th class="p-2"><input type="checkbox" class="selectAll"
																			   name="accessAll" id="allowAll"> <label
																class="mb-0"
																for="allowAll">@lang('Allow All Permissions')</label>
														</th>

														<th class="p-2">@lang('Permission')</th>
													</tr>
													</thead>
													<tbody>
													@if(config('permissionList'))
														@php
															$i = 0;
                                                            $j = 500;
														@endphp
														@foreach(collect(config('permissionList')) as $key1 => $permission)
															@php
																$i++;
															@endphp
															<tr class="partiallyCheckAll{{ $i }}">
																<td class="pl-2">
																	<strong>
																		<input
																			type="checkbox"
																			class="partiallySelectAll{{$i}}"
																			name="partiallyAccessAll"
																			id="partiallySelectAll{{$i}}"
																			onclick="partiallySelectAll({{$i}})"> <label
																			for="partiallySelectAll{{$i}}">@lang(str_replace('_', ' ', $key1))</label>
																	</strong>
																</td>
																@if(1 == count($permission))
																	<td class="border-left pl-2">
																		<input type="checkbox"
																			   class="cursor-pointer singlePermissionSelectAll{{$i}}"
																			   id="singlePermissionSelect{{$i}}"
																			   value="{{join(",",collect($permission)->collapse()['permission']['view'])}}"
																			   onclick="singlePermissionSelectAll({{$i}})"
																			   name="permissions[]"/>
																		<label
																			for="singlePermissionSelect{{$i}}">{{ str_replace('_', ' ', collect($permission)->keys()[0]) }}</label>
																	</td>
																	<td class="pl-2 border-left" style="width: 178px;">
																		<ul class="list-unstyled">
																			@if(!empty(collect($permission)->collapse()['permission']['view']))
																				<li>
																					@if(!empty(collect($permission)->collapse()['permission']['view']))
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['view'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/> @lang('View')
																					@endif
																				</li>
																			@endif

																			@if(!empty(collect($permission)->collapse()['permission']['add']))
																				<li>
																					<input type="checkbox"
																						   value="{{join(",",collect($permission)->collapse()['permission']['add'])}}"
																						   class="cursor-pointer"
																						   name="permissions[]"/> @lang('Add')
																				</li>
																			@endif

																			@if(!empty(collect($permission)->collapse()['permission']['edit']))
																				<li>
																					<input type="checkbox"
																						   value="{{join(",",collect($permission)->collapse()['permission']['edit'])}}"
																						   class="cursor-pointer"
																						   name="permissions[]"/>
																					@lang('Edit')
																				</li>
																			@endif

																			@if(!empty(collect($permission)->collapse()['permission']['delete']))
																				<li>
																					<input type="checkbox"
																						   value="{{join(",",collect($permission)->collapse()['permission']['delete'])}}"
																						   class="cursor-pointer"
																						   name="permissions[]"/>
																					@lang('Delete')
																				</li>
																			@endif

																			@if(collect($permission)->keys()[0] == 'Shipment_List')
																				@if(!empty(collect($permission)->collapse()['permission']['dispatch']))
																					<li>
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['dispatch'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/>
																						@lang('Dispatch')
																					</li>
																				@endif
																			@endif

																			@if(collect($permission)->keys()[0] == 'Customer_List')
																				@if(!empty(collect($permission)->collapse()['permission']['show_profile']))
																					<li>
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['show_profile'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/>
																						@lang('Profile')
																					</li>
																				@endif

																				@if(!empty(collect($permission)->collapse()['permission']['login_as']))
																					<li>
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['login_as'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/>
																						@lang('Login As')
																					</li>
																				@endif
																			@endif

																			@if($key1 == 'User_Panel')
																				@if(!empty(collect($permission)->collapse()['permission']['send_mail']))
																					<li>
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['send_mail'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/>
																						@lang('Send Mail')
																					</li>
																				@endif

																				@if(!empty(collect($permission)->collapse()['permission']['login_as']))
																					<li>
																						<input
																							type="checkbox"
																							value="{{join(",",collect($permission)->collapse()['permission']['login_as'])}}"
																							class="cursor-pointer"
																							name="permissions[]"/>
																						@lang('Login As')
																					</li>
																				@endif
																			@endif
																		</ul>
																	</td>
																@else
																	<td colspan="2">
																		<!-- Nested table for the second column -->
																		<table class="child-table">
																			@foreach($permission as $key2 => $subMenu)
																				@php
																					$j++;
																				@endphp

																				<tr class="partiallyCheckAll{{ $j }}">
																					<td class="p-2">
																						<input type="checkbox"
																							   class="cursor-pointer multiplePermissionSelectAll{{$j}}"
																							   id="multiplePermissionSelectAll{{$j}}"
																							   value="{{join(",",$subMenu['permission']['view'])}}"
																							   onclick="multiplePermissionSelectAll({{$j}})"
																							   name="permissions[]"/>
																						<label class="mb-0"
																							   for="multiplePermissionSelectAll{{$j}}">@lang(str_replace('_', ' ', $key2))</label>
																					</td>

																					<td class="pl-2 border-left  multiplePermissionCheck{{$j}}"
																						style="width: 178px;">
																						<ul class="list-unstyled py-2 mb-0">
																							@if(!empty($subMenu['permission']['view']))
																								<li>
																									<input
																										type="checkbox"
																										value="{{join(",",$subMenu['permission']['view'])}}"
																										class="cursor-pointer"
																										name="permissions[]"/> @lang('View')
																								</li>
																							@endif

																							@if(!empty($subMenu['permission']['add']))
																								<li>
																									<input
																										type="checkbox"
																										value="{{join(",",$subMenu['permission']['add'])}}"
																										class="cursor-pointer"
																										name="permissions[]"/> @lang('Add')
																								</li>
																							@endif

																							@if(!empty($subMenu['permission']['edit']))
																								<li>
																									<input
																										type="checkbox"
																										value="{{join(",",$subMenu['permission']['edit'])}}"
																										class="cursor-pointer"
																										name="permissions[]"/> @lang('Edit')
																								</li>
																							@endif

																							@if(!empty($subMenu['permission']['delete']))
																								<li>
																									<input
																										type="checkbox"
																										value="{{join(",",$subMenu['permission']['delete'])}}"
																										class="cursor-pointer"
																										name="permissions[]"/> @lang('Delete')
																								</li>
																							@endif

																							@if($key1 == 'Shipping_Rates')
																								@if(!empty($subMenu['permission']['show']))
																									<li>
																										<input
																											type="checkbox"
																											value="{{join(",",$subMenu['permission']['show'])}}"
																											class="cursor-pointer"
																											name="permissions[]"/>
																										@lang('Show')
																									</li>
																								@endif
																							@endif

																							@if($key1 == 'Manage_Branch')
																								@if(!empty($subMenu['permission']['show_profile']))
																									<li>
																										<input
																											type="checkbox"
																											value="{{join(",",$subMenu['permission']['show_profile'])}}"
																											class="cursor-pointer"
																											name="permissions[]"/>
																										@lang('Profile')
																									</li>
																								@endif

																								@if(!empty($subMenu['permission']['show_staff_list']))
																									<li>
																										<input
																											type="checkbox"
																											value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
																											class="cursor-pointer"
																											name="permissions[]"/>
																										@lang('Staff List')
																									</li>
																								@endif

																								@if(!empty($subMenu['permission']['login_as']))
																									<li>
																										<input
																											type="checkbox"
																											value="{{join(",",$subMenu['permission']['login_as'])}}"
																											class="cursor-pointer"
																											name="permissions[]"/>
																										@lang('Login As')
																									</li>
																								@endif
																							@endif

																							@if($key1 == 'Role_And_Permissions')
																								@if(!empty($subMenu['permission']['login_as']))
																									<li>
																										<input
																											type="checkbox"
																											value="{{join(",",$subMenu['permission']['login_as'])}}"
																											class="cursor-pointer"
																											name="permissions[]"/>
																										@lang('Login As')
																									</li>
																								@endif
																							@endif

																						</ul>
																					</td>
																				</tr>
																			@endforeach
																		</table>
																	</td>

																@endif
															</tr>

														@endforeach
													@endif

													</tbody>
												</table>
											</div>

											<div class="invalid-feedback d-block">
												@error('permissions') @lang($message) @enderror
											</div>
										</div>
									</div>
								</div>

								<button type="submit"
										class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Create Role')</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
	<script type="text/javascript">
		'use strict'

		function partiallySelectAll($key1) {
			if ($(`.partiallySelectAll

			${$key1}`).prop('checked') == true) {
				$(`.partiallyCheckAll

				${$key1}`).find('input[type="checkbox"]').attr('checked', 'checked');
			} else {
				$(`.partiallyCheckAll

				${$key1}`).find('input[type="checkbox"]').removeAttr('checked');
			}
		}

		function singlePermissionSelectAll($key) {
			if ($(`.singlePermissionSelectAll

			${$key}`).prop('checked') == true) {
				$(`.partiallyCheckAll

				${$key}`).find('input[type="checkbox"]').attr('checked', 'checked');
			} else {
				$(`.partiallyCheckAll

				${$key}`).find('input[type="checkbox"]').removeAttr('checked');
			}
		}


		function multiplePermissionSelectAll($key) {
			if ($(`.multiplePermissionSelectAll

			${$key}`).prop('checked') == true) {
				$(`.multiplePermissionCheck

				${$key}`).find('input[type="checkbox"]').attr('checked', 'checked');
			} else {
				$(`.multiplePermissionCheck

				${$key}`).find('input[type="checkbox"]').removeAttr('checked');
			}
		}


		$(document).ready(function () {
			$('.selectAll').on('click', function () {
				if ($(this).is(':checked')) {
					$(this).parents('.select-all-access').find('input[type="checkbox"]').attr('checked', 'checked')
				} else {
					$(this).parents('.select-all-access').find('input[type="checkbox"]').removeAttr('checked')
					$('.allAccordianShowHide').removeClass('show');
				}
			})
		})
	</script>
@endsection
