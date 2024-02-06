@extends($theme.'layouts.user')
@section('page_title',__('Create Ticket'))

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<section class="section pt-4">
				<div class="section-header">
					<h2>@lang('Create Ticket')</h2>
				</div>

				<div class="row mb-3">
					<div class="container-fluid" id="container-wrapper">

						<div class="row justify-content-md-center">
							<div class="col-sm-12">
								<div class="card mb-4 card-primary shadow">
									<div class="card-body profile-setting">
										<form action="{{route('user.ticket.store')}}" method="post"
											  enctype="multipart/form-data">
											@csrf

											<div class="input-box">
												<label for="subject">@lang('Subject')</label>
												<input type="text" name="subject" placeholder="@lang('Subject')"
													   value="{{ old('subject') }}"
													   class="form-control @error('subject') is-invalid @enderror">
												<div class="invalid-feedback">
													@error('subject') @lang($message) @enderror
												</div>
												<div class="valid-feedback"></div>
											</div>

											<div class="input-box mt-3">
												<label for="message">@lang('Message')</label>
												<textarea name="message" rows="5"
														  class="form-control @error('note') is-invalid @enderror">{{ old('message') }}</textarea>
												<div class="invalid-feedback">
													@error('message') @lang($message) @enderror
												</div>
											</div>
											<div class="input-box mt-4">
												<div class="attach-file">
													<span class="prev"> <i class="fa fa-link"></i> </span>
													<input class="form-control" name="attachments[]" accept="image/*"
														   type="file" multiple/>
												</div>
												<p class="text-danger select-files-count"></p>
												@error('attachments')
												<div class="text-danger"> @lang($message) </div>
												@enderror
											</div>
											<button type="submit" class="btn btn-primary btn-sm btn-block cmn_btn mt-3">
												@lang('Submit Ticket')
											</button>
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
@section('scripts')
	<script>
		'use strict';
		$(document).ready(function () {
			$(document).on('change', '#upload', function () {
				var fileCount = $(this)[0].files.length;
				$('.select-files-count').text(fileCount + ' file(s) selected');
			});
		});
	</script>
@endsection
