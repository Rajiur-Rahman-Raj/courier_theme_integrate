@extends('admin.layouts.master')
@section('page_title', __('Branch Profile'))
@push('extra_styles')
@endpush
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Branch Profile')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a> /
						<a href="{{ route('branchList') }}">@lang('Branches')</a>
					</div>
					<div class="breadcrumb-item">@lang('Branch Profile')</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row justify-content-md-center">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div class="card-header d-inline">
									<div class="row">
										<div class="col-xl-5">
											<div class="w-100"><h5
													class="mt-0 font-weight-bold text-primary text-center mt-3 mb-4 ">{{ __($branchInfo->branch_name) }}</h5>
											</div>
										</div>
									</div>
									<div class="row ">
										<div class="col-xl-5">

											<div class="branch-img">
												<img class="h-100 align-self-start img-profile-view img-thumbnail"
													 src="{{ getFile($branchInfo->driver,$branchInfo->image) }}"
													 alt="@lang('Branch Image')">
											</div>

										</div>
										<div class="col-xl-7 mt-4 mt-xl-0">
											<div class="row">
												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-boxes"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Total Shipments')</h4>
															</div>
															<div class="card-body">
																{{ $totalShipments }}
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Total Transaction Amount')</h4>
															</div>
															<div class="card-body">
																{{trans($basic->currency_symbol)}}{{getAmount($totalTransactions, config('basic.fraction_number'))}}
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Condition Receive Amount')</h4>
															</div>
															<div class="card-body">
																{{trans($basic->currency_symbol)}}{{getAmount($conditionReceiveAmount, config('basic.fraction_number'))}}
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Condition Pay Amount')</h4>
															</div>
															<div class="card-body">
																{{trans($basic->currency_symbol)}}{{getAmount($conditionPayAmount, config('basic.fraction_number'))}}
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Branch In Transactions')</h4>
															</div>
															<div class="card-body">
																{{trans($basic->currency_symbol)}}{{getAmount($branchInTransaction, config('basic.fraction_number'))}}
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-6 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Branch Out Transactions')</h4>
															</div>
															<div class="card-body">
																{{trans($basic->currency_symbol)}}{{getAmount($branchOutTransaction, config('basic.fraction_number'))}}
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12 mb-3">
													<div class="card card-statistic-1 shadow-sm branch-box">
														<div class="card-icon bg-primary">
															<i class="fas fa-dollar-sign"></i>
														</div>
														<div class="card-wrap">
															<div class="card-header">
																<h4>@lang('Branch Current Assets')</h4>
															</div>
															<div class="card-body">
																{{getAmount($branchCurrentAssets, config('basic.fraction_number'))}} <span class="font-weight-normal">{{trans($basic->base_currency)}}</span>
															</div>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>
									<div class="row mt-4">
										<div class="col-xl-3 col-md-4 col-sm-6 ">
											<div class="address-item m-2">
												<div class="icon-area">
													<i class="fas fa-map-marker-alt"></i>
												</div>
												<div class="content-area">
													<span
														class="font-weight-bold text-dark"></span> {{ __($branchInfo->address) }}
												</div>

											</div>

										</div>
										<div class="col-xl-3 col-md-4 col-sm-6 ">
											<div class="address-item m-2">
												<div class="icon-area">
													<i class="fas fa-phone fa-rotate-90"></i>
												</div>
												<div class="content-area">
													<span
														class="font-weight-bold text-dark"> </span> {{ __($branchInfo->phone) }}
												</div>

											</div>

										</div>
										<div class="col-xl-3 col-md-4 col-sm-6 ">
											<div class="address-item m-2">
												<div class="icon-area">
													<i class="fas fa-envelope"></i>
												</div>
												<div class="content-area">
													<span
														class="font-weight-bold text-dark"></span> {{ __($branchInfo->email) }}
												</div>

											</div>


										</div>
										<div class="col-xl-3 col-md-4 col-sm-6 ">
											<div class="address-item m-2">
												<div class="icon-area">
													<i class="fas fa-envelope"></i>
												</div>
												<div class="content-area">
													<span
														class="font-weight-bold text-dark"> </span> {{ __($branchInfo->email) }}
												</div>

											</div>


										</div>

									</div>
								</div>


								<div class="card-body">
									<form method="post" action="{{ route('branchUpdate', $branchInfo->id) }}"
										  class="mt-4" enctype="multipart/form-data">
										@csrf
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="name">@lang('Branch Name') <span
															class="text-danger">*</span></label>
													<input type="text" name="branch_name"
														   class="form-control @error('branch_name') is-invalid @enderror"
														   value="{{ old('branch_name', $branchInfo->branch_name) }}">
													<div
														class="invalid-feedback">@error('branch_name') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="city">@lang('Email')</label>
													<input type="text" name="email"
														   class="form-control @error('email') is-invalid @enderror"
														   value="{{ old('email', $branchInfo->email) }}">
													<div
														class="invalid-feedback">@error('email') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label for="phone">@lang('Phone')</label>
													<input type="text" name="phone"
														   class="form-control @error('phone') is-invalid @enderror"
														   value="{{ old('phone', $branchInfo->phone) }}">
													<div
														class="invalid-feedback">@error('phone') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group">
													<label for="address">@lang('Address')</label>
													<input type="text" name="address"
														   class="form-control @error('address') is-invalid @enderror"
														   value="{{ old('address', $branchInfo->address) }}">
													<div
														class="invalid-feedback">@error('address') @lang($message) @enderror</div>
												</div>
											</div>


											<div class="col-md-12">
												<div class="form-group">
													<label for="details">@lang('Details')</label>
													<textarea
														class="form-control @error('details') is-invalid @enderror"
														name="details" rows="5"
														value="{!! $branchInfo->details !!}">{!! $branchInfo->details !!} </textarea>
													<div
														class="invalid-feedback">@error('details') @lang($message) @enderror</div>
												</div>
											</div>


											<div class="col-md-6">
												<div class="form-group">
													<label for="name">@lang('Branch Profile') <span class="text-danger">*</span></label>
													<div id="image-preview" class="image-preview"
														 style="background-image: url({{getFile($branchInfo->driver, $branchInfo->image)}})">
														<label for="image-upload"
															   id="image-label">@lang('Choose File')</label>
														<input type="file" name="image" class=""
															   id="image-upload"/>
													</div>
													<div
														class="invalid-feedback">@error('branch_name') @lang($message) @enderror</div>
												</div>
											</div>


											<div class="col-md-6">
												<div class="form-group">
													<label for="city">@lang('Status')</label>
													<div class="selectgroup w-100">
														<label class="selectgroup-item">
															<input type="radio" name="status" value="0"
																   class="selectgroup-input" {{ $branchInfo->status == 0 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('OFF')</span>
														</label>

														<label class="selectgroup-item">
															<input type="radio" name="status" value="1"
																   class="selectgroup-input" {{ $branchInfo->status == 1 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('ON')</span>
														</label>
													</div>
												</div>
											</div>
										</div>

										<button type="submit"
												class="btn btn-primary btn-sm btn-block">@lang('Update Profile')</button>
									</form>
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
	<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
@endpush
@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$(document).on('change', '.file-upload-input', function () {
				let _this = $(this);
				let reader = new FileReader();
				reader.readAsDataURL(this.files[0]);
				reader.onload = function (e) {
					$('.img-profile-view').attr('src', e.target.result);
				}
			});

			$.uploadPreview({
				input_field: "#image-upload",
				preview_box: "#image-preview",
				label_field: "#image-label",
				label_default: "Choose File",
				label_selected: "Change File",
				no_label: false
			});
		})
	</script>
@endsection
