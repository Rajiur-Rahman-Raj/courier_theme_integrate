@extends($theme.'layouts.app')
@section('title', __($title))

@section('banner_heading')
	@lang('Blog Details')
@endsection

@section('content')
	<!-- blog_details_area_start -->
	<section class="blog_details_area">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-8 order-2 order-lg-1">
					<div class="blog_details">
						<div class="thum_inner">
							<div class="blog_image">
								<img src="{{ getFile($singleBlog->driver, $singleBlog->image) }}" alt="">
								<span class="category">@lang(optional($singleBlog->category->details)->name)</span>
							</div>
						</div>
						<div class="blog_header py-3">
							<div class="date_author">
								<span><a href=""><i class="far fa-user"></i>@lang(optional($singleBlog->details)->author)</a></span>
								<span><i class="far fa-calendar-alt" aria-hidden="true"></i>{{ customDate($singleBlog->created_at) }}</span>
							</div>
							<h3 class="mt-30">@lang(optional($singleBlog->details)->title)</h3>
						</div>
						<div class="blog_para">
							<p>@lang(optional($singleBlog->details)->details)</p>
						</div>
					</div>

					<div id="shareBlock" class="mt-5">
						<h4>@lang('Share now') : </h4>
					</div>
				</div>

				<div class="col-lg-4 order-1 order-lg-2">
					<div class="blog_sidebar">
						<div class="search_area d-flex align-items-center mb-40">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Search Here..."
									   aria-label="Username" aria-describedby="basic-addon1">
								<a href="" type="submit" class="input-group-text hover" id="basic-addon1"><i
										class="far fa-search"></i></a>
							</div>
						</div>
						@if(count($allBlogCategory) > 0)
							<div class="categories_area mt-40" data-aos="fade-up">
								<div class="section_header">
									<h4 class="mb-20">@lang('Categories')</h4>
								</div>
								<ul class="categories_list">
									@foreach($allBlogCategory as $key => $category)
										<li><a href="{{ route('CategoryWiseBlog', [slug(optional($category->details)->name), $category->id]) }}"><span>@lang(optional($category->details)->name)</span> <span class="highlight">({{$category->blog_count}})</span></a></li>
									@endforeach
								</ul>
							</div>
						@endif

						@if (count($relatedBlogs) > 0)

							<div class="categories_area mt-40" data-aos="fade-up">
								<div class="section_header">
									<h4 class="mb-20">@lang('Related Blogs')</h4>
								</div>
								@foreach($relatedBlogs as $key => $relatedBlog)
									<div class="blog_widget_area">
										<ul>
											<li>
												<a href="{{route('blogDetails',[slug(optional($relatedBlog->details)->title), $relatedBlog->id])}}" class="d-flex">
													<div class="blog_widget_image">
														<img src="{{ getFile($relatedBlog->driver, $relatedBlog->image) }}"
															 alt="@lang('blog_image')">
													</div>
													<div class="blog_widget_content">
														<div class="blog_title">{{ \Str::limit(optional($relatedBlog->details)->title, 50) }}</div>
														<div class="blog_date">
															<div
																class="blog_item1">{{ customDate($relatedBlog->created_at) }}</div>
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
	<!-- blog_details_area_start -->
@endsection
