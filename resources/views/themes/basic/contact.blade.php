@extends($theme.'layouts.app')
@section('title',trans($title))

@section('banner_main_heading')
	@lang('Contact Us')
@endsection

@section('banner_heading')
	@lang('Contact')
@endsection

@section('content')
	@if(isset($contact))
		<section class="contact_page mb-0 mb-md-5 mb-lg-0">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6 order-2 order-lg-1">
						<div class="contact_message_area text-center">
							<div class="form_title text-start mb-30">
								<div class="section_subtitle"><h4>@lang($contact->title)</h4></div>
								<p>@lang($contact->sub_title)</p>
							</div>

							<form action="{{route('contact.send')}}" method="post">
								@csrf
								<div class="row">
									<div class="mb-3 col-md-6">
										<input class="form-control" type="text" name="name" value="{{old('name')}}"
											   placeholder="@lang('Full name')">
										@error('name')
										<div class="text-start text-danger">
											{{$message}}
										</div>
										@enderror
									</div>

									<div class="mb-3 col-md-6">
										<input class="form-control" type="email" name="email" value="{{old('email')}}"
											   placeholder="@lang('Email address')">
										@error('email')
										<div class="text-start text-danger">
											{{$message}}
										</div>
										@enderror
									</div>

									<div class="mb-3 col-md-12">
										<input class="form-control" type="text" name="subject"
											   value="{{old('subject')}}" placeholder="@lang('Subject')">
										@error('subject')
										<div class="text-start text-danger">
											{{$message}}
										</div>
										@enderror
									</div>
									<div class="mb-3 col-12">
									<textarea class="form-control" cols="30" rows="3" name="message"
											  placeholder="@lang('Your message')"
											  id="exampleFormControlTextarea1">{{old('message')}}</textarea>
										@error('message')
										<div class="text-start text-danger">
											{{$message}}
										</div>
										@enderror
									</div>
								</div>
								<div class="btn_area d-flex">
									<button type="submit" class="cmn_btn mt-30 w-100">@lang('Send a massage')</button>
								</div>
							</form>
						</div>
					</div>
					<div class="col-lg-6 order-1 order-lg-2">
						<div class="section_right">
							<div class="image_area">
								<img src="{{ asset($themeTrue.'images/contact_icon.gif') }}" alt="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	@endif
	@if(isset($contact))
		<div class="contact_area">
			<div class="contact_top pb-100">

			</div>
			<div class="contact_bottom">
				<div class="contact_bottom_inner">
					<div class="container">
						<div class="row gy-5">
							<div class="col-md-4">
								<div class="cmn_contact_box">
									<div class="icon_area">
										<i class="fal fa-phone-alt"></i>
									</div>
									<div class="text_area">
										<h4>@lang('Our Phone')</h4>
										<p>@lang($contact->phone)</p>
									</div>
								</div>
							</div>
							@if(isset($contact->email))
							<div class="col-md-4">
								<div class="cmn_contact_box">
									<div class="icon_area">
										<i class="fal fa-envelope"></i>
									</div>
									<div class="text_area">
										<h4>@lang('Email')</h4>
										<p>@lang($contact->email)</p>
									</div>
								</div>
							</div>
							@endif

							<div class="col-md-4">
								<div class="cmn_contact_box">
									<div class="icon_area">
										<i class="fal fa-map-marker-alt"></i>
									</div>
									<div class="text_area">
										<h4>@lang('Our Address')</h4>
										<p>@lang($contact->address)</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<!-- map_area_start -->
	@if(isset($templatesMedia))
		<div class="map_area">
			<iframe
				src="{{ optional($templatesMedia->description)->button_link }}" class="w-100" height="450"  allowfullscreen="" loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
	@endif
	<!-- map_area_end -->
@endsection
