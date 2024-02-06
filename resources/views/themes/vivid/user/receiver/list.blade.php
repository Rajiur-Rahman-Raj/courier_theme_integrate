@extends($theme.'layouts.user')
@section('page_title',__('Receiver List'))

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading d-flex justify-content-between align-items-center flex-column flex-sm-row mb-3 gap-2 gap-sm-0">
					<div class="">
						<h2 class="mb-0">@lang('Receiver Lists')</h2>
						<nav aria-label="breadcrumb"  class="ms-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a
										href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Receiver List')</a></li>
							</ol>
						</nav>
					</div>

					<div class="">
						<a href="{{ route('user.receiver.create') }}" class="cmn_btn">
							@lang('Create new receiver')
						</a>
					</div>
				</div>

				<div class="table-parent table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col">@lang('Name')</th>
							<th scope="col">@lang('Branch')</th>
							<th scope="col">@lang('Phone')</th>
							<th scope="col">@lang('Email')</th>
							<th scope="col">@lang('Join Date')</th>
						</tr>
						</thead>
						<tbody>
						@forelse($allReceivers as $key => $receiver)
							<tr>
								<td data-label="@lang('Name')">
									@lang($receiver->name)
								</td>
								<td data-label="@lang('Branch')">
									@lang(optional(optional($receiver->profile)->branch)->branch_name)
								</td>
								<td data-label="@lang('Phone')">
									{{ optional($receiver->profile)->phone }}
								</td>
								<td data-label="@lang('Email')">
									{{ $receiver->email }}
								</td>
								<td data-label="@lang('Join Date')">
									{{ __(date('d M,Y - H:i a',strtotime($receiver->created_at))) }}
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="100%" class="text-center p-2 flex-column">
									<img class="not-found-img"
										 src="{{ asset($themeTrue.'images/business.png') }}"
										 alt="">
									<p class="text-center no-data-found-text">@lang('No Receiver Found')</p>
								</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>
				<div class="pagination_area mt-4">
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center">
							{{ $allReceivers->appends($_GET)->links() }}
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
@endsection
