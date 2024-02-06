@extends('admin.layouts.master')
@section('page_title',__(ucfirst(kebab2Title($content))))

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang(kebab2Title($content))</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang(kebab2Title($content))</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row justify-content-md-center">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang(ucfirst(kebab2Title($content)))</h6>
									@if(adminAccessRoute(config('permissionList.Theme_Settings.Content_Settings.permission.add')))
										<span>
										<a href="{{ route('content.create',$content) }}"
										   class="btn btn-sm btn-outline-primary"><i class="fas fa-plus-circle"></i> @lang('Add New')</a>
									</span>
									@endif
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover align-items-center table-flush">
											<thead class="thead-light">
											<tr>
												@if($content == 'hero')
													<th>@lang('Image')</th>
												@elseif($content == 'testimonial')
													<th>@lang('Name')</th>
												@elseif($content == 'social-links')
													<th>@lang('Social Icon')</th>
												@else
													<th>@lang('title')</th>
												@endif
												@if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Content_Settings.permission.edit'), config('permissionList.Theme_Settings.Content_Settings.permission.delete'))))
													<th>@lang('Action')</th>
												@endif
											</tr>
											</thead>
											<tbody>
											@foreach($contents as $key => $value)
												<tr>
													@if($content == 'hero')
														<td data-label="@lang('')" class="p-3">
															<img
																src="{{ getFile($value->contentMedia->driver, $value->contentMedia->description->image) }}"
																alt="@lang('hero image')" width="100" height="80">
														</td>
													@elseif($content == 'testimonial')
														<td data-label="@lang('Title')">
															@if(isset($value->contentDetails[0]))
																{{ __(optional(optional($value->contentDetails[0])->description)->name ?? __('N/A')) }}
															@endif
														</td>
													@elseif($content == 'social-links')
														<td>
															<div class="social_area mt-50">
																<ul class="d-flex">
																	<li><a href="javascript:void(0)"><i
																				class="{{ optional($value->contentMedia->description)->social_icon }}"></i></a>
																	</li>
																</ul>
															</div>
														</td>
													@else
														<td data-label="@lang('Title')">
															@if(isset($value->contentDetails[0]))
																{{ __(optional(optional($value->contentDetails[0])->description)->title ?? __('N/A')) }}
															@else
																{{ optional(optional($value->contentMedia)->description)->social_icon ?? __('N/A') }}
															@endif
														</td>
													@endif
													@if(adminAccessRoute(array_merge(config('permissionList.Theme_Settings.Content_Settings.permission.edit'), config('permissionList.Theme_Settings.Content_Settings.permission.delete'))))
														<td data-label="@lang('Action')">
															@if(adminAccessRoute(config('permissionList.Theme_Settings.Content_Settings.permission.edit')))
																<a href="{{ route('content.show',$value) }}"
																   class="btn btn-sm btn-outline-primary rounded-circle" data-toggle="tooltip"
																   data-original-title="@lang('Edit')"><i
																		class="fas fa-edit"></i> </a>
															@endif
															@if(adminAccessRoute(config('permissionList.Theme_Settings.Content_Settings.permission.delete')))
																<a href="javascript:void(0)"
																   data-route="{{ route('content.delete',$value->id) }}"
																   data-toggle="modal"
																   data-target="#delete-modal"
																   class="btn btn-outline-danger rounded-circle btn-sm delete"
																><i class="fas fa-trash-alt" data-toggle="tooltip"
																	data-original-title="@lang('Delete')"></i> </a>
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

		</section>
	</div>

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
			$(document).on('click', '.delete', function () {
				let url = $(this).data('route');
				$('.deleteRoute').attr('action', url);
			})
		});
	</script>
@endsection
