@extends($theme.'layouts.user')
@section('page_title',__('Create Receiver'))

@section('content')
	<!-- main -->
	<div class="container-fluid">
		<div class="main row">
			<div class="col">
				<div class="dashboard-heading">
					<div class="">
						<h3 class="mb-0">@lang('Create New Receiver')</h3>
						<nav aria-label="breadcrumb" class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
								<li class="breadcrumb-item"><a
										href="{{ route('user.receiverList') }}">@lang('Receiver List')</a></li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('create reveiver')</a></li>
							</ol>
						</nav>
					</div>
				</div>
				<!-- profile setting -->
				<div class="profile-setting">
					<div class="row g-4">
						<div class="col-xxl-12 col-lg-12">
							<div class="profile_card">
								<div id="" class="content show active">
									<form action="{{ route('user.receiver.store')}}" method="post">
										@csrf
										<div class="row g-4">
											<div class="input-box col-md-4">
												<label for="branch_id">@lang('Select Branch')</label>
												<select class="form-select" aria-label="Default select example"
														name="branch_id"
														id="branch_id">
													<option selected disabled>@lang('select branch')</option>
													@foreach($allBranches as $branch)
														<option
															value="{{ $branch->id}}" {{ request()->branch_id == $branch->id ? 'selected' : '' }}>@lang($branch->branch_name)</option>
													@endforeach
												</select>
												@if($errors->has('language_id'))
													<div
														class="error text-danger">@lang($errors->first('language_id'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-4">
												<label for="">@lang('Name')</label>
												<input type="text" class="form-control" name="name"
													   placeholder="@lang('Full name')"
													   value="{{ old('name') }}"/>
												@if($errors->has('name'))
													<div
														class="error text-danger">@lang($errors->first('name'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-4">
												<label for="">@lang('Username')</label>
												<input type="text" class="form-control" id="username"
													   name="username"
													   placeholder="@lang('username')"
													   value="{{ old('username') }}"/>
												@if($errors->has('username'))
													<div
														class="error text-danger">@lang($errors->first('username'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-4">
												<label for="">@lang('Email')</label>
												<input class="form-control" type="email"
													   id="email"
													   name="email"
													   placeholder="@lang('email')"
													   value="{{ old('email') }}"/>
												@if($errors->has('email'))
													<div
														class="error text-danger">@lang($errors->first('email'))
													</div>
												@endif
											</div>
											<div class="input-box col-md-4">
												<label for="">@lang('Phone')</label>
												<input type="text" class="form-control"
													   id="phone"
													   name="phone"
													   placeholder="@lang('phone')"
													   value="{{ old('phone') }}"/>
												@if($errors->has('phone'))
													<div
														class="error text-danger">@lang($errors->first('phone'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-4">
												<label for="">@lang('Password')</label>
												<input type="text" class="form-control"
													   id="password"
													   name="password"
													   placeholder="@lang('password')"
													   value="{{ old('password') }}"/>
												@if($errors->has('password'))
													<div
														class="error text-danger">@lang($errors->first('password'))
													</div>
												@endif
											</div>
											<div class="input-box col-md-3">
												<label for="country_id">@lang('Select Country') <span class="text-dark">(@lang('optional'))</span></label>
												<select class="form-select selectedCountry"
														aria-label="Default select example"
														name="country_id"
														id="country_id">
													<option selected disabled>@lang('select branch')</option>
													@foreach($allCountries as $country)
														<option
															value="{{ $country->id}}" {{ request()->country_id == $country->id ? 'selected' : '' }}>@lang($country->name)</option>
													@endforeach
												</select>
												@if($errors->has('country_id'))
													<div
														class="error text-danger">@lang($errors->first('country_id'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-3">
												<label for="state_id"> @lang('Select State') <span class="text-dark">(@lang('optional'))</span></label>
												<select name="state_id"
														class="form-control @error('state_id') is-invalid @enderror selectedState select2"></select>
												@if($errors->has('state_id'))
													<div
														class="error text-danger">@lang($errors->first('state_id'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-3">
												<label for="city_id"> @lang('Select city') <span
														class="text-dark font-weight-bold">(@lang('optional'))</span></label>
												<select name="city_id"
														class="form-control @error('city_id') is-invalid @enderror selectedCity select2"></select>
												@if($errors->has('city_id'))
													<div
														class="error text-danger">@lang($errors->first('city_id'))
													</div>
												@endif
											</div>

											<div class="input-box col-md-3">
												<label for="area_id"> @lang('Select Area') <span
														class="text-dark font-weight-bold">(@lang('optional'))</span></label>
												<select name="area_id"
														class="form-control @error('area_id') is-invalid @enderror selectedArea select2">
												</select>
												@if($errors->has('area_id'))
													<div
														class="error text-danger">@lang($errors->first('area_id'))
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
														  value="{{ old('address') }}">{{ old('address') }}</textarea>
												@if($errors->has('address'))
													<div
														class="error text-danger">@lang($errors->first('address'))
													</div>
												@endif
											</div>
											<div class="input-box col-12">
												<button class="cmn_btn w-100">@lang('Create')</button>
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
	@include('partials.locationJs')
	<script>
		'use strict'
		$(document).on('change', "#identity_type", function () {
			let value = $(this).find('option:selected').val();
			window.location.href = "{{route('user.profile')}}/?identity_type=" + value
		});
	</script>
@endsection
