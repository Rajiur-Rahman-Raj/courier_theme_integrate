<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="{{ getFile(config('location.logo.path').'favicon.png') }}" rel="icon">
	<title>@yield('page_title')</title>
	@include('admin.layouts.styles')
</head>
<body>

</body>
	@section('content')
	@show
	@yield('scripts')
</html>
