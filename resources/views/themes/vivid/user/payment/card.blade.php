@extends($theme.'layouts.user')
@section('title')
	{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection
@push('extra_styles')
	<link href="{{ asset('assets/dashboard/css/card-js.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
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
					<div class="card mb-4 card-primary shadow">
						<div class="card-body">
							<div class="d-flex justify-content-center align-items-center">
								<form class="form-horizontal" id="example-form"
									  action="{{ route('ipn', [optional($deposit->gateway)->code ?? '', $deposit->utr]) }}"
									  method="post">
									<fieldset>
										<legend>@lang('Your Card Information')</legend>
										<div class="card-js form-group">
											<input class="card-number form-control" name="card_number"
												   placeholder="@lang('Enter your card number')" autocomplete="off"
												   required>
											<input class="name form-control" id="the-card-name-id" name="card_name"
												   placeholder="@lang('Enter the name on your card')" autocomplete="off"
												   required>
											<input class="expiry form-control" autocomplete="off" required>
											<input class="expiry-month" name="expiry_month">
											<input class="expiry-year" name="expiry_year">
											<input class="cvc form-control" name="card_cvc" autocomplete="off" required>
										</div>
										<button type="submit" class="btn btn-primary">@lang('Submit')</button>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
@push('extra_scripts')
	<script src="{{ asset('assets/dashboard/js/card-js.min.js') }}"></script>
@endpush
