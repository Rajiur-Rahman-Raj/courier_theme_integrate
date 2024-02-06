<!DOCTYPE html>
<html class="no-js" lang="en" @if(session()->get('rtl') == 1) dir="rtl" @endif >
<head>
	<meta charset="utf-8"/>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ getFile(config('basic.default_file_driver'),config('basic.favicon_image')) }}">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>@yield('title')</title>

	@include('partials.seo')

	<!----  How to load Css Library, Here is an example ----->
	<link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/bootstrap.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/owl.carousel.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/owl.theme.default.min.css')}}"/>
	<link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/jquery.fancybox.min.css')}}"/>
	<link href="{{asset('assets/global/css/select2.min.css')}}" rel="stylesheet">

	<script src="{{asset($themeTrue.'js/fontawesomepro.js')}}"></script>


	@stack('css-lib')
	<!---- Here is your Css Library----->

	<!----  Push your custom css  ----->
	@stack('css')
	<link rel="stylesheet" type="text/css" href="{{asset($themeTrue.'css/style.css')}}"/>
</head>

<body onload="preloder_function()" @if(session()->get('rtl') == 1) class="rtl" @endif>

<!-- preloader_area_start -->
<div id="preloader">
	<div class="truck-wrapper">
		<div class="truck">
			<div class="truck-container"></div>
			<div class="glases"></div>
			<div class="bonet"></div>
			<div class="base"></div>
			<div class="base-aux"></div>
			<div class="wheel-back"></div>
			<div class="wheel-front"></div>
			<div class="smoke"></div>
		</div>
	</div>
</div>
<!-- preloader_area_end -->

@include($theme.'partials.navbar')

@include($theme.'partials.banner')

@yield('content')

@include($theme.'partials.footer')

@stack('extra-content')

<a href="#" class="scroll_up">
	<i class="fal fa-chevron-double-up"></i>
</a>

<!----  How to load JS Library, Here is an example ----->
<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/jquery.waypoints.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/owl.carousel.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.counterup.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.fancybox.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/socialSharing.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.scrollUp.min.js')}}"></script>
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/main.js')}}"></script>

@stack('extra-js')

<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>

<script>
	'use strict';


	$("select.form-select").select2({
		width:'100%',
	});

	@auth
	@if(basicControl()->push_notification)
	let pushNotificationArea = new Vue({
		el: "#pushNotificationArea",
		data: {
			items: [],
		},
		mounted() {
			this.getNotifications();
			this.pushNewItem();
		},
		methods: {
			getNotifications() {
				let app = this;
				axios.get("{{ route('push.notification.show') }}")
					.then(function (res) {
						app.items = res.data;
					})
			},
			readAt(id, link) {
				let app = this;
				let url = "{{ route('push.notification.readAt', 0) }}";
				url = url.replace(/.$/, id);
				axios.get(url)
					.then(function (res) {
						if (res.status) {
							app.getNotifications();
							if (link != '#') {
								window.location.href = link
							}
						}
					})
			},
			readAll() {
				let app = this;
				let url = "{{ route('push.notification.readAll') }}";
				axios.get(url)
					.then(function (res) {
						if (res.status) {
							app.items = [];
						}
					})
			},
			pushNewItem() {
				let app = this;
				// Pusher.logToConsole = true;
				let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
					encrypted: true,
					cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
				});
				let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
				channel.bind('App\\Events\\UserNotification', function (data) {
					app.items.unshift(data.message);
				});
				channel.bind('App\\Events\\UpdateUserNotification', function (data) {
					app.getNotifications();
				});
			}
		}
	});

	@endif
	@endauth
</script>

@stack('script')

@include($theme.'partials.notification')

@include('plugins')
</body>
</html>
