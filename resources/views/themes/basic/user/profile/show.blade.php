@extends($theme.'layouts.user')
@section('page_title',__('Profile'))

@section('content')
	<!-- main -->
	<div class="container-fluid">
		<div class="main row">
			<div class="col">
				<!-- profile setting -->
				<div class="profile-setting">
					<div class="row g-4">
						<div class="col-xxl-3 col-lg-4">
							<div class="sidebar-wrapper p-0">
								<form method="post" action="{{ route('user.updateProfile') }}"
									  enctype="multipart/form-data">
									@csrf
									<div class="profile">
										<div class="img  mx-auto">
											<img id="profile"
												 src="{{getFile($userProfile->driver,$userProfile->profile_picture) }}"
												 alt=""
												 class="img-fluid"/>
											<button class="upload-img">
												<i class="fal fa-camera"></i>
												<input class="form-control"  type="file"
													   onchange="previewImage('profile')" name="profile_picture"
													   accept="image/*"/>
											</button>
										</div>
										<div class="text">
											<h5 class="name">@lang(ucfirst($user->name))</h5>
											<span>@lang($user->username)</span>
										</div>
										<div class="btn_area">
											<button type="submit" class="cmn_btn">@lang('Update')</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="col-xxl-9 col-lg-8">
							<div class="profile_card">
								<div class="profile-navigator">
									<button tab-id="tab1"
											class="tab {{ $errors->has('profile') ? 'active' : (($errors->has('password') || $errors->has('identity') || $errors->has('addressVerification')) ? '' : ' active') }}">
										<i class="fal fa-user"></i> @lang('Profile Information')
									</button>
									<button tab-id="tab2" class="tab {{ $errors->has('password') ? 'active' : '' }}">
										<i class="fal fa-key"></i> @lang('Password Setting')
									</button>
								</div>

								<div id="tab1"
									 class="content {{ $errors->has('profile') || !$errors->has('password')  ?'active' : '' }}">
									<form action="{{ route('user.updateInformation')}}" method="post">
										@method('put')
										@csrf
										<div class="row g-4">
											<div class="input-box col-md-12">
												<label for="">@lang('Full Name')</label>
												<input type="text" class="form-control" name="name"
													   placeholder="@lang('full name')"
													   value="{{ old('name', $user->name) }}"/>
												@if($errors->has('name'))
													<div
														class="error text-danger">@lang($errors->first('name'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-6">
												<label for="">@lang('Username')</label>
												<input type="text" class="form-control" id="username"
													   name="username"
													   placeholder="@lang('username')"
													   value="{{ old('username', $user->username) }}"/>
												@if($errors->has('username'))
													<div
														class="error text-danger">@lang($errors->first('username'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-6">
												<label for="">@lang('Email')</label>
												<input class="form-control" type="email"
													   id="email"
													   name="email"
													   placeholder="@lang('email')"
													   value="{{ old('email', $user->email) }}"/>
												@if($errors->has('email'))
													<div
														class="error text-danger">@lang($errors->first('email'))
													</div>
												@endif
											</div>
											<div class="input-box col-md-6">
												<label for="">@lang('Phone')</label>
												<input type="text" class="form-control"
													   id="phone"
													   name="phone"
													   placeholder="@lang('phone')"
													   value="{{ old('phone', $userProfile->phone) }}"/>
												@if($errors->has('phone'))
													<div
														class="error text-danger">@lang($errors->first('phone'))
													</div>
												@endif
											</div>
											<div class="input-box col-md-6">
												<label for="language_id">@lang('Preferred Language')</label>
												<select class="form-control" aria-label="Default select example"
														name="language_id"
														id="language_id">
													<option selected disabled>@lang('select language')</option>
													@foreach($languages as $la)
														<option
															value="{{$la->id}}" {{ old('language_id', $user->language_id) == $la->id ? 'selected' : '' }}> @lang($la->name)</option>
													@endforeach
												</select>
												@if($errors->has('language_id'))
													<div
														class="error text-danger">@lang($errors->first('language_id'))
													</div>
												@endif
											</div>

											<div class="input-box col-12">
												<label for="">@lang('Address')</label>
												<textarea class="form-control @error('address') is-invalid @enderror"
														  id="address"
														  name="address"
														  type="text"
														  placeholder="@lang('address')"
														  value="{{ old('address', $userProfile->address) }}">{{ old('address', $userProfile->address) }}</textarea>
												@if($errors->has('address'))
													<div
														class="error text-danger">@lang($errors->first('address'))
													</div>
												@endif
											</div>
											<div class="input-box col-12">
												<button class="cmn_btn">@lang('Update')</button>
											</div>
										</div>
									</form>
								</div>
								<div id="tab2" class="content {{ ($errors->has('password')) ? ' active' :''}}">
									<form method="post" action="{{ route('user.updatePassword') }}">
										@csrf
										<div class="row g-4">
											<div class="input-box col-md-6">
												<label for="">@lang('Current Password')</label>
												<input type="password"
													   id="current_password"
													   name="current_password"
													   autocomplete="off"
													   class="form-control"
													   placeholder="@lang('Enter Current Password')"/>
												@if($errors->has('current_password'))
													<div
														class="error text-danger">@lang($errors->first('current_password'))</div>
												@endif
											</div>
											<div class="input-box col-md-6">
												<label for="">@lang('New Password')</label>
												<input type="password"
													   id="password"
													   name="password"
													   autocomplete="off"
													   class="form-control"
													   placeholder="@lang('Enter New Password')"/>
												@if($errors->has('password'))
													<div
														class="error text-danger">@lang($errors->first('password'))</div>
												@endif
											</div>
											<div class="input-box col-md-6">
												<label for="password_confirmation">@lang('Confirm Password')</label>
												<input type="password"
													   id="password_confirmation"
													   name="password_confirmation"
													   autocomplete="off"
													   class="form-control"
													   placeholder="@lang('Confirm Password')"/>
												@if($errors->has('password_confirmation'))
													<div
														class="error text-danger">@lang($errors->first('password_confirmation'))</div>
												@endif
											</div>
											<div class="input-box col-12">
												<button class="cmn_btn">@lang('Update Password')</button>
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
	</div>
@endsection

@push('css-lib')
	<link rel="stylesheet" href="{{asset($themeTrue.'css/bootstrap-fileinput.css')}}">
@endpush

@section('scripts')
	<script src="{{asset($themeTrue.'js/bootstrap-fileinput.js')}}"></script>
	<script>
		'use strict'
		$(document).on('change', "#identity_type", function () {
			let value = $(this).find('option:selected').val();
			window.location.href = "{{route('user.profile')}}/?identity_type=" + value
		});
	</script>
@endsection
