@extends($theme.'layouts.app')
@section('page_title',__('Change Password'))

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Change Password')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Change Password')</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="container-fluid" id="container-wrapper">
					<div class="row justify-content-md-center">
						<div class="col-md-6">
							<div class="card mb-4 card-primary shadow">
								<div
									class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
									<h6 class="m-0 font-weight-bold text-primary">@lang('Change Password')</h6>
								</div>
								<div class="card-body">
									<form action="{{ route('user.change.password') }}" method="post">
										@csrf
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="currentPassword"
														   class="col-form-label">@lang('Current password')</label>
													<input type="password" name="currentPassword"
														   value="{{ old('currentPassword') }}"
														   placeholder="@lang('Enter your current password')"
														   class="form-control @error('currentPassword') is-invalid @enderror">
													<div
														class="invalid-feedback">@error('currentPassword') @lang($message) @enderror</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label for="password"
														   class="col-form-label">@lang('Password')</label>
													<input type="password" name="password" value="{{ old('password') }}"
														   placeholder="@lang('Enter new password')"
														   class="form-control @error('password') is-invalid @enderror">
													<div
														class="invalid-feedback">@error('password') @lang($message) @enderror</div>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<label for="password_confirmation"
														   class="col-form-label">@lang('Repeat password')</label>
													<input type="password" name="password_confirmation"
														   value="{{ old('password_confirmation') }}"
														   class="form-control form-control-sm"
														   placeholder="@lang('Repeat password')">
												</div>
											</div>
											<div class="col-md-12">
												<button type="submit"
														class="btn btn-primary btn-sm btn-block">@lang('Change Password')</button>
											</div>
										</div>
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
