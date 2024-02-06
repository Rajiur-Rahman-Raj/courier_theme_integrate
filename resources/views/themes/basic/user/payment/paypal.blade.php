@extends($theme.'layouts.user')
@section('title')
	{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Pay with PAYPAL')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Pay with PAYPAL')</div>
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col-md-5">
					<div class="card card-primary shadow">
						<div class="card-body">
							<div class="row justify-content-center">
								<div class="col-md-3">
									<img
										src="{{getFile(config('location.gateway.path').optional($deposit->gateway)->image)}}"
										class="card-img-top gateway-img">
								</div>
								<div class="col-md-6">
									<h5 class="my-2">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
									<div id="paypal-button-container"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
@section('scripts')
	<script src="https://www.paypal.com/sdk/js?client-id={{ $data->cleint_id }}&currency={{$data->currency}}"></script>
	<script>
		paypal.Buttons({
			createOrder: function (data, actions) {
				return actions.order.create({
					purchase_units: [
						{
							description: "{{ $data->description }}",
							custom_id: "{{ $data->custom_id }}",
							amount: {
								currency_code: "{{ $data->currency }}",
								value: "{{ $data->amount }}",
								breakdown: {
									item_total: {
										currency_code: "{{ $data->currency }}",
										value: "{{ $data->amount }}"
									}
								}
							}
						}
					]
				});
			},
			onApprove: function (data, actions) {
				return actions.order.capture().then(function (details) {
					var trx = "{{ $data->custom_id }}";
					window.location = '{{ url('payment/paypal') }}/' + trx + '/' + details.id
				});
			}
		}).render('#paypal-button-container');
	</script>
@endsection
