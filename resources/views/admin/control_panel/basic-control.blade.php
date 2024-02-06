@extends('admin.layouts.master')
@section('page_title', __('Basic Control'))
@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Basic Control')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Basic Control')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-4 col-lg-3">
						@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
					</div>
					<div class="col-12 col-md-8 col-lg-9">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-md-center">
								<div class="col-lg-12">

									<div class="bd-callout bd-callout-warning mx-2">
										<i class="fas fa-info-circle mr-2"></i> @lang("If you get 500(server error) for some reason, please turn on <b>Error Log</b> and try again. Then you can see what was missing in your system.")
									</div>

									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Basic Control')</h6>
										</div>
										<div class="card-body">
											<form action="{{ route('basic.control') }}" method="post">
												@csrf
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label for="site_title">@lang('Site Title')</label>
															<input type="text" name="site_title"
																   value="{{ old('site_title',$basicControl->site_title) }}"
																   placeholder="@lang('Site Title')"
																   class="form-control @error('site_title') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('site_title') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-6 mb-3">
														<div class="form-group">
															<label for="time_zone">@lang('Time Zone')</label>
															<select name="time_zone"
																	class="select2-single form-control @error('time_zone') is-invalid @enderror"
																	id="time_zone">
																@foreach(timezone_identifiers_list() as $key => $value)
																	<option
																		value="{{$value}}" {{  (old('time_zone',$basicControl->time_zone) == $value ? ' selected' : '') }}>{{ __($value) }}</option>
																@endforeach
															</select>
															<div
																class="invalid-feedback">@error('time_zone') @lang($message) @enderror</div>
														</div>
													</div>

													<div class="col-md-6 mb-3">
														<div class="form-group">
															<label
																for="operator_country">@lang('Operator Country') </label>
															<span><span class="">(@lang('Select the country from which you are handling your business'))</span></span>
															<select name="operator_country"
																	class="select2-single form-control @error('operator_country') is-invalid @enderror"
																	id="operator_country">
																@foreach($allCountries as $key => $country)
																	<option
																		value="{{ $country->id }}" {{ old('operator_country', $country->id == $basicControl->operator_country ? 'selected' : '') }}>{{ __($country->name) }}</option>
																@endforeach
															</select>
															<div
																class="invalid-feedback">@error('operator_country') @lang($message) @enderror</div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label for="base_currency">@lang('Base Currency')</label>
															<input type="text" name="base_currency"
																   value="{{ old('base_currency',$basicControl->base_currency) }}"
																   placeholder="@lang('Base Currency')"
																   class="form-control @error('base_currency') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('base_currency') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label
																for="currency_symbol">@lang('Currency Symbol')</label>
															<input type="text" name="currency_symbol"
																   value="{{ old('currency_symbol',$basicControl->currency_symbol) }}"
																   placeholder="@lang('Currency Symbol')"
																   class="form-control @error('currency_symbol') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('currency_symbol') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="site_title">@lang('Fraction number')</label>
															<input type="text" name="fraction_number"
																   value="{{ old('fraction_number',$basicControl->fraction_number) }}"
																   placeholder="@lang('Fraction number')"
																   class="form-control @error('fraction_number') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('fraction_number') @lang($message) @enderror</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="paginate">@lang('Paginate')</label>
															<input type="text" name="paginate"
																   value="{{ old('paginate',$basicControl->paginate) }}"
																   placeholder="@lang('Paginate')"
																   class="form-control @error('paginate') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('paginate') @lang($message) @enderror</div>
														</div>
													</div>

													<div class="col-md-6 mb-3">
														<div class="form-group">
															<label for="refund_time">@lang('Refund Time')</label>
															<select name="refund_time"
																	class="select2-single form-control @error('refund_time') is-invalid @enderror"
																	id="refund_time">
																<option
																	value="1_minute" {{ old('refund_time', $basicControl->refund_time == '1_minute' ? 'selected' : '') }}>@lang('1 minute')</option>
																<option
																	value="5_minute" {{ old('refund_time', $basicControl->refund_time == '5_minute' ? 'selected' : '') }}>@lang('5 minutes')</option>
																<option
																	value="10_minute" {{ old('refund_time', $basicControl->refund_time == '1_hour' ? 'selected' : '') }}>@lang('10 minutes')</option>
																<option
																	value="30_minute" {{ old('refund_time', $basicControl->refund_time == '10_minute' ? 'selected' : '') }}>@lang('30 minutes')</option>
																<option
																	value="1_hour" {{ old('refund_time', $basicControl->refund_time == '30_minute' ? 'selected' : '') }}>@lang('1 Hour')</option>
																<option
																	value="2_hour" {{ old('refund_time', $basicControl->refund_time == '2_hour' ? 'selected' : '') }}>@lang('2 Hours')</option>
																<option
																	value="3_hour" {{ old('refund_time', $basicControl->refund_time == '3_hour' ? 'selected' : '') }}>@lang('3 Hours')</option>
																<option
																	value="6_hour" {{ old('refund_time', $basicControl->refund_time == '6_hour' ? 'selected' : '') }}>@lang('6 Hours')</option>
																<option
																	value="12_hours {{ old('refund_time', $basicControl->refund_time == '12_hours' ? 'selected' : '') }}">@lang('12 Hours')</option>
																<option
																	value="1_day" {{ old('refund_time', $basicControl->refund_time == '1_day' ? 'selected' : '') }}>@lang('1 day')</option>
																<option
																	value="2_day" {{ old('refund_time', $basicControl->refund_time == '2_day' ? 'selected' : '') }}>@lang('2 days')</option>
																<option
																	value="3_day" {{ old('refund_time', $basicControl->refund_time == '3_day' ? 'selected' : '') }}>@lang('3 days')</option>
																<option
																	value="7_day" {{ old('refund_time', $basicControl->refund_time == '7_day' ? 'selected' : '') }}>@lang('7 days')</option>
															</select>

															<div
																class="invalid-feedback">@error('operator_country') @lang($message) @enderror</div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label>@lang('Strong Password')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio" name="strong_password"
																		   value="0"
																		   class="selectgroup-input" {{ old('strong_password', $basicControl->strong_password) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio" name="strong_password"
																		   value="1"
																		   class="selectgroup-input" {{ old('strong_password', $basicControl->strong_password) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('strong_password')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>


													<div class="col-md-3">
														<div class="form-group">
															<label>@lang('Registration')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio" name="registration"
																		   value="0"
																		   class="selectgroup-input" {{ old('registration', $basicControl->registration) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio" name="registration"
																		   value="1"
																		   class="selectgroup-input" {{ old('registration', $basicControl->registration) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('registration')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label>@lang('Error log')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio" name="error_log" value="0"
																		   class="selectgroup-input" {{ old('error_log', $basicControl->error_log) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio" name="error_log" value="1"
																		   class="selectgroup-input" {{ old('error_log', $basicControl->error_log) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('error_log')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label>@lang('Cron Set Up Pop Up')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="is_active_cron_notification" value="0"
																		   class="selectgroup-input" {{ old('is_active_cron_notification', $basicControl->is_active_cron_notification) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="is_active_cron_notification" value="1"
																		   class="selectgroup-input" {{ old('is_active_cron_notification', $basicControl->is_active_cron_notification) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('is_active_cron_notification')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>


												</div>
												<div class="row mb-2">
													<div class="col-md-12">
														<h6 class="my-3 font-weight-bold text-dark">@lang('Color Settings')</h6>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label for="primaryColor">@lang('Primary Color')</label>
															<input type="color" name="primaryColor"
																   value="{{ old('primaryColor',$basicControl->primaryColor) }}"
																   class="form-control @error('primaryColor') is-invalid @enderror">
															<div
																class="invalid-feedback">@error('primaryColor') @lang($message) @enderror</div>
														</div>
													</div>


												</div>
												<div class="form-group">
													<button type="submit" name="submit"
															class="btn btn-primary btn-sm btn-block">@lang('Save changes')</button>
												</div>
											</form>
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
@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/select2.min.js') }}"></script>
@endpush
@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$('.select2-single').select2();
			$(document).on('change', '#base_currency', function (e) {
				e.preventDefault();
				$('.joining-currency').html($('#base_currency :selected').data("code"));
			});
		})
	</script>
@endsection
