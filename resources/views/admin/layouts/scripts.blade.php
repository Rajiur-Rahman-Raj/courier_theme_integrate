<!-- General JS Scripts -->
<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/modules/popper.js') }}"></script>
<script src="{{ asset('assets/dashboard/modules/bootstrap/js/bootstrap.min.js') }}"></script>
@stack('js_lib')
<script src="{{ asset('assets/dashboard/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/stisla.js') }}"></script>


<!-- JS Libraies -->
<script src="{{ asset('assets/dashboard/modules/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/js/new_select2.full.min.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('assets/dashboard/js/scripts.js') }}"></script>

<script>
	'use strict'

	var dropdownMenu;

	// and when you show it, move it to the body
	$(window).on('show.bs.dropdown', function (e) {

		// grab the menu
		dropdownMenu = $(e.target).find('.dropdown-menu');

		// detach it and append it to the body
		$('body').append(dropdownMenu.detach());

		// grab the new offset position
		var eOffset = $(e.target).offset();

		// make sure to place it where it would normally go (this could be improved)
		dropdownMenu.css({
			'display': 'block',
			'top': eOffset.top + $(e.target).outerHeight(),
			'left': eOffset.left
		});
	});

	// and when you hide it, reattach the drop down, and hide it normally
	$(window).on('hide.bs.dropdown', function (e) {
		$(e.target).append(dropdownMenu.detach());
		dropdownMenu.hide();
	});


	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
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
				axios.get("{{ route('admin.push.notification.show') }}")
					.then(function (res) {
						app.items = res.data;
					})
			},
			readAt(id, link) {
				let app = this;
				let url = "{{ route('admin.push.notification.readAt', 0) }}";
				url = url.replace(/.$/, id);
				axios.get(url)
					.then(function (res) {
						if (res.status) {
							app.getNotifications();
							if (link !== '#') {
								window.location.href = link
							}
						}
					})
			},
			readAll() {
				let app = this;
				let url = "{{ route('admin.push.notification.readAll') }}";
				axios.get(url)
					.then(function (res) {
						if (res.status) {
							app.items = [];
						}
					})
			},
			pushNewItem() {
				let app = this;
				Pusher.logToConsole = false;
				let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
					encrypted: true,
					cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
				});
				let channel = pusher.subscribe('admin-notification.' + "{{ Auth::id() }}");
				channel.bind('App\\Events\\AdminNotification', function (data) {
					app.items.unshift(data.message);
				});
				channel.bind('App\\Events\\UpdateAdminNotification', function (data) {
					app.getNotifications();
				});
			}
		}
	});
	@endif
	@endauth
	// for search
	$(document).on('input', '.global-search', function () {
		var search = $(this).val().toLowerCase();

		if (search.length == 0) {
			$('.search-result').find('.content').html('');
			$(this).siblings('.search-backdrop').addClass('d-none');
			$(this).siblings('.search-result').addClass('d-none');
			return false;
		}

		$('.search-result').find('.content').html('');
		$(this).siblings('.search-backdrop').removeClass('d-none');
		$(this).siblings('.search-result').removeClass('d-none');

		var match = $('.sidebar-menu li').filter(function (idx, element) {
			if (!$(element).find('a').hasClass('has-dropdown') && !$(element).hasClass('menu-header'))
				return $(element).text().trim().toLowerCase().indexOf(search) >= 0 ? element : null;
		}).sort();

		if (match.length == 0) {
			$('.search-result').find('.content').append(`<div class="search-item"><a href="javascript:void(0)">No result found</a></div>`);
			return false;
		}

		match.each(function (index, element) {
			var item_text = $(element).text().replace(/(\d+)/g, '').trim();
			var item_url = $(element).find('a').attr('href');
			if (item_url != '#') {
				$('.search-result').find('.content').append(`<div class="search-item"><a href="${item_url}">${item_text}</a></div>`);
			}
		});
	});


	$('.summernote').summernote({
		minHeight: 120,
		callbacks: {
			onBlurCodeview: function () {
				let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
				$(this).val(codeviewHtml);
			}
		}
	});
</script>


@stack('extra_scripts')
