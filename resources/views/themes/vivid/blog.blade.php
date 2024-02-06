@extends($theme.'layouts.app')
@section('title', __($title))

@section('banner_heading')
	@lang('Our Blogs')
@endsection

@section('content')
	<!-- blog_area_start -->
	@if (count($allBlogs) > 0)
		<section class="blog_details_area">
			<div class="container">
				<div class="row g-4">
					<div class="col-lg-8 order-2 order-lg-1">
						@foreach($allBlogs as $key => $blog)
							<div class="blog_details">
								<div class="thum_inner">
									<div class="blog_image">
										<img src="{{ getFile($blog->driver, $blog->image) }}" alt="">
									</div>
								</div>

								<div class="blog_header py-3">
									<div class="date_author">
										<span><a href=""><i class="fal fa-list"></i>@lang(optional($blog->category->details)->name)</a></span>
										<span class="float-end"><i class="far fa-calendar-alt" aria-hidden="true"></i>{{ customDate($blog->created_at) }}</span>
									</div>
									<h3 class="mt-30">@lang(optional($blog->details)->title)</h3>
								</div>

								<div class="blog_para">
									<p>{!!  \Str::limit(__(optional($blog->details)->details), 300)  !!}</p>

								</div>
								<a class="btn cmn_btn mt-3 mb-5"
								   href="{{route('blogDetails',[slug(optional($blog->details)->title), $blog->id])}}">@lang('Read More')</a>
							</div>
						@endforeach

						<div class="pagination_area mt-3">
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">
									{{ $allBlogs->appends($_GET)->links() }}
								</ul>
							</nav>
						</div>
					</div>

					<div class="col-lg-4 order-1 order-lg-2">
						<div class="blog_sidebar">
							<form action="{{ route('blogSearch') }}" method="get">
								<div class="search_area d-flex align-items-center mb-40">
									<div class="input-group">
										<input type="text" class="form-control" id="search" name="search"
											   placeholder="@lang('Search Here')..."
											   aria-label="Username" aria-describedby="basic-addon1">
										<button type="submit" class="input-group-text hover" id="basic-addon1"><i
												class="far fa-search"></i></button>
									</div>
								</div>
							</form>
							@if(count($blogCategory) > 0)
								<div class="categories_area mt-40" data-aos="fade-up">
									<div class="section_header">
										<h4 class="mb-20">@lang('Categories')</h4>
									</div>
									<ul class="categories_list">
										@foreach($blogCategory as $key => $category)
											<li>
												<a href="{{ route('CategoryWiseBlog', [slug(optional($category->details)->name), $category->id]) }}"><span>@lang(optional($category->details)->name)</span>
													<span class="highlight">({{$category->blog_count}})</span></a>
											</li>
										@endforeach
									</ul>
								</div>
							@endif

							@if (count($relatedBlogs) > 0)

								<div class="categories_area mt-40" data-aos="fade-up">
									<div class="section_header">
										<h4 class="mb-20">@lang('Recent Blogs')</h4>
									</div>
									@foreach($relatedBlogs->take(3)->sortDesc()->shuffle() as $key => $recentBlog)
										<div class="blog_widget_area">
											<ul>
												<li>
													<a href="{{route('blogDetails',[slug(optional($blog->details)->title), $blog->id])}}"
													   class="d-flex">
														<div class="blog_widget_image">
															<img
																src="{{ getFile($recentBlog->driver, $recentBlog->image) }}"
																alt="@lang('blog_image')">
														</div>
														<div class="blog_widget_content">
															<div class="blog_title">
																{{ \Illuminate\Support\Str::limit(optional($recentBlog->details)->title, 50) }}
															</div>
															<div class="blog_date">
																<div
																	class="blog_item1">{{ customDate($recentBlog->created_at) }}</div>
															</div>
														</div>
													</a>
												</li>
											</ul>
										</div>
									@endforeach
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</section>
	@else
		<div class="custom-not-found">
			<img src="{{ asset($themeTrue.'images/not_found.png') }}" alt="@lang('not found image')"
				 class="img-fluid">
		</div>
	@endif
	<!-- blog_area_start -->

@endsection
