<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="{{ getFile(config('basic.default_file_driver'),config('basic.favicon_image')) }}" rel="icon">
	<title> @yield('page_title') | @lang('Admin') </title>
	<!-- General CSS Files -->
	<link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/dashboard/modules/fontawesome/css/all.min.css') }}">

	<!-- CSS Libraries -->
	<link rel="stylesheet" href="{{ asset('assets/dashboard/modules/summernote/summernote-bs4.css') }}">


	<!-- Template CSS -->
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/components.css') }}">

	<link href="{{asset('assets/dashboard/css/new_select2.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('assets/dashboard/css/style.css') }}">

	@stack('extra_styles')
</head>

<body id="body">
	<div id="app">
		<div class="main-wrapper main-wrapper-1">
			<div class="navbar-bg" style="height: 90px"></div>
			@section('content')
			@show
			@include('admin.layouts.footer')

		</div>
	</div>


	<!-- General JS Scripts -->
	<script src="{{ asset('assets/dashboard/modules/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/modules/popper.js') }}"></script>
	<script src="{{ asset('assets/dashboard/modules/bootstrap/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/stisla.js') }}"></script>


	<!-- JS Libraies -->
	<script src="{{ asset('assets/dashboard/modules/summernote/summernote-bs4.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/pusher.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/vue.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/axios.min.js') }}"></script>
	<script src="{{ asset('assets/dashboard/js/notiflix-aio-2.7.0.min.js') }}"></script>

	{{--<script src="{{ asset('assets/dashboard/js/select2.min.js') }}"></script>--}}
	<script src="{{ asset('assets/dashboard/js/new_select2.full.min.js') }}"></script>
	<!-- Template JS File -->
	<script src="{{ asset('assets/dashboard/js/scripts.js') }}"></script>

	<script>
		'use strict'

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	</script>
	@stack('extra_scripts')

	@include('admin.layouts.flash-message')
@stack('loadModal')
@yield('scripts')

</body>
</html>
