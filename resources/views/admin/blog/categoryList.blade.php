@extends('admin.layouts.master')
@section('page_title', __('Blog Category List'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Category List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Category List')</div>
				</div>
			</div>
			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-12 col-lg-12">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Category List')</h6>
											@if(adminAccessRoute(config('permissionList.Blog_Settings.Category_List.permission.add')))
												<a href="{{route('blogCategoryCreate')}}"
												   class="btn btn-sm btn-outline-primary add"><i
														class="fas fa-plus-circle"></i> @lang('Add New')</a>
											@endif
										</div>
										<div class="card-body">
											<div class="table-responsive">
												<table
													class="table table-striped table-hover align-items-center table-flush"
													id="data-table">
													<thead class="thead-light">
													<tr>
														<th scope="col">@lang('SL.')</th>
														<th scope="col">@lang('Category Name')</th>
														<th scope="col">@lang('Status')</th>
														@if(adminAccessRoute(array_merge(config('permissionList.Blog_Settings.Category_List.permission.edit'), config('permissionList.Blog_Settings.Category_List.permission.delete'))))
															<th scope="col">@lang('Action')</th>
														@endif
													</tr>
													</thead>
													<tbody>
													@forelse($manageBlogCategory as $key => $item)
														<tr>
															<td data-label="@lang('SL.')">{{++$key}}</td>
															<td data-label="@lang('Category Name')"
																class="">@lang(optional($item->details)->name)</td>
															<td data-label="@lang('Status')"
																class="font-weight-bold text-dark">
																@if($item->status == 1)
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-success"></i>
																		@lang('Active')
																	</span>
																@else
																	<span class="badge badge-light">
            															<i class="fa fa-circle text-success"></i>
																		@lang('Deactive')</span>
																@endif
															</td>
															@if(adminAccessRoute(array_merge(config('permissionList.Blog_Settings.Category_List.permission.edit'), config('permissionList.Blog_Settings.Category_List.permission.delete'))))
																<td data-label="@lang('Action')">
																	@if(adminAccessRoute(config('permissionList.Blog_Settings.Category_List.permission.edit')))
																		<a href="{{ route('blogCategoryEdit',$item->id) }}"
																		   class="btn btn-outline-primary rounded-circle btn-sm"
																		   data-toggle="tooltip"
																		   data-original-title="@lang('Edit')"><i class="fa fa-edit"
																									aria-hidden="true"></i>
																		</a>
																	@endif
																	@if(adminAccessRoute(config('permissionList.Blog_Settings.Category_List.permission.delete')))
																		<a href="javascript:void(0)"
																		   data-target="#delete-category"
																		   data-toggle="modal"
																		   data-route="{{ route('blogCategoryDelete', $item->id) }}"
																		   class="btn btn-sm btn-outline-danger rounded-circle deleteClass">
																			<i class="fa fa-trash" data-toggle="tooltip"
																			   data-original-title="@lang('Delete')"></i>
																		</a>
																	@endif
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

	@push('loadModal')
		<div class="modal fade" id="delete-category">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="" class="deleteModal">
						@csrf
						@method('delete')
						<!-- Modal Header -->
						<div class="modal-header modal-colored-header bg-primary">
							<h4 class="modal-title text-white modalTitle">@lang('Confirmation')</h4>
							<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
						</div>
						<!-- Modal body -->
						<div class="modal-body">
							<p>@lang('Are you sure to delete this category?')</p>
						</div>
						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal"><span>@lang('Close')</span>
							</button>
							<button type="submit" class=" btn btn-primary"><span>@lang('Yes')</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endpush

@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush
@section('scripts')
	<script>
		'use strict'
		$(document).on('click', '.deleteClass', function () {
			let route = $(this).data('route');
			$('.deleteModal').attr('action', route);
		});
	</script>

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
