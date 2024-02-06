{{-- shipment status update modal --}}
<div id="updateShipmentStatus" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Confirmation')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="editShipmentStatusForm">
				@csrf
				@method('put')
				<div class="modal-body">
					<p class="shipmentStatusChangeMessage"></p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Yes')</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Assign To Collect Shipment Request Modal --}}
<div id="assignToCollectShipmentRequest" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Assign Confirmation')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="assignToCollectShipmentRequestForm">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-sm-12 col-md-12 mb-3">
							<label for="branch_driver_id"> @lang('Select Driver') <span class="text-danger">*</span></label>
							<select name="branch_driver_id"
									class="form-control @error('branch_driver_id') is-invalid @enderror select2 senderBranchDriver"
									id="branchDriver">
							</select>

							<div class="invalid-feedback">
								@error('branch_driver_id') @lang($message) @enderror
							</div>
							<div class="valid-feedback"></div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Assign')</button>
				</div>
			</form>
		</div>
	</div>
</div>


{{-- Assign To Delivered Shipment Request Modal --}}
<div id="assignToDeliveredShipmentRequest" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Assign Confirmation')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="assignToDeliveredShipmentRequestForm">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-sm-12 col-md-12 mb-3">
							<label for="branch_driver_id"> @lang('Select Driver') <span class="text-danger">*</span></label>
							<select name="branch_driver_id"
									class="form-control @error('branch_driver_id') is-invalid @enderror select2 receiverBranchDriver"
									id="branchDriver">
							</select>

							<div class="invalid-feedback">
								@error('branch_driver_id') @lang($message) @enderror
							</div>
							<div class="valid-feedback"></div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Assign')</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Accept Shipment Request Modal --}}
<div id="acceptShipmentRequest" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Confirmation')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="acceptShipmentRequestForm">
				@csrf
				@method('put')
				<div class="modal-body">
					<p>@lang('Are you sure to accept this shipment?')</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Yes')</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Cancel Shipment Request Modal --}}
<div id="cancelShipmentRequest" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Confirmation')</h4>
				<button type="button" class="close modal-close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="cancelShipmentRequestForm">
				@csrf
				@method('put')
				<div class="modal-body">
					<div class="mb-5">
						<p>@lang('Are you sure to cancel this shipment request?')</p>
					</div>
					<div class="shipment-refund-alert"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark modal-close" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Yes')</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Delete Shipment Modal --}}
<div id="deleteShipment" class="modal fade" tabindex="-1" role="dialog"
	 aria-labelledby="primary-header-modalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-dark font-weight-bold"
					id="primary-header-modalLabel">@lang('Confirmation')</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<form action="" method="post" id="deleteShipmentForm">
				@csrf
				@method('delete')
				<div class="modal-body">
					<p>@lang('Are you sure to delete this shipment?')</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('No')</button>
					<button type="submit" class="btn btn-primary">@lang('Yes')</button>
				</div>
			</form>
		</div>
	</div>
</div>
