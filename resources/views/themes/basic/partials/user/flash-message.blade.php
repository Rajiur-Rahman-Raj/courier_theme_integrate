@if(session()->has('success'))
	<script>
		Notiflix.Notify.success("{{ __(session()->get('success')) }}");
	</script>
@endif
@if(session()->has('alert'))
	<script>
		Notiflix.Notify.failure("{{ __(session()->get('alert')) }}");
	</script>
@endif

@if (session()->has('error'))
	<script>
		Notiflix.Notify.failure("@lang(session('error'))");
	</script>
@endif

@if (session()->has('warning'))
	<script>
		Notiflix.Notify.warning("@lang(session('warning'))");
	</script>
@endif
