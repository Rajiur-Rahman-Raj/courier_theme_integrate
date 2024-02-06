@extends('admin.layouts.master')

@section('page_title')
	@lang('Edit Role')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">-
				<h1>@lang("Edit Role")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a href="{{route('admin.role')}}">@lang("Role List")</a></div>
					<div class="breadcrumb-item">@lang("Edit Role")</div>
				</div>
			</div>
		</section>
		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h5>@lang("Edit Role")</h5>

							<a href="{{route('admin.role')}}" class="btn btn-sm  btn-primary mr-2">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>
						</div>
						<div class="card-body">
							<form method="post" action="{{ route('roleUpdate', $singleRole->id) }}"
								  class="mt-4" enctype="multipart/form-data">
								@csrf
								<div class="row">
									<div class="col-sm-12 col-md-12 mb-3">
										<label for="name" class="font-weight-bold text-dark"> @lang('Role Name') <span
												class="text-danger">*</span></label>
										<input type="text" name="name"
											   placeholder="@lang('write role name')"
											   class="form-control @error('name') is-invalid @enderror"
											   value="{{ old('name', $singleRole->name) }}">
										<div class="invalid-feedback">
											@error('name') @lang($message) @enderror
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="card mb-4 card-primary ">
											<div class="card-header">
												<div class="title">
													<h5>@lang('Accessibility')</h5>
												</div>
											</div>
											<div class="card-body select-all-access">
												<div class="form-group">
													<label class="text-dark font-weight-bold"
														   style="font-size: 16px"><input type="checkbox" class="selectAll" name="accessAll"> {{trans('Select All')}}
													</label>
												</div>
												@if(config('permissionList'))
													@php
														$i = 0;
													@endphp

													@foreach(config('permissionList') as $key1 => $sidebarMenus)
														@php
															$i++;
														@endphp

														<div id="accordion">
															<div class="card">
																<div class="card-header p-0 m-0 border-0"
																	 id="{{$key1}}">
																	<button
																		class="btn btn- w-100 text-left rounded-0 py-2 pb-3 d-flex align-items-center justify-content-between"
																		type="button" data-toggle="collapse"
																		data-target="#{{$key1}}" aria-expanded="true"
																		aria-controls="{{$key1}}">
																		<h6 class="text-dark mt-3">@lang(str_replace('_', ' ', $key1))</h6>
																		<i class="fas fa-chevron-down text-dark"></i>
																	</button>
																</div>

																<div id="{{$key1}}" class="collapse allAccordianShowHide"
																	 aria-labelledby="{{$key1}}"
																	 data-parent="#accordion">
																	<div class="card-body">
																		@php
																			$i++;
																		@endphp
																		<div class="title d-flex justify-content-start">
																			<label class="font-weight-bold"><input
																					type="checkbox"
																					class="partiallySelectAll{{$i}}"
																					name="partiallyAccessAll"
																					onclick="partiallySelectAll({{$i}})"> {{trans('Select All')}}
																			</label>
																		</div>

																		<table
																			class=" table table-hover table-striped table-bordered text-center">
																			<thead class="thead-dark">
																			<tr>
																				<th class="text-left">@lang('Permissions')</th>
																				<th>@lang('View')</th>
																				<th>@lang('Add')</th>
																				@if($key1 == 'Shipping_Rates')
																					<th>@lang('Show')</th>
																				@endif
																				<th>@lang('Edit')</th>
																				<th>@lang('Delete')</th>
																				@if($key1 == 'Manage_Branch')
																					<th>@lang('Profile')</th>
																					<th>@lang('Staff List')</th>
																					<th>@lang('Login As')</th>
																				@endif

																				@if($key1 == 'Manage_Customers')
																					<th>@lang('Profile')</th>
																					<th>@lang('Login As')</th>
																				@endif

																				@if($key1 == 'User_Panel')
																					<th>@lang('Send Mail')</th>
																					<th>@lang('Login As')</th>
																				@endif

																				@if($key1 == 'Role_And_Permissions')
																					<th>@lang('Login As')</th>
																				@endif

																				@if($key1 == 'Manage_Shipments')
																					<th>@lang('Dispatch')</th>
																				@endif
																			</tr>
																			</thead>
																			<tbody>
																			@foreach($sidebarMenus as $key2 => $subMenu)
																				<tr class="partiallyCheckAll{{ $i }}">
																					<td class="text-left">@lang(str_replace('_', ' ', $key2))</td>
																					<td data-label="View">
																						@if(!empty($subMenu['permission']['view']))
																							<input type="checkbox"
																								   value="{{join(",",$subMenu['permission']['view'])}}"
																								   class="cursor-pointer"
																								   @if(in_array_any( $subMenu['permission']['view'], $singleRole->permission??[] ))
																									   checked
																								   @endif
																								   name="permissions[]"/>
																						@else
																							<span>-</span>
																						@endif
																					</td>

																					<td data-label="Add">
																						@if(!empty($subMenu['permission']['add']))
																							<input type="checkbox"
																								   value="{{join(",",$subMenu['permission']['add'])}}"
																								   class="cursor-pointer"
																								   @if(in_array_any( $subMenu['permission']['add'], $singleRole->permission??[] ))
																									   checked
																								   @endif
																								   name="permissions[]"/>
																						@else
																							<span>-</span>
																						@endif
																					</td>
																					@if($key1 == 'Shipping_Rates')
																						<td data-label="Show">
																							@if(!empty($subMenu['permission']['show']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif
																					<td data-label="Edit">
																						@if(!empty($subMenu['permission']['edit']))
																							<input type="checkbox"
																								   value="{{join(",",$subMenu['permission']['edit'])}}"
																								   class="cursor-pointer"
																								   @if(in_array_any( $subMenu['permission']['edit'], $singleRole->permission??[] ))
																									   checked
																								   @endif
																								   name="permissions[]"/>
																						@else
																							<span>-</span>
																						@endif
																					</td>
																					<td data-label="Delete">
																						@if(!empty($subMenu['permission']['delete']))
																							<input type="checkbox"
																								   value="{{join(",",$subMenu['permission']['delete'])}}"
																								   class="cursor-pointer"
																								   @if(in_array_any( $subMenu['permission']['delete'], $singleRole->permission??[] ))
																									   checked
																								   @endif
																								   name="permissions[]"/>
																						@else
																							<span>-</span>
																						@endif
																					</td>

																					@if($key2 == 'Shipment_List')
																						<td data-label="dispatch">
																							@if(!empty($subMenu['permission']['dispatch']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['dispatch'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any($subMenu['permission']['dispatch'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key2 == 'Branch_List')
																						<td data-label="Profile">
																							@if(!empty($subMenu['permission']['show_profile']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_profile'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_profile'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Staff List">
																							@if(!empty($subMenu['permission']['show_staff_list']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_staff_list'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>

																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif


																					@if($key2 == 'Branch_Manager')
																						<td data-label="Profile">
																							@if(!empty($subMenu['permission']['show_profile']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_profile'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_profile'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Staff List">
																							@if(!empty($subMenu['permission']['show_staff_list']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_staff_list'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>

																					
																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key2 == 'Employee_List')
																						<td data-label="Profile">
																							@if(!empty($subMenu['permission']['show_profile']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_profile'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_profile'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Staff List">
																							@if(!empty($subMenu['permission']['show_staff_list']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_staff_list'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key2 == 'Driver_List')
																						<td data-label="Profile">
																							@if(!empty($subMenu['permission']['show_profile']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_profile'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_profile'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Staff List">
																							@if(!empty($subMenu['permission']['show_staff_list']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_staff_list'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_staff_list'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key2 == 'Customer_List')
																						<td data-label="Profile">
																							@if(!empty($subMenu['permission']['show_profile']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['show_profile'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['show_profile'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>

																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key1 == 'User_Panel')
																						<td data-label="Send Mail">
																							@if(!empty($subMenu['permission']['send_mail']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['send_mail'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['send_mail'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>

																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
																							@endif
																						</td>
																					@endif

																					@if($key1 == 'Role_And_Permissions')
																						<td data-label="Login As">
																							@if(!empty($subMenu['permission']['login_as']))
																								<input type="checkbox"
																									   value="{{join(",",$subMenu['permission']['login_as'])}}"
																									   class="cursor-pointer"
																									   @if(in_array_any( $subMenu['permission']['login_as'], $singleRole->permission??[] ))
																										   checked
																									   @endif
																									   name="permissions[]"/>
																							@else
																								<span>-</span>
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
													@endforeach
												@endif
											</div>
											<div class="invalid-feedback d-block">
												@error('permissions') @lang($message) @enderror
											</div>
										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-md-5 form-group">
										<label class="font-weight-bold text-dark">@lang('Status')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="status" value="0"
													   class="selectgroup-input" {{ old('status', $singleRole->status) == 0 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('OFF')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="status" value="1"
													   class="selectgroup-input" {{ old('status', $singleRole->status) == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('ON')</span>
											</label>
										</div>
									</div>
								</div>

								<button type="submit"
										class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3">@lang('Update Role')</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		'use strict'
		function partiallySelectAll($key1){
			if($(`.partiallySelectAll${$key1}`).prop('checked') == true) {
				$(`.partiallyCheckAll${$key1}`).find('input[type="checkbox"]').attr('checked', 'checked');
			} else {
				$(`.partiallyCheckAll${$key1}`).find('input[type="checkbox"]').removeAttr('checked');
			}
		}

		$(document).ready(function () {
			$('.selectAll').on('click', function () {
				if ($(this).is(':checked')) {
					$(this).parents('.select-all-access').find('input[type="checkbox"]').attr('checked', 'checked')
					$('.allAccordianShowHide').addClass('show');
				} else {
					$(this).parents('.select-all-access').find('input[type="checkbox"]').removeAttr('checked')
					$('.allAccordianShowHide').removeClass('show');
				}
			})
		})
	</script>
@endsection

