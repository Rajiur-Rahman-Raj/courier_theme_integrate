@extends($theme.'layouts.user')
@section('page_title',__('Add Fund Preview'))

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<section class="section p-0">
				<div class="section-header mt-3">
					<h2 class="text-center mb-4">@lang('Add Fund Preview')</h2>
				</div>

				<div class="row">
					<div class="container-fluid" id="container-wrapper">
						<div class="row justify-content-md-center">
							<div class="col-lg-6">
								<div class="card mb-4 shadow card-primary">
									<div
										class="card-header py-3 d-flex flex-row align-items-center justify-content-center">
										<h6 class="m-0 font-weight-bold text-dark">@lang('Preview Deposit')</h6>
									</div>
									<div class="card-body">
										<form action="{{ route('deposit.confirm',$utr) }}" method="post">
											@csrf

											<div class="text-center">
												<img class="rounded mb-5"
													 src="{{ getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image) }}"
													 width="109">
											</div>

											<ul class="list-group">
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Gateway')</span>
													<span>{{ __(optional($deposit->gateway)->name) }} </span>
												</li>
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Name')</span>
													<span> {{ __($deposit->receiver->name) }} </span>
												</li>
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Currency')</span>
													<span>{{ config('basic.base_currency') }}</span>
												</li>
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Amount')</span>
													<span>{{ (getAmount($deposit->amount)) }} <span>{{ config('basic.base_currency') }}</span></span>
												</li>
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Charge')</span>
													<span>{{ (getAmount($deposit->charge)) }} <span>{{ config('basic.base_currency') }}</span></span>
												</li>
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>@lang('Payable amount')</span>
													<span>{{ (getAmount($deposit->amount + $deposit->charge)) }} <span>{{ config('basic.base_currency') }}</span></span>
												</li>
											</ul>
											<button type="submit" id="submit"
													class="btn cmn_btn btn-sm btn-block btn-security mt-3 w-100">@lang('Confirm')</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</section>
		</div>
	</div>

@endsection
