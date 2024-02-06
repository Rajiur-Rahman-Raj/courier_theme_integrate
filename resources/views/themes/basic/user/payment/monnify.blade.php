@extends($theme.'layouts.user')
@section('title')
	{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection

@section('content')
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1>{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h1>
			<div class="section-header-breadcrumb">
				<div class="breadcrumb-item active">
					<a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
				</div>
				<div class="breadcrumb-item">{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-md-5">
				<div class="card card-primary shadow">
					<div class="card-body">
						<div class="row justify-content-center">
							<div class="col-md-3">
								<img src="{{getFile(config('location.gateway.path').optional($deposit->gateway)->image)}}"
									 class="card-img-top gateway-img">
							</div>
							<div class="col-md-6">
								<h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
								<button class="btn btn-primary" onclick="payWithMonnify()">@lang('Pay Now')</button>
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
	<script type="text/javascript" src="//sdk.monnify.com/plugin/monnify.js"></script>
	<script type="text/javascript">
		'use strict';
            function payWithMonnify() {
                MonnifySDK.initialize({
                    amount: {{ $data->amount }},
                    currency: "{{ $data->currency }}",
                    reference: "{{ $data->ref }}",
                    customerName: "{{$data->customer_name }}",
                    customerEmail: "{{$data->customer_email }}",
                    customerMobileNumber: "{{ $data->customer_phone }}",
                    apiKey: "{{ $data->api_key }}",
                    contractCode: "{{ $data->contract_code }}",
                    paymentDescription: "{{ $data->description }}",
                    isTestMode: true,
                    onComplete: function (response) {
                        if (response.paymentReference) {
                            window.location.href = '{{ route('ipn', ['monnify', $data->ref]) }}';
                        } else {
                            window.location.href = '{{ route('failed') }}';
                        }
                    },
                    onClose: function (data) {
                    }
                });
            }
	</script>
@endsection
