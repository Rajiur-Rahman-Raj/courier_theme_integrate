@extends($theme.'layouts.app')
@section('title',trans('Home'))

@section('content')
    @include($theme.'partials.heroBanner')
    @include($theme.'sections.feature')
    @include($theme.'sections.about-us')
    @include($theme.'sections.service')
    @include($theme.'sections.why-choose-us')
    @include($theme.'sections.testimonial')
    @include($theme.'sections.how-it-work')
    @include($theme.'sections.faq')
    @include($theme.'sections.blog')
@endsection
