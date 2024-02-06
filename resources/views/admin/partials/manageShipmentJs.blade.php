<script>
	'use strict'
	$(document).on('click', '.cancelShipmentRequest', function () {
		let dataRoute = $(this).data('route');
		$('#cancelShipmentRequestForm').attr('action', dataRoute);
		let basicControl = @json(basicControl());
		let refundTimeArray = basicControl.refund_time.split("_");
		let refundTime = refundTimeArray[0];
		let refundTimeType = refundTimeArray[1];
		let dataProperty = $(this).data('property');
		let paymentType = dataProperty.payment_type;
		let paymentStatus = dataProperty.payment_status;

		if (paymentType == 'wallet' && paymentStatus == 1) {
			$('.shipment-refund-alert').html(`
						<div class="bd-callout bd-callout-warning">
							<i class="fas fa-info-circle mr-2"></i>
							N.B: You will get a refund ${refundTime} ${refundTimeType} after canceling your shipment request. Refund charges will be deducted.
						</div>
					`);
		}
	});

	$(document).on('click', '.modal-close', function () {
		$('.shipment-refund-alert').html('');
	});

	$(document).ready(function () {
		$(document).on('click', '.editShipmentStatus', function () {
			let dataRoute = $(this).data('route');
			let dataStatus = $(this).data('status');

			if (dataStatus == 'in_queue') {
				$('.shipmentStatusChangeMessage').text('Are you sure to dispatch this shipment?')
			} else if (dataStatus == 'upcoming') {
				$('.shipmentStatusChangeMessage').text('Are you sure to received this shipment?')
			} else if (dataStatus == 'received') {
				$('.shipmentStatusChangeMessage').text('Are you sure to delivered this shipment?')
			} else if (dataStatus == 'assign_to_delivery') {
				$('.shipmentStatusChangeMessage').text('Are you sure to delivered this shipment?')
			} else if (dataStatus == 'return_in_queue') {
				$('.shipmentStatusChangeMessage').text('Are you sure to return back this shipment?')
			}else if (dataStatus == 'return_in_dispatch') {
				$('.shipmentStatusChangeMessage').text('Are you sure to return dispatch this shipment?')
			} else if (dataStatus == 'return_in_upcoming') {
				$('.shipmentStatusChangeMessage').text('Are you sure to received return shipment?')
			} else if (dataStatus == 'return_in_received') {
				$('.shipmentStatusChangeMessage').text('Are you sure to Delivered return shipment?')
			}
			$('#editShipmentStatusForm').attr('action', dataRoute);
		});

		$(document).on('click', '.assignToCollectShipmentRequest', function () {
			let dataRoute = $(this).data('route');
			$('#assignToCollectShipmentRequestForm').attr('action', dataRoute);

			let dataPropertry = $(this).data('property');
			let branchDriver = dataPropertry.sender_branch.branch_driver;

			branchDriver.forEach(res => {
				$('.senderBranchDriver').append(`<option value="${res.admin_id}">${res.admin.name}</option>`)
			})
		});

		$(document).on('click', '.assignToDeliveredShipmentRequest', function () {
			let dataRoute = $(this).data('route');
			$('#assignToDeliveredShipmentRequestForm').attr('action', dataRoute);

			let dataPropertry = $(this).data('property');
			let branchDriver = dataPropertry.receiver_branch.branch_driver;

			branchDriver.forEach(res => {
				$('.receiverBranchDriver').append(`<option value="${res.admin_id}">${res.admin.name}</option>`)
			})
		});

		$(document).on('click', '.acceptShipmentRequest', function () {
			let dataRoute = $(this).data('route');
			$('#acceptShipmentRequestForm').attr('action', dataRoute);
		});

		$(document).on('click', '.deleteShipment', function () {
			let dataRoute = $(this).data('route');
			$('#deleteShipmentForm').attr('action', dataRoute);
		});
	})
</script>
