@extends('admin.layouts.master')
@section('page_title')
	@lang('Blog List')
@endsection

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Blog List')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Blog List')</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Blog List')</h6>
									<div class="media mb-4 float-right">
										@if(adminAccessRoute(config('permissionList.Blog_Settings.Blog_List.permission.add')))
											<a href="{{route('blogCreate')}}" class="btn btn-sm btn-primary mr-2">
												<span><i class="fa fa-plus-circle"></i> @lang('Add New')</span>
											</a>
										@endif
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
											class="table table-striped table-hover align-items-center table-borderless">
											<thead class="thead-light">
											<tr>
												<th scope="col">@lang('SL No.')</th>
												<th scope="col">@lang('Category')</th>
												<th scope="col">@lang('Author')</th>
												<th scope="col">@lang('Title')</th>
												<th scope="col">@lang('Status')</th>
												@if(adminAccessRoute(array_merge(config('permissionList.Blog_Settings.Blog_List.permission.edit'), config('permissionList.Blog_Settings.Blog_List.permission.delete'))))
													<th scope="col" class="text-center">@lang('Action')</th>
												@endif
											</tr>
											</thead>
											<tbody>
											@forelse($blogs as $blog)
												<tr>
													<td data-label="@lang('SL No.')">{{$loop->index+1}}</td>
													<td data-label="@lang('Category')">
														@lang(optional(optional($blog->category)->details)->name)
													</td>

													<td data-label="@lang('Author')">
														@lang(optional($blog->details)->author)
													</td>

													<td data-label="@lang('Title')">
														@lang(\Illuminate\Support\Str::limit(optional($blog->details)->title,45))
													</td>

													<td data-label="@lang('Status')" class="font-weight-bold text-dark">
														@if($blog->status == 1)
															<span class="badge badge-light">
            															<i class="fa fa-circle text-success"></i>
																		@lang('Active')																	</span>
														@else
															<span class="badge badge-light">
            															<i class="fa fa-circle text-danger"></i>
																		@lang('Deactive')																	</span>
														@endif

													</td>
													@if(adminAccessRoute(array_merge(config('permissionList.Blog_Settings.Blog_List.permission.edit'), config('permissionList.Blog_Settings.Blog_List.permission.delete'))))
														<td data-label="@lang('Action')" class="text-center">
															@if(adminAccessRoute(config('permissionList.Blog_Settings.Blog_List.permission.edit')))
																<a href="{{ route('blogEdit',$blog->id) }}"
																   data-toggle="tooltip"
																   data-original-title="@lang('Edit')"
																   class="btn btn-outline-primary btn-rounded rounded-circle btn-sm editBtn"
																>
																	<i class="fas fa-edit"></i>
																</a>
															@endif

															@if(adminAccessRoute(config('permissionList.Blog_Settings.Blog_List.permission.delete')))
																<a href="javascript:void(0)"
																   data-route="{{ route('blogDelete',$blog->id) }}"
																   data-toggle="modal"
																   data-target="#delete-modal"
																   class="btn btn-outline-danger btn-rounded btn-sm rounded-circle deleteItem"><i
																		class="fas fa-trash-alt" data-toggle="tooltip"
																		data-original-title="@lang('Delete')"></i>
																</a>
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
									<div class="card-footer">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- Delete Modal -->
	<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
		 aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="primary-header-modalLabel">@lang('Delete Confirmation')</h5>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<p>@lang('Are you sure to delete this?')</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
					<form action="" method="post" class="deleteRoute">
						@csrf
						@method('delete')
						<button type="submit" class="btn btn-primary">@lang('Yes')</button>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection


@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$(document).on('click', '.deleteItem', function () {
				let url = $(this).data('route');
				$('.deleteRoute').attr('action', url);
			})
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
			Notiflix.Notify.failure("{{trans($error)}}");
			@endforeach
		</script>
	@endif
@endsection
