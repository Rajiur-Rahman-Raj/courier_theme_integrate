@extends($theme.'layouts.user')
@section('page_title',__('Tickets Log'))

@section('content')
	<div class="container-fluid">
		<div class="main row">
			<div class="col-12">
				<div class="dashboard-heading d-flex justify-content-between align-items-center flex-column flex-sm-row mb-3 gap-2 gap-sm-0">
					<div class="">
						<h2 class="mb-0">@lang('Tickets Log')</h2>
						<nav aria-label="breadcrumb"  class="ms-2">
							<ol class="breadcrumb mb-0">
								<li class="breadcrumb-item"><a
										href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
								<li class="breadcrumb-item"><a href="javascript:void(0)">@lang('Tickets Log')</a></li>
							</ol>
						</nav>
					</div>
					<div class="">
						<a href="{{ route('user.ticket.create') }}" class="cmn_btn">
							@lang('Create new ticket')
						</a>
					</div>

				</div>

				<div class="table-parent table-responsive">
					<table class="table table-striped">
						<thead>
						<tr>
							<th scope="col">@lang('Subject')</th>
							<th scope="col">@lang('Status')</th>
							<th scope="col">@lang('Last Reply')</th>
							<th scope="col">@lang('Action')</th>
						</tr>
						</thead>
						<tbody>
						@forelse($tickets as $key => $ticket)
							<tr>
								<td data-label="@lang('Subject')">
									[{{ trans('Ticket# ').__($ticket->ticket) }}
									] {{ __($ticket->subject) }}
								</td>
								<td data-label="@lang('Status')">
									@if($ticket->status == 0)
										<span class="badge text-bg-primary">@lang('Open')</span>
									@elseif($ticket->status == 1)
										<span class="badge text-bg-success">@lang('Answered')</span>
									@elseif($ticket->status == 2)
										<span
											class="badge text-bg-secondary">@lang('Replied')</span>
									@elseif($ticket->status == 3)
										<span
											class="badge text-bg-danger">@lang('Closed')</span>
									@endif
								</td>
								<td data-label="@lang('Last Reply')">
									{{ __($ticket->last_reply->diffForHumans()) }}
								</td>
								<td data-label="@lang('Action')">
									<a href="{{ route('user.ticket.view', $ticket->ticket) }}"
									   class="view_cmn_btn">
										@lang('View')
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="100%" class="text-center p-2 flex-column">
									<img class="not-found-img"
										 src="{{ asset($themeTrue.'images/business.png') }}"
										 alt="">
									<p class="text-center no-data-found-text">@lang('No Tickets Found')</p>
								</td>
							</tr>
						@endforelse
						</tbody>
					</table>
				</div>

				<div class="pagination_area mt-3">
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center">
							{{ $tickets->appends($_GET)->links() }}
						</ul>
					</nav>
				</div>

			</div>
		</div>
	</div>
@endsection
