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
								<button type="button" class="btn btn-primary" id="btn-confirm">@lang('Pay with VoguePay')</button>
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
	<script src="https://pay.voguepay.com/js/voguepay.js"></script>
	<script>
		closedFunction = function () {

		}
		successFunction = function (transaction_id) {
			let txref = "{{ $data->merchant_ref }}";
			window.location.href = '{{ url('payment/voguepay') }}/' + txref + '/' + transaction_id;
		}
		failedFunction = function (transaction_id) {
			window.location.href = '{{ route('failed') }}';
		}

		function pay(item, price) {
			Voguepay.init({
				v_merchant_id: "{{ $data->v_merchant_id }}",
				total: price,
				notify_url: "{{ $data->notify_url }}",
				cur: "{{ $data->cur }}",
				merchant_ref: "{{ $data->merchant_ref }}",
				memo: "{{ $data->memo }}",
				developer_code: '5af93ca2913fd',
				custom: "{{ $data->custom }}",
                customer: {
                    name: "{{ $data->customer_name }}",
                    address: "{{ $data->customer_address }}",
                    email: "{{ $data->customer_email }}"
                },
				closed: closedFunction,
				success: successFunction,
				failed: failedFunction
			});
		}
		$(document).ready(function () {
			$(document).on('click', '#btn-confirm', function (e) {
				e.preventDefault();
				pay('Buy', {{ $data->Buy }});
			});
		});
	</script>

@stop
