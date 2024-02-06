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
								<button type="button" class="btn btn-primary" id="btn-confirm"
										onClick="payWithRave()">@lang('Pay Now')</button>
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
	<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
	<script>
		'use strict';
            let btn = document.querySelector("#btn-confirm");
            btn.setAttribute("type", "button");
            const API_publicKey = "{{$data->API_publicKey }}";

            function payWithRave() {
                let x = getpaidSetup({
                    PBFPubKey: API_publicKey,
                    customer_email: "{{ $data->customer_email }}",
                    amount: "{{ $data->amount }}",
                    customer_phone: "{{ $data->customer_phone }}",
                    currency: "{{ $data->currency }}",
                    txref: "{{ $data->txref }}",
                    onclose: function () {
                    },
                    callback: function (response) {
                        let txref = response.tx.txRef;
                        let status = response.tx.status;
                        window.location = '{{ url('payment/flutterwave') }}/' + txref + '/' + status;
                    }
                });
            }
	</script>
@endsection
