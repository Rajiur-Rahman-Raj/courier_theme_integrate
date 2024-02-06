@extends('admin.layouts.master')
@section('page_title',__('Seo Settings'))
@push('extra_styles')
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/select-tag.css') }}">
@endpush

@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Seo Settings')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>
					<div class="breadcrumb-item">@lang('Seo Settings')</div>
				</div>
			</div>

			<div class="section-body">
				<div class="row mt-sm-4">
					<div class="col-12 col-md-4 col-lg-3">
						@include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
					</div>

					<div class="col-12 col-md-8 col-lg-9">
						<div class="container-fluid" id="container-wrapper">
							<div class="row justify-content-md-center">
								<div class="col-lg-12">
									<div class="bd-callout bd-callout-warning mx-2">
										<i class="fas fa-info-circle mr-2"></i> @lang('After changes logo/seo. Please clear your browser\'s cache to see changes.')
									</div>

									<div class="card mb-4 card-primary shadow">
										<div
											class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h6 class="m-0 font-weight-bold text-primary">@lang('Seo Settings')</h6>
										</div>
										<div class="card-body">
											@include('errors.error')
											<form method="post" action="{{ route('seo.update') }}"
												  enctype="multipart/form-data">
												@csrf

												<div class="row">
													<div class="col-md-6 col-xl-4">
														<div class="form-group   ">
															<label class="col-form-label">@lang('Meta Image')</label>
															<div id="image-preview" class="image-preview "
																 style="background-image: url({{ getFile(basicControl()->default_file_driver,config('basic.logo_meta')) ? : 0 }});">
																<label for="image-upload"
																	   id="image-label">@lang('Choose File')</label>
																<input type="file" name="image"
																	   class=" @error('image') is-invalid @enderror"
																	   id="image-upload"/>
															</div>
															<div class="invalid-feedback">
																@error('image') @lang($message) @enderror
															</div>
														</div>
													</div>

													<div class="col-md-6 col-xl-8">
														<div class="form-group">
															<label for="social_title"
																   class="col-form-label">@lang('Social title')</label>
															<input type="text" name="social_title"
																   value="{{ old('social_title',$basicControl->social_title) }}"
																   class="form-control @error('social_title') is-invalid @enderror">
															<div class="invalid-feedback">
																@error('social_title') @lang($message) @enderror
															</div>
														</div>

														<div class="form-group ">
															<label for="meta_keywords"
																   class="col-form-label">@lang('Meta keywords')
																<small
																	class="ms-2 mt-2 font-italic"> @lang("Separate multiple keywords by")
																	<code>@lang("comma")</code> @lang('or')
																	<code>@lang("enter")</code> @lang('key') </small>
															</label>

															<select name="meta_keywords[]"
																	class="form-control select2-auto-tokenize"
																	multiple="multiple" required>
																@if(@$basicControl->meta_keywords)
																	@foreach(explode(',',$basicControl->meta_keywords) as $option)
																		<option value="{{ $option }}"
																				selected>{{ __($option) }}</option>
																	@endforeach
																@endif
															</select>

															<div class="invalid-feedback">
																@error('meta_keywords') @lang($message) @enderror
															</div>
														</div>

														<div class="form-group">
															<label for="meta_description"
																   class="col-form-label">@lang('Meta description')</label>
															<textarea
																class="form-control @error('meta_description') is-invalid @enderror"
																name="meta_description"
																rows="5">{{ old('meta_description',$basicControl->meta_description) }}</textarea>
															<div class="invalid-feedback">
																@error('meta_description') @lang($message) @enderror
															</div>
														</div>

														<div class="form-group">
															<label for="social_description"
																   class="col-form-label">@lang('Social description')</label>
															<textarea
																class="form-control @error('social_description') is-invalid @enderror"
																name="social_description"
																rows="5">{{ old('social_description',$basicControl->social_description) }}</textarea>
															<div class="invalid-feedback">
																@error('social_description') @lang($message) @enderror
															</div>
														</div>

													</div>
												</div>
												<button type="submit"
														class="btn btn-primary btn-sm btn-block mt-1">@lang('Save Change')</button>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@push('js_lib')

	<script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>
@endpush

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function () {
			$.uploadPreview({
				input_field: "#image-upload",
				preview_box: "#image-preview",
				label_field: "#image-label",
				label_default: "Choose File",
				label_selected: "Change File",
				no_label: false
			});
			$('.select2-auto-tokenize').select2({
				tags: true,
				tokenSeparators: [',']

			});


		});
	</script>
@endsection

