@extends($theme.'layouts.user')
@section('title',__($page_title))

@section('content')
	<!-- Transfer Money -->
	<div class="container-fluid">
		<div class="main row">
			<div class="row mt-2">
				<div class="col">
					<div class="header-text-full">
						<h2 class="dashboard_breadcurmb_heading mb-1">{{trans($page_title)}}</h2>
						<nav aria-label="breadcrumb" class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">@lang('Balance Transfer')</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>

			<div class="col">
				<!-- Transfer Money -->
				<section class="profile-setting search-bar">
					<div class="row g-4 g-lg-5">
						<div class="col-lg-12">
							<div id="tab1"
								 class="content p-0 {{ $errors->has('email') ? ' active' : (($errors->has('amount') || $errors->has('wallet_type') || $errors->has('password')) ? '' :  ' active') }}">
								<form class="form-row" action="{{ route('user.send.money') }}" method="post">
									@csrf
									<div class="row g-4">
										<div class="input-box col-md-12">
											<label for="lastname">@lang('Current Balance')</label>
											<div class="input-group">
												<span class="input-group-text"
													  id="basic-addon1">{{ $basic->currency_symbol }}</span>
												<input type="text" name="balance" value="{{ Auth::user()->balance }}"
													   class="form-control" readonly/>
											</div>
											@if($errors->has('balance'))
												<div class="error text-danger">@lang($errors->first('balance'))</div>
											@endif
										</div>


										<div class="input-box col-md-12">
											<label for="firstname">@lang('Receiver Email Address')</label>
											<input type="email" name="email" value="{{old('email')}}"
												   placeholder="@lang('Receiver Email Address')" class="form-control"
												   id="email"/>

											@error('email')
											<div class="error text-danger">@lang($message) </div>
											@enderror
										</div>

										<div class="input-box col-md-12">
											<label for="lastname">@lang('Amount')</label>
											<input type="text" name="amount" value="{{old('amount')}}"
												   placeholder="@lang('Enter Amount')"
												   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
												   id="lastname" class="form-control"/>
											@if($errors->has('amount'))
												<div
													class="error text-danger">@lang($errors->first('amount'))</div>
											@endif
										</div>

										<div class="input-box col-md-12">
											<label for="email">@lang('Enter Password')</label>
											<input type="password" name="password" value="{{old('password')}}"
												   placeholder="@lang('Your Password')"
												   class="form-control"/>
											@error('password')
											<div class="error text-danger">@lang($message) </div>
											@enderror
										</div>

										<div class="input-box col-12">
											<button class="cmn_btn" type="submit">@lang('Transfer')</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
@endsection
