@extends('admin.layouts.master')
@section('page_title',__('Edit Category'))
@section('content')
	<div class="main-content">
		<section class="section">
			<div class="section-header">
				<h1>@lang('Edit Category')</h1>
				<div class="section-header-breadcrumb">
					<div class="breadcrumb-item active">
						<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
					</div>

					<div class="breadcrumb-item active">
						<a href="{{ route('blogCategory') }}">@lang('Category List')</a>
					</div>
					<div class="breadcrumb-item">@lang('Edit Category')</div>
				</div>
			</div>

			<div class="row">
				<div class="container-fluid" id="container-wrapper">
					<div class="card mb-4 card-primary shadow">
						<div
							class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-primary">@lang('Create Category')</h6>
							<a href="{{route('blogCategory')}}"
							   class="btn btn-sm btn-outline-primary add"><i
									class="fas fa-arrow-left"></i> @lang('Back')</a>
						</div>

						<div class="card-body pb-0">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								@foreach($languages as $key => $language)
									<li class="nav-item">
										<a class="nav-link {{ $loop->first ? 'active' : '' }}"
										   data-toggle="tab" href="#lang-tab-{{ $key }}" role="tab"
										   aria-controls="lang-tab-{{ $key }}"
										   aria-selected="{{ $loop->first ? 'true' : 'false' }}">@lang($language->name)</a>
									</li>
								@endforeach
							</ul>
						</div>

						<div class="tab-content mt-2" id="myTabContent">
							@foreach($languages as $key => $language)
								<div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
									 id="lang-tab-{{ $key }}" role="tabpanel">
									<form action="{{ route('blogCategoryUpdate', [$id, $language->id]) }}" method="post"
										  enctype="multipart/form-data">
										@csrf
										@method('put')
										<div class="card-body">
											<div class="row">
												<div class="form-group col-md-6">
													<label>@lang('Category Name')</label>
													<input class="form-control" type="text"
														   name="name[{{ $language->id }}]"
														   class="form-control  @error('name'.'.'.$language->id) is-invalid @enderror"
														   value="{{ old('name'.$language->id, isset($blogCategoryDetails[$language->id]) ? $blogCategoryDetails[$language->id][0]->name : '') }}"
														   placeholder="@lang('category name')">
													@error('name'.'.'.$language->id)
													<span class="text-danger">{{$message}}</span>
													@enderror
												</div>
												@if($key == 0)
													<div class="col-md-6 form-group">
														<label>@lang('Status')</label>
														<div class="selectgroup w-100">
															<label class="selectgroup-item">
																<input type="radio" name="status" value="0"
																	   class="selectgroup-input" {{ optional($blogCategoryDetails[$language->id][0]->category)->status == 0 ? 'checked' : ''}}>
																<span class="selectgroup-button">@lang('OFF')</span>
															</label>
															<label class="selectgroup-item">
																<input type="radio" name="status" value="1"
																	   class="selectgroup-input" {{ optional($blogCategoryDetails[$language->id][0]->category)->status == 1 ? 'checked' : ''}}>
																<span class="selectgroup-button">@lang('ON')</span>
															</label>
														</div>
													</div>
												@endif
											</div>

											<div class="form-group">
												<button type="submit" name="submit"
														class="btn btn-primary btn-sm btn-block">@lang('Update Category')
												</button>
											</div>
										</div>
									</form>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection
