@extends($theme.'layouts.user')
@section('page_title')
	{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection
@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/stripe.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header mt-3">
				<h2 class="text-center mb-4">{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h2>
			</div>

			<div class="row justify-content-center">
				<div class="col-md-5">
					<div class="card card-primary shadow">
						<div class="card-body">
							<div class="row justify-content-center">
								<div class="col-md-3">
									<img src="{{ getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image) }}" class="card-img-top gateway-img">
								</div>
								<div class="col-md-6">

									<h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
									<form action="{{ $data->url }}" method="{{ $data->method }}">
										<script src="{{ $data->src }}" class="stripe-button"
												@foreach($data->val as $key=> $value)
													data-{{$key}}="{{$value}}"
											@endforeach>
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
@push('extra_scripts')
	<script src="https://js.stripe.com/v3/"></script>
@endpush


