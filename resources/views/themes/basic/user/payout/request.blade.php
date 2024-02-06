@extends($theme.'layouts.user')
@section('page_title',__('Send Payout Request'))

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading">
					<h2 class="mb-0">@lang('Send Payout Request')</h2>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="bd-callout bd-callout-primary text-dark font-weight-bold">
							<i class="base_color fas fa-info-circle"></i> @lang("Withdraw your money from here")
						</div>
					</div>
				</div>
				<div class="row">
					<section class="profile-setting p-3">
						<div class="row justify-content-md-center">
							<div class="col-md-6 mb-3">
								<div class="sidebar-wrapper">
									<h5 class="mb-3 font-weight-bold">@lang('Choose a payout method')</h5>
									<form action="{{ route('payout.request') }}" method="post">
										@csrf
										<div class="row payout">
											<div class="col-md-12">
												<div class="input-box text-center">
													@foreach($payoutMethods as $key => $value)
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="radio"
																   name="methodId"
																   id="{{ $key }}"
																   value="{{ $value->id }}" {{ old('methodId') == $value->id ? ' checked' : ''}}>
															<label class="form-check-label" for="{{ $key }}">
																<img
																	src="{{ getFile($value->driver,$value->logo) }}">
																<span>{{ __($value->methodName) }}</span>
															</label>
														</div>
													@endforeach
												</div>
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-md-12">
												<div class="input-box">
													<label for="amount">@lang('Amount')</label>
													<div class="input-group append">
														<input type="text" name="amount"
															   value="{{ old('amount') }}"
															   placeholder="@lang('Enter Amount')"
															   class="form-control @error('amount') is-invalid @enderror">
														<button type="button" class="btn-modify cmn_btn">
															<span class="">@lang($baseControl->base_currency)</span>
														</button>
													</div>
													<div
														class="text-danger">@error('amount') @lang($message) @enderror</div>
												</div>
											</div>
										</div>
										<button type="submit" id="submit"
												class="cmn_btn mt-3">@lang('Continue')</button>
									</form>
								</div>
							</div>
							<div class="col-md-6">
								<div class="sidebar-wrapper">
									<h6 class="m-0 text-dark font-weight-bold">@lang('Details')</h6>
									<div class="card-body showCharge d-none">
										<ul class="list-group">
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span>@lang('Fixed charge')</span>
												<span class="text-danger" id="fixed_charge"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span>@lang('Percentage charge')</span><span class="text-danger"
																							 id="percentage_charge"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span>@lang('Min limit')</span>
												<span class="text-info" id="min_limit"></span>
											</li>
											<li class="list-group-item d-flex justify-content-between align-items-center">
												<span>@lang('Max limit')</span>
												<span class="text-info" id="max_limit"></span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script>
		'use strict';
		$(document).ready(function () {
			$(document).on('input', 'input[name="amount"]', function () {
				let limit = '{{ $baseControl->fraction_number }}';
				let amount = $(this).val();
				let fraction = amount.split('.')[1];
				if (fraction && fraction.length > limit) {
					amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
					$(this).val(amount);
				}
			});

			$(document).on('change', "input[type=radio][name=methodId]", function (e) {
				let methodId = this.value;
				$.ajax({
					method: "GET",
					url: "{{ route('payout.checkLimit') }}",
					dataType: "json",
					data: {'methodId': methodId}
				})
					.done(function (response) {
						let amountField = $('#amount');
						if (response.status) {
							$('.showCharge').removeClass('d-none');
							$('#fixed_charge').html(response.fixed_charge + ' ' + response.currency_code);
							$('#percentage_charge').html(response.percentage_charge + ' ' + response.currency_code);
							$('#min_limit').html(parseFloat(response.min_limit).toFixed(response.currency_limit) + ' ' + response.currency_code);
							$('#max_limit').html(parseFloat(response.max_limit).toFixed(response.currency_limit) + ' ' + response.currency_code);
						} else {
							$('.showCharge').addClass('d-none');
						}
					});
			});
		});
	</script>
@endsection
