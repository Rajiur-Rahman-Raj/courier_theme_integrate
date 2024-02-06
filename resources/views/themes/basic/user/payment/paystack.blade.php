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
									<img
										src="{{getFile(config('location.gateway.path').optional($deposit->gateway)->image)}}"
										class="card-img-top gateway-img">
								</div>
								<div class="col-md-6">
									<h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
									<button type="button" class="btn btn-primary"
											id="btn-confirm">@lang('Pay Now')</button>
									<form
										action="{{ route('ipn', [optional($deposit->gateway)->code, $deposit->utr]) }}"
										method="POST">
										@csrf
										<script src="//js.paystack.co/v1/inline.js"
												data-key="{{ $data->key }}"
												data-email="{{ $data->email }}"
												data-amount="{{$data->amount}}"
												data-currency="{{$data->currency}}"
												data-ref="{{ $data->ref }}"
												data-custom-button="btn-confirm">
										</script>
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

