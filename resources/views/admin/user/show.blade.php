@extends('admin.layouts.master')
@section('page_title', __('User profile'))
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('User profile')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('User profile')</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="container-fluid user-profile" id="container-wrapper">
					<div class="row justify-content-md-center">
						<div class="col-lg-7">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<div class="media custom--media flex-wrap d-flex flex-row justify-content-center">
										<div class="mr-2">
											<img class="align-self-start mr-3 img-profile-view img-thumbnail"
												 src="{{ getFile($userProfile->driver,$userProfile->profile_picture) }}"
												 alt="{{ __($user->name) }}">
										</div>
										<div class="media-body">

											<h5 class="mt-0 font-weight-bold text-primary">{{ __($user->name) }}</h5>
											<p>
												<i class="fas fa-user"></i> {{ __($user->username) }} <br>
												<i class="fas fa-mobile-alt"></i> {{ __($userProfile->phone) }} <br>
												<i class="fas fa-envelope"></i> {{ __( $user->email) }} <br>
												@if(optional($user->profile)->address != null)
													<i class="fas fa-map-marker-alt"></i> {{ __( optional($user->profile)->address) }}
													<br>
												@endif
											</p>
										</div>
									</div>
								</div>
								<div class="card-body">
									<form method="post" action="{{ route('user.edit',$user) }}"
										  enctype="multipart/form-data">
										@csrf
										<div class="row">
											<div class="col-md-6">
												<label for="name">@lang('Name') </label>
												<input type="text" name="name" placeholder="@lang('User full name')"
													   value="{{ old('name',$user->name) }}"
													   class="form-control @error('name') is-invalid @enderror">
												<div
													class="invalid-feedback">@error('name') @lang($message) @enderror</div>
											</div>

											<div class="col-md-6">
												<label for="username">@lang('Username') </label>
												<input type="text" name="username"
													   placeholder="@lang('Username of uesr')"
													   value="{{ old('username',$user->username) }}"
													   class="form-control @error('username') is-invalid @enderror">
												<div
													class="invalid-feedback">@error('username') @lang($message) @enderror</div>
											</div>

											<div class="col-md-6">
												<label for="email">@lang('E-Mail') </label>
												<input type="text" name="email"
													   placeholder="@lang('User email address')"
													   value="{{ old('email',$user->email) }}"
													   class="form-control @error('email') is-invalid @enderror">
												<div
													class="invalid-feedback">@error('email') @lang($message) @enderror</div>
											</div>

											@if($userProfile->phone_code == null)
												<div class="col-md-6">
													<div class="form-group">
														<label for="phone">@lang('Phone') </label>
														<div class="input-group-sm media">
															<input type="text" name="phone" class="form-control"
																   value="{{old('phone',$userProfile->phone)}}"
																   placeholder="@lang('User Phone Number')">
														</div>
														<div
															class="invalid-feedback">@error('phone') @lang($message) @enderror</div>
													</div>
												</div>
											@else
												<div class="col-md-6">
													<label for="phone">@lang('Phone') </label>
													<div class="input-group-sm media">
														<div class="row">
															<div class="col-md-5">
																<div class="input-group-prepend">
																	<select name="phone_code"
																			class="form-control-sm country_code">
																		@foreach($countries as $value)
																			<option
																				value="{{$value['phone_code']}}"{{ $userProfile->phone_code == $value['phone_code'] ? 'selected' : '' }}>
																				{{ __($value['phone_code']) }}
																				<strong>({{ __($value['name']) }}
																					)</strong>
																			</option>
																		@endforeach
																	</select>
																</div>
															</div>
															<div class="col-md-7">
																<input type="text" name="phone" class="form-control"
																	   value="{{old('phone',$userProfile->phone)}}"
																	   placeholder="@lang('User Phone Number')">
															</div>
														</div>
													</div>
													<div
														class="invalid-feedback">@error('phone') @lang($message) @enderror</div>
												</div>
											@endif

											<div class="col-sm-12 col-md-6">
												<div class="form-group">
													<label for="national_id"> @lang('National Id') <span
															class="font-weight-bold"><sub>(@lang('optional'))</sub></span></label>
													<input type="text" name="national_id"
														   class="form-control @error('national_id') is-invalid @enderror"
														   value="{{ old('national_id', $userProfile->national_id) }}">

													<div
														class="invalid-feedback">@error('national_id') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-md-6">
												<label for="password">@lang('Password')</label>
												<input type="password" name="password"
													   placeholder="@lang('User password')"
													   value="{{ old('password') }}"
													   class="form-control @error('password') is-invalid @enderror">
												<div
													class="invalid-feedback">@error('password') @lang($message) @enderror</div>
											</div>

											<div class="col-sm-12 col-md-12 mb-3">
												<label for="branch_id"> @lang('Branch') </label>
												<select name="branch_id"
														class="form-control @error('branch_id') is-invalid @enderror select2">
													<option value="" disabled selected>@lang('Select Branch')</option>
													@foreach($allBranches as $branch)
														<option
															value="{{ $branch->id }}" {{ $branch->id == $userProfile->branch_id ? 'selected' : '' }}>@lang($branch->branch_name)</option>
													@endforeach
												</select>

												<div class="invalid-feedback">
													@error('branch_id') @lang($message) @enderror
												</div>
												<div class="valid-feedback"></div>
											</div>

											<div class="col-md-12">
												<div class="form-group">
													<label for="address" class="font-weight-normal">@lang('Address')</label>
													<textarea
														class="form-control @error('address') is-invalid @enderror"
														name="address"
														rows="5">{{ old('address', $userProfile->address) }}</textarea>
													<div
														class="invalid-feedback">@error('address') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="search-currency-dropdown">
													<label for="language">@lang('Language')
														<i class="fas fa-info-circle" data-toggle="tooltip"
														   data-placement="top"
														   title="@lang('Select language to get notification on preferred language')"></i>
													</label>
													<select name="language"
															class="form-control @error('language') is-invalid @enderror">
														@foreach($languages as $language)
															<option
																value="{{ $language->id }}" {{ old('language', $user->language_id) == $language->id ? 'selected' : '' }}>
																{{ __($language->name) }}
															</option>
														@endforeach
													</select>
													<div
														class="invalid-feedback">@error('language') @lang($message) @enderror</div>
												</div>
											</div>

											<div class="col-sm-12 col-md-6 mb-3">
												<label for="client_type"> @lang('User Type') </label>
												<select name="client_type"
														class="form-control @error('client_type') is-invalid @enderror select2">
													<option
														value="0" {{ $user->user_type == 0 ? 'selected' : '' }}>@lang('Normal')</option>
													<option
														value="1" {{ $user->user_type == 1 ? 'selected' : '' }}>@lang('Sender/Customer')</option>
													<option
														value="2" {{ $user->user_type == 2 ? 'selected' : '' }}>@lang('Receiver')</option>
												</select>

												<div class="invalid-feedback">
													@error('client_type') @lang($message) @enderror
												</div>
											</div>

											<div class="col-md-4 mt-4">
													<label>@lang('Email Verification')</label>
													<div class="selectgroup w-100">
														<label class="selectgroup-item">
															<input type="radio" name="email_verification" value="0"
																   class="selectgroup-input" {{ old('email_verification', $user->email_verification) == 0 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Unverified')</span>
														</label>
														<label class="selectgroup-item">
															<input type="radio" name="email_verification" value="1"
																   class="selectgroup-input" {{ old('email_verification', $user->email_verification) == 1 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Verified')</span>
														</label>
													</div>
													@error('email_verification')
													<span class="text-danger" role="alert">
														<strong>{{ __($message) }}</strong>
													</span>
													@enderror
											</div>
											<div class="col-md-4 mt-4">
													<label>@lang('SMS Verification')</label>
													<div class="selectgroup w-100">
														<label class="selectgroup-item">
															<input type="radio" name="sms_verification" value="0"
																   class="selectgroup-input" {{ old('sms_verification', $user->sms_verification) == 0 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Unverified')</span>
														</label>
														<label class="selectgroup-item">
															<input type="radio" name="sms_verification" value="1"
																   class="selectgroup-input" {{ old('sms_verification', $user->sms_verification) == 1 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Verified')</span>
														</label>
													</div>
													@error('sms_verification')
													<span class="text-danger" role="alert">
														<strong>{{ __($message) }}</strong>
													</span>
													@enderror
											</div>
											<div class="col-md-4 mt-4">
													<label>@lang('User Status')</label>
													<div class="selectgroup w-100">
														<label class="selectgroup-item">
															<input type="radio" name="status" value="0"
																   class="selectgroup-input" {{ old('status', $user->status) == 0 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Suspend')</span>
														</label>
														<label class="selectgroup-item">
															<input type="radio" name="status" value="1"
																   class="selectgroup-input" {{ old('status', $user->status) == 1 ? 'checked' : ''}}>
															<span class="selectgroup-button">@lang('Active')</span>
														</label>
													</div>
													@error('status')
													<span class="text-danger" role="alert">
														<strong>{{ __($message) }}</strong>
													</span>
													@enderror
											</div>

											<div class="col-sm-12 col-md-12 mt-4">
												<div class="mb-4">
													<label class="col-form-label">@lang("Profile Picture")</label>
													<div id="image-preview" class="image-preview"
														 style="background-image: url({{ getFile($userProfile->driver, $userProfile->profile_picture)}}">
														<label for="image-upload"
															   id="image-label">@lang('Choose File')</label>
														<input type="file" name="image" class=""
															   id="image-upload"/>
													</div>
													@error('image')
													<span class="text-danger">{{ $message }}</span>
													@enderror
												</div>
											</div>
										</div>
										<button type="submit"
												class="btn btn-primary btn-sm btn-block">@lang('Update Profile')</button>
									</form>
								</div>
							</div>
						</div>

						<div class="col-lg-5">
							<div class="card mb-4 card-primary shadow">
								<div class="card-body">
								<span class="py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="mb-1 font-weight-bold text-primary">@lang('User Current Balance')</h6>
								</span>
									<ul class="list-group">
										<li class="list-group-item d-flex flex-row justify-content-between align-items-center">
										<span>
											@lang('Balance') &nbsp;
										</span>
											<span>
											{{ config('basic.currency_symbol') . ' ' . getAmount($user->balance) }}
										</span>
										</li>
									</ul>
								</div>


								<div class="card-body">
									<h6 class="mb-3 font-weight-bold text-primary">@lang('User Transaction Details')</h6>
									<div class="row">
										<div class="col-md-6 mb-sm-3">
											<a href="">
												<div class="card card-statistic-1 shadow-sm branch-box">
													<div class="card-icon bg-primary">
														<i class="fas fa-funnel-dollar"></i>
													</div>
													<div class="card-wrap">
														<div class="card-header">
															<h4>@lang('Total Deposit')</h4>
														</div>
														<div class="card-body">
															{{trans(config('basic.currency_symbol'))}}{{getAmount($totalDeposit, config('basic.fraction_number'))}}
														</div>
													</div>
												</div>
											</a>

										</div>
										<div class="col-md-6 mb-sm-3">
											<a href="">
												<div class="card card-statistic-1 shadow-sm branch-box">
													<div class="card-icon bg-primary">
														<i class="fas fa-hand-holding-usd"></i>
													</div>
													<div class="card-wrap">
														<div class="card-header">
															<h4>@lang('Total Payout')</h4>
														</div>
														<div class="card-body">
															{{trans(config('basic.currency_symbol'))}}{{getAmount($totalPayout, config('basic.fraction_number'))}}
														</div>
													</div>
												</div>
											</a>
										</div>
									</div>
								</div>


								<div class="card-body">
									<h6 class="mb-3 font-weight-bold text-primary">@lang('User Shipment Details')</h6>
									<div class="row">
										<div class="col-md-6 mb-3">
											<div class="card card-statistic-1 shadow-sm branch-box">
												<div class="card-icon bg-primary">
													<i class="fas fa-shipping-fast"></i>
												</div>
												<div class="card-wrap">
													<div class="card-header">
														<h4>@lang('Total Shipments')</h4>
													</div>
													<div class="card-body">
														{{ $shipmentRecord['totalShipments'] }}
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6 mb-3">
											<div class="card card-statistic-1 shadow-sm branch-box">
												<div class="card-icon bg-primary">
													<i class="fas fa-truck"></i>
												</div>
												<div class="card-wrap">
													<div class="card-header">
														<h4>@lang(optional(basicControl()->operatorCountry)->name) @lang('Shipments')</h4>
													</div>
													<div class="card-body">
														{{ $shipmentRecord['totalOperatorCountryShipments'] }}
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
														<h4>@lang('Total Transaction')</h4>
													</div>
													<div class="card-body">
														{{trans(config('basic.currency_symbol'))}}{{getAmount($transactionRecord['totalShipmentTransactions'], config('basic.fraction_number'))}}
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6 mb-3">
											<div class="card card-statistic-1 shadow-sm branch-box">
												<div class="card-icon bg-primary">
													<i class="fas fa-plane"></i>
												</div>
												<div class="card-wrap">
													<div class="card-header">
														<h4>@lang('Internationally Shipments')</h4>
													</div>
													<div class="card-body">
														{{ $shipmentRecord['totalInternationallyShipments'] }}
													</div>
												</div>
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
	<div class="modal fade" id="balance">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				@if(in_array($user->user_type, [1,2]))
					<form method="post" action="{{ route('client.balance.update',$user->id) }}"
						  enctype="multipart/form-data">
						@else
							<form method="post" action="{{ route('user.balance.update',$user->id) }}"
								  enctype="multipart/form-data">
								@endif
								@csrf
								<!-- Modal Header -->
								<div class="modal-header modal-colored-header bg-primary">
									<h4 class="modal-title text-white">@lang('Add / Subtract Balance')</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<!-- Modal body -->
								<div class="modal-body">
									<div class="form-group ">
										<label>@lang('Amount')</label>
										<div class="input-group">
											<input class="form-control" type="text" name="balance" id="balance">
											<div class="input-group-prepend">
												<span class="form-control">{{config('basic.base_currency')}}</span>
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="selectgroup w-100">
											<label class="selectgroup-item">
												<input type="radio" name="add_status" value="1"
													   class="selectgroup-input" checked>
												<span class="selectgroup-button">@lang('Add Balance')</span>
											</label>
											<label class="selectgroup-item">
												<input type="radio" name="add_status" value="0"
													   class="selectgroup-input">
												<span class="selectgroup-button">@lang('Substruct Balance')</span>
											</label>
										</div>
									</div>
								</div>
								<!-- Modal footer -->
								<div class="modal-footer">
									<button type="button" class="btn btn-light" data-dismiss="modal">
										<span>@lang('Close')</span>
									</button>
									<button type="submit" class=" btn btn-primary balanceSave">
										<span>@lang('Submit')</span>
									</button>
								</div>

							</form>
			</div>
		</div>
	</div>
@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
@endpush

@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {

			$.uploadPreview({
				input_field: "#image-upload",
				preview_box: "#image-preview",
				label_field: "#image-label",
				label_default: "Choose File",
				label_selected: "Change File",
				no_label: false
			});

			$('[data-toggle="tooltip"]').tooltip();
			$(document).on('change', '.file-upload-input', function () {
				let _this = $(this);
				let reader = new FileReader();
				reader.readAsDataURL(this.files[0]);
				reader.onload = function (e) {
					$('.img-profile-view').attr('src', e.target.result);
				}
			});
		});

		$(document).on('click', '.balanceSave', function () {
			var bala = $('#balance').text();
		});
	</script>

@endsection
