
@extends('admin.layouts.master')
@section('page_title',__('Role List'))

@section('content')
	<div id="role-permission-app">
		<div class="main-content">
			<section class="section">
				<div class="section-header">
					<h1>@lang('Role List')</h1>
					<div class="section-header-breadcrumb">
						<div class="breadcrumb-item active">
							<a href="{{ route('admin.home') }}">@lang('Dashboard')</a>
						</div>
						<div class="breadcrumb-item">@lang('Role List')</div>
					</div>
				</div>

				<div class="row mb-3">
					<div class="container-fluid" id="container-wrapper">
						<div class="row">
							<div class="col-lg-12">
								<div class="card mb-4 card-primary shadow">
									<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
										<h6 class="m-0 font-weight-bold text-primary">@lang('Role List')</h6>
										<button class="btn btn-sm btn-primary" data-target="#add-modal"
												data-toggle="modal" @click="makeDataEmpty">@lang('Add New Role')</button>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table
												class="table table-striped table-hover align-items-center table-borderless">
												<thead class="thead-light">
												<tr>
													<th>@lang('SL.')</th>
													<th>@lang('Name')</th>
													<th>@lang('Status')</th>
													<th>@lang('Action')</th>
												</tr>
												</thead>
												<tbody>
												@forelse($roles as $key => $value)
													<tr>
														<td data-label="@lang('SL.')">{{++$key }}</td>
														<td data-label="@lang('Name')">{{$value->name }}</td>
														<td data-label="@lang('Status')">
															@if($value->status == 1)
																<span class="badge badge-success">@lang('Active')</span>
															@else
																<span
																	class="badge badge-danger">@lang('Inactive')</span>
															@endif
														</td>
														<td data-label="@lang('Action')">
															<button
																class="btn btn-sm btn-outline-info"
																data-target="#editModal" data-toggle="modal"
																data-resource="{{$value}}"
																@click="editRole({{ $value }})"
																class="editRole">@lang('Edit')</button>
															<button
																class="btn btn-sm btn-outline-danger notiflix-confirm"
																data-target="#delete-modal"
																data-toggle="modal"
																data-route="{{route('admin.role.delete',$value->id)}}">
																@lang('Delete')</button>
														</td>
													</tr>
												@empty
													<tr>
														<th colspan="100%"
															class="text-center">@lang('No data found')</th>
													</tr>
												@endforelse
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		{{--	Add Modal--}}
		<div id="add-modal" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog"
			 aria-labelledby="primary-header-modalLabel"
			 aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-dark font-weight-bold"
							id="primary-header-modalLabel">@lang('Add Roles')</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<form>
						<div class="modal-body">
							<div class="col-12">
								<label for="">@lang('Name')</label>
								<input
									type="text"
									class="form-control" v-model="item.name"
									placeholder="@lang('Name')"/>
								<span class="text-danger name-error"></span>
							</div>
							<div class="col-md-12 my-3">
								<label for="">@lang('Status') </label>
								<div class="selectgroup w-100">
									<label class="selectgroup-item">
										<input type="radio" v-model="item.status" value="0" class="selectgroup-input">
										<span class="selectgroup-button">@lang('OFF')</span>
									</label>
									<label class="selectgroup-item">
										<input type="radio" v-model="item.status" value="1" class="selectgroup-input"
											   :checked="item.status == 1">
										<span class="selectgroup-button">@lang('ON')</span>
									</label>
								</div>
							</div>
							<div class="card mb-4 card-primary shadow">
								<div class="card-header">
									<div class="title">
										<h5>@lang('Accessibility')</h5>
									</div>
								</div>
								<div class="card-body">

									<div class="title">
										<h6 class="text-dark">@lang('Manage Branch')</h6>
									</div>
								</div>
								<span class="text-danger mb-2 permissions-error"></span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
							<button type="button" class="btn btn-primary"
									@click.prevent="rolePermissionSubmit">@lang('save')</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{--	Edit Modal--}}
		<div id="editModal" class="modal fade" tabindex="-1" role="dialog"
			 aria-labelledby="primary-header-modalLabel"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-dark font-weight-bold"
							id="primary-header-modalLabel">@lang('Update Roles')</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>

					<form>
						<div class="modal-body">
							<input type="hidden" v-model="item.id" class="editRoleId" value="">
							<div class="col-12">
								<label for="">@lang('Name')</label>
								<input
									type="text"
									class="form-control editRoleName"
									v-model="item.name"
									placeholder="@lang('Name')"/>
								<span class="text-danger name-error"></span>
							</div>
							<div class="col-md-12 my-3">
								<label for="">@lang('Status') </label>
								<div class="selectgroup w-100">
									<label class="selectgroup-item">
										<input type="radio" v-model="item.status" value="0" class="selectgroup-input"
											   :checked="item.status == 0">
										<span class="selectgroup-button">@lang('OFF')</span>
									</label>
									<label class="selectgroup-item">
										<input type="radio" v-model="item.status" value="1" class="selectgroup-input"
											   :checked="item.status == 1">
										<span class="selectgroup-button">@lang('ON')</span>
									</label>
								</div>
							</div>
							<div class="card mb-4 card-primary shadow">
								<div class="card-header">
									<div class="title">
										<h5>@lang('Accessibility')</h5>
									</div>
								</div>
								<div class="card-body">
									@if(config('permissionList'))
										<div class="row">
											@foreach(config('permissionList') as $key => $item)
												@php
													$words = explode(" ", $key);
													$camelCaseKey = lcfirst(implode("", $words));
												@endphp
												<div class="input-box col-md-6 mt-3">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox"
															   v-model="item.permissions"
															   value="{{$item}}" role="switch"
															   id="{{ $camelCaseKey }}"/>
														<label class="form-check-label"
															   for="{{ $camelCaseKey }}">{{str_replace('_',' ',ucfirst($key))}}</label>
													</div>
												</div>
											@endforeach
										</div>
									@endif
								</div>
								<span class="text-danger mb-2 permissions-error"></span>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
								<button type="button" @click.prevent="rolePermissionUpdate"
										class="btn btn-primary">@lang('update')</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{--	Delete Modal--}}
		<div id="delete-modal" class="modal fade" tabindex="-1" role="dialog"
			 aria-labelledby="primary-header-modalLabel"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title text-dark font-weight-bold"
							id="primary-header-modalLabel">@lang('Delete Confirmation')</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					</div>
					<form action="" method="post" class="deleteRoute">
						@csrf
						@method('delete')
						<div class="modal-body">
							<p>@lang('Are you sure want to delete this roles')</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
							<button type="submit" class="btn btn-primary">@lang('Confirm')</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('extra_scripts')
	<script>
		'use strict'

		$(document).on('click', '.notiflix-confirm', function () {
			var route = $(this).data('route');
			$('.deleteRoute').attr('action', route)
		});

		var newApp = new Vue({
			el: "#role-permission-app",
			data: {
				item: {
					name: "",
					id: "",
					status: "",
					permissions: [],
				}
			},
			mounted() {
				this.item.status = 1;
			},
			methods: {
				rolePermissionSubmit() {
					var $url = '{{route('admin.role.create')}}'
					axios.post($url, this.item)
						.then(function (response) {
							if (response.data.result) {
								location.reload();
							}
						})
						.catch(function (error) {
							let errors = error.response.data;
							errors = errors.errors
							for (let err in errors) {
								let selector = document.querySelector(`.${err}-error`);
								if (selector) {
									selector.innerText = `${errors[err]}`;
								}
							}
						});
				},
				makeDataEmpty() {
					this.item.name = "";
					this.item.id = "";
					this.item.permissions = [];
				},
				editRole(obj) {
					this.makeDataEmpty();
					this.item.name = obj.name;
					this.item.id = obj.id;
					this.item.status = obj.status;
					this.item.permissions = obj.permission;
					if (0 < obj.permission.length) {
						obj.permission.map(function (obj, i) {
							$(`.permission-check[value="${obj}"]`).attr('checked', 'checked');
						});
					}
				},
				rolePermissionUpdate() {
					var $url = '{{route('admin.role.update')}}'
					axios.post($url, this.item)
						.then(function (response) {
							if (response.data.result) {
								location.reload();
							}
						})
						.catch(function (error) {
							let errors = error.response.data;
							errors = errors.errors
							for (let err in errors) {
								let selector = document.querySelector(`.${err}-error`);
								if (selector) {
									selector.innerText = `${errors[err]}`;
								}
							}
						});
				},
			}
		})
	</script>
@endpush
