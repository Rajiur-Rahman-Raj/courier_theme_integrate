@extends('admin.layouts.master')
@section('page_title', __('Payout Details'))

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Payout Details')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Payout Details')</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row justify-content-md-center">
						<div class="col-lg-12">
							<div class="card mb-4 card-primary shadow">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Payout List')</h6>
									<a href="{{ route('admin.payout.index')}}" class="btn btn-sm btn-outline-primary"> <i
												class="fas fa-arrow-left"></i> @lang('Back')</a>
								</div>
								<div class="card-body">
									<ul class="list-group">
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Sender name')</span><span> {{ __(optional($payout->user)->name ?? __('N/A')) }} </span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Payment method')</span><span> {{ __(optional($payout->payoutMethod)->methodName) }} </span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Transaction Id')</span><span> {{ __($payout->utr) }} </span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Email')</span><span> {{ __($payout->email) }} </span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Status')</span>
											<span>
												@if($payout->status == 0)
													<span class="text-warning">@lang('Pending')</span>
												@elseif($payout->status == 1)
													<span class="text-info">@lang('Generated')</span>
												@elseif($payout->status == 2)
													<span class="text-success">@lang('Payment Done')</span>
												@elseif($payout->status == 5)
													<span class="text-danger">@lang('Canceled')</span>
												@endif
											</span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Charge')</span>
											<span>
												{{ (getAmount($payout->amount)).' '.__(config('basic.base_currency')) }}
											</span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Deduct amount from user')</span><span> {{ (getAmount($payout->transfer_amount)).' '.__(config('basic.base_currency')) }} </span>
										</li>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>@lang('Payable Amount')</span>
											<span>
												{{ (getAmount($payout->received_amount)).' '.__(config('basic.base_currency')) }}
											</span>
										</li>
									</ul>
									@if(isset($payout->withdraw_information))
										<ul class="list-group mt-4">
											<h5>@lang('Withdraw Information')</h5>
											<div class="progress mb-3 h-2">
												<div class="progress-bar w-25" role="progressbar" aria-valuenow="25"
													aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											@foreach(json_decode($payout->withdraw_information) as $key => $value)
												<li class="list-group-item list-group-item-action d-flex justify-content-between">
													<span>{{ __(snake2Title($key)) }}</span>
													<span>
														@if($value->type == 'file')
															<img class="img-profile rounded-circle"
																src="{{asset('assets/upload/payoutFile').'/'.$value->fieldValue }}">
														@else
															{{ __($value->fieldValue) }}
														@endif
													</span>
												</li>
											@endforeach
										</ul>
									@endif
									@if(isset($payout->note))
									<ul class="list-group mt-4">
										<h5>@lang('Note')</h5>
										<div class="progress mb-3 h-2">
											<div class="progress-bar w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0"
												aria-valuemax="100"></div>
										</div>
										<li class="list-group-item list-group-item-action d-flex justify-content-between">
											<span>{{ __($payout->note) }}</span>
										</li>
									</ul>
									@endif
									<div class="card-footer float-right">
										@if($payout->status == 1)
											<a href="{{ route('admin.user.payout.confirm',$payout->utr) }}"
											data-target="#confirmModal" data-toggle="modal"
											class="btn btn-success btn-sm confirmButton">@lang('Confirm')</a>
											<a href="{{ route('admin.user.payout.cancel',$payout->utr) }}" data-toggle="modal"
											data-target="#confirmModal"
											class="btn btn-danger btn-sm confirmButton">@lang('Reject')</a>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</section>
	</div>

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-info-circle"></i> @lang('Confirmation !')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="confirmForm">
                    <div class="modal-body text-center">
                        <p>@lang('Are you sure you want to confirm this action?')</p>
                        @csrf
                        <div class="form-group">
                            <label for="note">@lang('Note')</label>
                            <textarea name="note" rows="5" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary"
                                data-dismiss="modal">@lang('Close')</button>
                        <input type="submit" class="btn btn-primary" value="@lang('Confirm')">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        'use strict';
       $(document).ready(function () {
           $(document).on('click', '.confirmButton', function (e) {
               e.preventDefault();
               let submitUrl = $(this).attr('href');
               $('#confirmForm').attr('action', submitUrl)
           })
       })
    </script>
@endsection
