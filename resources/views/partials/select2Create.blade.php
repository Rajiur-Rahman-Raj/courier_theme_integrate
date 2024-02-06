<script>
	'use strict'

	$('.select-shipping-date').select2({
		width : '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" data-toggle="modal" data-target="#add-package-modal"
                    class="btn btn-outline-primary" >+ Create Date </a>
                    </li>`);
	});

	$('.select-branch').select2({
		width : '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('createBranch') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create Branch </a>
                    </li>`);
	});

	$('.select-client').select2({
		width : '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('createClient') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create Client </a>
                    </li>`);
	});

	$('.create-receiver').select2({
		width : '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('user.receiver.create') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create Receiver </a>
                    </li>`);
	});



	//	2nd part
	// If your required choice state are not found
	$('.select2Country').select2({
		width: '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('countryList') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New Country </a>
                    </li>`);
	});

	// If your required choice state are not found
	$('.select2State').select2({
		width: '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('stateList', ['state-list']) }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New State </a>
                    </li>`);
	});

	// If your required choice city are not found
	$('.select2City').select2({
		width: '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('cityList', ['city-list']) }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New City </a>
                    </li>`);
	});

	// If your required choice area are not found
	$('.select2Area').select2({
		width: '100%',
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('areaList', ['area-list']) }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New Area </a>
                    </li>`);
	});

	$('.select2ParcelType').select2({
		width: '100%'
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('parcelServiceList') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New Parcel </a>
                    </li>`);
	});

	$('.select-role').select2({
		width: '100%'
	}).on('select2:open', () => {
		$(".select2-results:not(:has(a))").append(`<li style='list-style: none; padding: 10px;'><a style="width: 100%" href="{{ route('admin.role') }}"
                    class="btn btn-outline-primary" target="_blank">+ Create New Role </a>
                    </li>`);
	});

</script>
