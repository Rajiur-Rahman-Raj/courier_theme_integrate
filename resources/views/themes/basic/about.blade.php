@extends($theme.'layouts.app')
@section('title',trans('About Us'))

@section('banner_main_heading')
	@lang('About Us')
@endsection

@section('banner_heading')
	@lang('About')
@endsection

@section('content')
    @include($theme.'sections.about-us')
	@include($theme.'sections.feature')
    @include($theme.'sections.why-choose-us')
	@include($theme.'sections.testimonial')
@endsection
