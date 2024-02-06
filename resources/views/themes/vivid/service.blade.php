@extends($theme.'layouts.app')
@section('title',trans('Services'))

@section('banner_main_heading')
	@lang('Our Services')
@endsection

@section('banner_heading')
	@lang('Service')
@endsection

@section('content')
	@include($theme.'sections.feature')
	@include($theme.'sections.service')
	@include($theme.'sections.how-it-work')
@endsection

