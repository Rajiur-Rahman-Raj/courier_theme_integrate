@extends('admin.layouts.master')
@section('page_title', __('All Email Templates'))

@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('All Email Templates')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('All Email Templates')</div>
				</div>
			</div>

			<div class="row mb-3 email-show">
				<div class="container-fluid" id="container-wrapper">
					<div class="row">
						<div class="col-12 col-md-4 col-lg-3">
							@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.email'), 'suffix' => ''])
						</div>
						<div class="col-12 col-md-8 col-lg-9">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('List of all templates')</h6>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table
											class="table table-striped table-hover align-items-center table-flush table-sm"
											id="emailTemplate">
											<thead class="thead-light">
											<tr>
												<th>@lang('No.')</th>
												<th>@lang('Name')</th>
												<th>@lang('Subject')</th>
												<th>@lang('Status')</th>
												@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.edit')))
													<th>@lang('Action')</th>
												@endif
											</tr>
											</thead>
											<tbody>
											@foreach($emailTemplates as $template)
												<tr>
													<td data-label="@lang('No.')">{{ __($loop->iteration) }}</td>
													<td data-label="@lang('Name')">{{ __($template->name) }}</td>
													<td data-label="@lang('Subject')">{{ __($template->subject) }}</td>
													<td data-label="@lang('Status')">
												<span
													class="badge badge-pill badge-{{($template->mail_status == 1) ?'success' : 'danger'}}">
													{{($template->mail_status == 1) ?trans('Active') : trans('Deactive')}}
												</span>
													</td>
													@if(adminAccessRoute(config('permissionList.Control_Panel.Control_Panel.permission.edit')))
														<td data-label="@lang('Action')">
															<a href="{{ route('email.template.edit',$template) }}"
															   class="btn btn-sm btn-outline-primary"><i
																	class="fas fa-edit"></i> @lang('Edit') </a>
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
@endsection
@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush
@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$('#emailTemplate').DataTable();
		});
	</script>
@endsection
