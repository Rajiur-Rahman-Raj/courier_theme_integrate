@extends('admin.layouts.master')

@section('title')
	@lang('Edit Department')
@endsection


@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang("Edit Department")</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active"><a href="{{ route('admin.home') }}">@lang("Dashboard")</a></div>
					<div class="breadcrumb-item"><a href="{{route('departmentList')}}">@lang("Departments")</a></div>
					<div class="breadcrumb-item">@lang("Edit Department")</div>
				</div>
			</div>
		</section>

		<div class="section-body">
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<div class="card mb-4 card-primary shadow-sm">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h5>@lang("Edit Department")</h5>

							<a href="{{route('departmentList')}}" class="btn btn-sm  btn-primary mr-2">
								<span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
							</a>
						</div>
						<div class="card-body">
							<form method="post" action="{{ route('departmentUpdate', $singleDepartmentInfo->id) }}"
								  class="mt-4" enctype="multipart/form-data">
								@csrf

								<div class="row">
									<div class="col-sm-12 col-md-4 mb-3">
										<label for="name"> @lang('Department Name') </label>
										<input type="text" name="name"
											   class="form-control @error('name') is-invalid @enderror"
											   value="{{ old('name', $singleDepartmentInfo->name) }}">
										<div class="invalid-feedback">
											@error('name') @lang($message) @enderror
										</div>
										<div class="valid-feedback"></div>
									</div>

									<div class="col-md-4">
										<label>@lang('Status')</label>
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="status" value="0"
													   class="selectgroup-input" {{ $singleDepartmentInfo->status == 0 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('OFF')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="status" value="1"
													   class="selectgroup-input" {{ $singleDepartmentInfo->status == 1 ? 'checked' : ''}}>
												<span class="selectgroup-button">@lang('ON')</span>
											</label>
										</div>
									</div>

									<div class="col-sm-12 col-md-4 mb-3">
										<label for="save" class="opacity-0"> @lang('save')</label>
										<button type="submit"
												class="btn waves-effect waves-light btn-rounded btn-primary btn-block">@lang('Save')</button>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection


		@push('extra_scripts')
			<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
		@endpush

		@section('scripts')
			<script type="text/javascript">
				'use strict';
				$(document).ready(function () {
					$.uploadPreview({
						input_field: "#image-upload",
						preview_box: "#image-preview",
						label_field: "#image-label",
						label_default: "Choose File",
						label_selected: "Change File",
						no_label: false
					});
				});
			</script>

@endsection
