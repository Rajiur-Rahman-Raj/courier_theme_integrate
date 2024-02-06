@extends($theme.'layouts.user')
@section('page_title')
	{{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection

@section('content')
	<section id="dashboard" class="p-0">
		<div class="container add-fund pb-50 mt-4">
			<div class="row profile-setting">
				<div class="col-md-12">
					<div class="card secbg br-4">
						<div class="card-body ">
							<div class="row ">
								<div class="col-md-12">
									<h3 class="title text-center mt-3">{{trans('Please follow the instruction below')}}</h3>
									<p class="text-center mt-2 ">{{trans('You have requested to deposit')}} <b
											class="text--base">{{getAmount($deposit->amount)}}
											{{$basic->base_currency}}</b> , {{trans('Please pay')}}
										<b class="text--base">{{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</b> {{trans('for successful payment')}}
									</p>

									<p class=" mt-2 ">
										<?php echo optional($deposit->gateway)->note; ?>
									</p>


									<form action="{{route('addFund.fromSubmit',$deposit->utr)}}" method="post"
										  enctype="multipart/form-data"
										  class="form-row  preview-form">
										@csrf
										@if(optional($deposit->gateway)->parameters)
											@foreach($deposit->gateway->parameters as $k => $v)
												@if($v->type == "text")
													<div class="col-md-12 mt-2">
														<div class="form-group input-box">
															<label>{{trans($v->field_level)}} @if($v->validation == 'required')
																	<span class="text--danger">*</span>
																@endif </label>
															<input type="text" name="{{$k}}"
																   class="form-control bg-transparent"
																   @if($v->validation == "required") required @endif>
															@if ($errors->has($k))
																<span
																	class="text--danger">{{ trans($errors->first($k)) }}</span>
															@endif
														</div>
													</div>
												@elseif($v->type == "textarea")
													<div class="col-md-12 mt-2">
														<div class="form-group input-box">
															<label>{{trans($v->field_level)}} @if($v->validation == 'required')
																	<span class="text--danger">*</span>
																@endif </label>
															<textarea name="{{$k}}" class="form-control bg-transparent"
																	  rows="3"
																	  @if($v->validation == "required") required @endif></textarea>
															@if ($errors->has($k))
																<span
																	class="text--danger">{{ trans($errors->first($k)) }}</span>
															@endif
														</div>
													</div>
												@elseif($v->type == "file")
													<div class="col-md-12 mt-2">
														<label>{{trans($v->field_level)}} @if($v->validation == 'required')
																<span class="text--danger">*</span>
															@endif </label>

														<div class="form-group input-box">
															<div class="fileinput fileinput-new "
																 data-provides="fileinput">
																<div class="fileinput-new thumbnail withdraw-thumbnail"
																	 data-trigger="fileinput">
																	<img style="width: 200px !important;"
																		 src="{{ getFile(config('location.default')) }}"
																		 alt="...">
																</div>
																<div
																	class="fileinput-preview fileinput-exists thumbnail wh-200-150 "></div>

																<div class="img-input-div">
                                                                <span class="btn cmn_btn btn-file">
                                                                    <span
																		class="fileinput-new "> @lang('Select') {{$v->field_level}}</span>
                                                                    <span
																		class="fileinput-exists"> @lang('Change')</span>
                                                                    <input type="file" name="{{$k}}" accept="image/*"
																		   @if($v->validation == "required") required @endif>
                                                                </span>
																	<a href="javascript:void(0)" class="btn btn-danger fileinput-exists"
																	   data-dismiss="fileinput"> @lang('Remove')</a>
																</div>

															</div>
															@if ($errors->has($k))
																<br>
																<span
																	class="text--danger">{{ __($errors->first($k)) }}</span>
															@endif
														</div>
													</div>
												@endif
											@endforeach
										@endif
										<div class="col-md-12 ">
											<div class=" form-group">
												<button type="submit" class="btn cmn_btn w-100 mt-3">
													<span>@lang('Confirm Now')</span>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	@push('extra_styles')
		<link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap-fileinput.css')}}">
	@endpush

	@push('extra_scripts')
		<script src="{{asset($themeTrue.'js/bootstrap-fileinput.js')}}"></script>
	@endpush
@endsection
