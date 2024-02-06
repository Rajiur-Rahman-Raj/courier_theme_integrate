@extends('admin.layouts.403_master')
@section('title')
	@lang('403')
@endsection


@section('content')
{{--	<div class="main-content">--}}


{{--	</div>--}}

	<div class="container-fluid main_container_403">
		<div class="row">
			<div class="col-md-12">
				<section class="section">
					{{--			<div class="row mt-5">--}}
					{{--				<div class="col-12">--}}
					{{--					<div class="card">--}}
					{{--						<div class="card-body">--}}
					{{--							<p class="text-center times-403"><i class="fa fa-user-times"></i></p>--}}
					{{--							<h4 class="card-title mb-3 text-center color-secondary"> @lang("You don't have permission to access that link")</h4>--}}
					{{--						</div>--}}
					{{--					</div>--}}
					{{--				</div>--}}
					{{--			</div>--}}


					<div class="forbidden_text_wrapper">
						<div class="forbidden_image">
							<img src="{{ asset('assets/upload/errors/403.gif') }}" alt="">
						</div>
						<div class="forbidden_title" data-content="404">
							403 - @lang('ACCESS DENIED')
						</div>

						<div class="forbidden_subtitle">
							@lang("Oops, You don't have permission to access this page.")
						</div>

						<div class="forbidden_buttons">
							<a class="button" href="{{ url()->previous() }}">@lang('Back')</a>
						</div>
					</div>


				</section>
			</div>
		</div>
	</div>

@endsection
