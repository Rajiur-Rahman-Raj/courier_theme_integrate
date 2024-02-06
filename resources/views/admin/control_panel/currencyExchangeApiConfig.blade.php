@extends('admin.layouts.master')
@section('page_title', __('Currency Exchange Api Config'))
@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Currency Exchange Api Config')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Currency Exchange Api Config')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-4 col-lg-3">
						@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
					</div>
					<div class="col-12 col-md-8 col-lg-9">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-center">
								<div class="col-lg-7">
									<div class="card card-primary shadow mb-4">
										<form action="{{ route('currency.exchange.api.config') }}" method="post">
											@csrf
											<div
												class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
												<h6 class="m-0 font-weight-bold text-primary">@lang('CurrencyLayer Api Config (Fiat Currency)')</h6>
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="currency_layer_access_key">@lang('Currency Layer Access Key')</label>
															<input type="text" name="currency_layer_access_key"
																   value="{{ old('currency_layer_access_key',$basicControl->currency_layer_access_key) }}"
																   placeholder="@lang('Enter your currency layer access key')"
																   class="form-control @error('currency_layer_access_key') is-invalid @enderror">
															<div class="invalid-feedback">
																@error('currency_layer_access_key') @lang($message) @enderror
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="currency_layer_auto_update_at">@lang('Select Update Time')</label>
															<select name="currency_layer_auto_update_at"
																	class="select2-single form-control form-control-sm">
																@foreach(config('basic.schedule_list') as $key => $value)
																	<option
																		value="{{ $key }}" {{ $key == old('currency_layer_auto_update_at',$basicControl->currency_layer_auto_update_at) ? 'selected' : '' }}> @lang($value)</option>
																@endforeach
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>@lang('Auto Update Currency Rate')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="currency_layer_auto_update" value="0"
																		   class="selectgroup-input" {{ old('currency_layer_auto_update', $basicControl->currency_layer_auto_update) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="currency_layer_auto_update" value="1"
																		   class="selectgroup-input" {{ old('currency_layer_auto_update', $basicControl->currency_layer_auto_update) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('currency_layer_auto_update')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>
												</div>
											</div>
											<div
												class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
												<h6 class="m-0 font-weight-bold text-primary">@lang('CoinMarketCap Api Config (Crypto Currency)')</h6>
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="coin_market_cap_app_key">@lang('Coin Market Cap App Key')</label>
															<input type="text" name="coin_market_cap_app_key"
																   value="{{ old('coin_market_cap_app_key',$basicControl->coin_market_cap_app_key) }}"
																   placeholder="@lang('Enter your coin market cap app key')"
																   class="form-control @error('coin_market_cap_app_key') is-invalid @enderror">
															<div class="invalid-feedback">
																@error('coin_market_cap_app_key') @lang($message) @enderror
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label
																for="coin_market_cap_auto_update_at">@lang('Select Update Time')</label>
															<select name="coin_market_cap_auto_update_at"
																	class="select2-single form-control form-control-sm">
																@foreach(config('basic.schedule_list') as $key => $value)
																	<option
																		value="{{ $key }}" {{ $key == old('coin_market_cap_auto_update_at',$basicControl->coin_market_cap_auto_update_at) ? 'selected' : '' }}> @lang($value)</option>
																@endforeach
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>@lang('Auto Update Currency Rate')</label>
															<div class="selectgroup w-100">
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="coin_market_cap_auto_update" value="0"
																		   class="selectgroup-input" {{ old('coin_market_cap_auto_update', $basicControl->coin_market_cap_auto_update) == 0 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('OFF')</span>
																</label>
																<label class="selectgroup-item">
																	<input type="radio"
																		   name="coin_market_cap_auto_update" value="1"
																		   class="selectgroup-input" {{ old('coin_market_cap_auto_update', $basicControl->coin_market_cap_auto_update) == 1 ? 'checked' : ''}}>
																	<span class="selectgroup-button">@lang('ON')</span>
																</label>
															</div>
															@error('coin_market_cap_auto_update')
															<span class="text-danger" role="alert">
																<strong>{{ __($message) }}</strong>
															</span>
															@enderror
														</div>
													</div>
												</div>
											</div>
											<div class="card-footer">
												<button type="submit"
														class="btn btn-block btn-primary">@lang('Save Changes')</button>
											</div>
										</form>
									</div>
								</div>
								<div class="col-lg-5">
									<div class="row">
										<div class="col-12">
											<div class="card card-primary shadow mb-4">
												<div
													class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
													<h6 class="m-0 font-weight-bold text-primary">@lang('Currency Layer Instructions')</h6>
												</div>
												<div class="card-body">
													@lang('Currencylayer provides a simple REST API with real-time and historical exchange rates for 168 world currencies,
													delivering currency pairs in universally usable JSON format - compatible with any of your applications.
													<br><br>
													Spot exchange rate data is retrieved from several major forex data providers in real-time, validated,
													processed and delivered hourly, Every 10 minutes, or even within the 60-second market window.')
													<a href="https://currencylayer.com/product"
													   target="_blank">@lang('Create an account') <i
															class="fas fa-external-link-alt"></i></a>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12">
											<div class="card card-primary shadow mb-4">
												<div
													class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
													<h6 class="m-0 font-weight-bold text-primary">@lang('Coin Market Cap Instructions')</h6>
												</div>
												<div class="card-body">
													@lang('CoinMarketCap is the world\'s most-referenced price-tracking website for cryptoassets in the rapidly growing cryptocurrency space.
													Its mission is to make crypto discoverable and efficient globally by empowering retail users with unbiased,
													high quality and accurate information for drawing their own informed conclusions.
													Get your free API keys')
													<a href="https://dashboard.pusher.com/accounts/sign_up"
													   target="_blank">@lang('Create an account') <i
															class="fas fa-external-link-alt"></i></a>
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
@endsection

@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/select2.min.js') }}"></script>
@endpush

@section('scripts')
	<script>
		'use strict'
		$(document).ready(function () {
			$('.select2-single').select2();
			$(document).on('click', '.copy-btn', function () {
				let _this = $(this)[0];
				let copyText = $(this).parents('.input-group-append').siblings('input');
				$(copyText).prop('disabled', false);
				copyText.select();
				document.execCommand("copy");
				$(copyText).prop('disabled', true);
				$(this).text('Coppied');
				setTimeout(function () {
					$(_this).text('');
					$(_this).html('<i class="fas fa-copy"></i>');
				}, 500)
			});
		});
	</script>
@endsection
