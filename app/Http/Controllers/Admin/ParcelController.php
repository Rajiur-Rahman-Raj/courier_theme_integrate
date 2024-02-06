<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParcelService;
use App\Models\ParcelType;
use App\Models\ParcelUnit;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ParcelController extends Controller
{
	use Upload;
	public function parcelServiceList(Request $request)
	{
		$search = $request->all();

		$data['allParcelTypes'] = ParcelType::latest()->paginate(config('basic.paginate'));

		$data['allParcelUnits'] = ParcelUnit::with('parcelType')
			->latest()
			->paginate(config('basic.paginate'));

		$data['allParcelService'] = ParcelService::with('parcelType', 'parcelUnit')
			->when(isset($search['parcel_type']), function ($query) use ($search){
				return $query->whereHas('parcelType', function ($q) use ($search) {
					$q->whereRaw("parcel_type REGEXP '[[:<:]]{$search['parcel_type']}[[:>:]]'");
				});
			})
			->when(isset($search['parcel_unit']), function ($query) use ($search){
				return $query->whereHas('parcelUnit', function ($q) use ($search) {
					$q->whereRaw("unit REGEXP '[[:<:]]{$search['parcel_unit']}[[:>:]]'");
				});
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->orderBy('parcel_type_id', 'ASC')
			->paginate(config('basic.paginate'));

		return view('admin.parcel.index', $data);
	}

	public function parcelTypeStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type' => ['required'],
		];

		$message = [
			'parcel_type.required' => 'Parcel type field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->parcel_type == null) {
			return back()->with('error', 'Parcel type field field is required');
		}

		$parcelType = new ParcelType();
		$parcelType->parcel_type = $request->parcel_type;
		$parcelType->status = $request->status;
		$parcelType->save();

		return back()->with('success', 'Parcel Type Created Successfully!');
	}

	public function parcelTypeUpdate(Request $request, $id){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type' => ['required'],
		];

		$message = [
			'parcel_type.required' => 'Parcel type field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->parcel_type == null) {
			return back()->with('error', 'Parcel type field is required');
		}

		$parcelType = ParcelType::findOrFail($id);
		$parcelType->parcel_type = $request->parcel_type;
		$parcelType->status = $request->status;
		$parcelType->save();

		return back()->with('success', 'Parcel Type Updated Successfully!');
	}

	public function parcelUnitStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'unit' => ['required', 'string', 'max:100'],
		];

		$message = [
			'parcel_type_id.required' => 'Please select a parcel type',
			'unit.required' => 'Unit field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->unit == null) {
			return back()->with('error', 'Unit field is required');
		}

		$parcelUnit = new ParcelUnit();
		$parcelUnit->parcel_type_id = $request->parcel_type_id;
		$parcelUnit->unit = $request->unit;
		$parcelUnit->status = $request->status;

		$parcelUnit->save();

		return back()->with('success', 'Parcel Unit Created Successfully!');
	}

	public function parcelUnitUpdate(Request $request, $id){

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'unit' => ['required', 'string', 'max:100']
		];

		$message = [
			'parcel_type_id.required' => 'Please select a parcel type',
			'unit.required' => 'Unit field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->unit == null) {
			return back()->with('error', 'Unit field is required');
		}

		$parcelUnit = ParcelUnit::findOrFail($id);

		$parcelUnit->parcel_type_id = $request->parcel_type_id;
		$parcelUnit->unit = $request->unit;
		$parcelUnit->status = $request->status;

		$parcelUnit->save();

		return back()->with('success', 'Parcel Unit Updated Successfully!');
	}

	public function parcelServiceStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'parcel_unit_id' => ['required', 'exists:parcel_units,id'],
			'cost' => ['required', 'numeric', 'min:1', 'not_in:0']
		];

		$message = [
			'parcel_type_id.required' => 'Please select a parcel type',
			'parcel_unit_id.required' => 'Please select a unit',
			'cost.required' => 'cost is required'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->cost == null) {
			return back()->with('error', 'Cost field is required');
		}

		$parcelService = new ParcelService();

		$parcelService->parcel_type_id = $request->parcel_type_id;
		$parcelService->parcel_unit_id = $request->parcel_unit_id;
		$parcelService->cost = $request->cost;
		$parcelService->status = $request->status;
		$parcelService->save();
		return back()->with('success', 'Parcel Service Created Successfully!');
	}

	public function parcelServiceUpdate(Request $request, $id){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'parcel_type_id' => ['required', 'exists:parcel_types,id'],
			'parcel_unit_id' => ['required', 'exists:parcel_units,id'],
			'cost' => ['required', 'numeric', 'min:1', 'not_in:0']
		];

		$message = [
			'parcel_type_id.required' => 'Please select a parcel type',
			'parcel_unit_id.required' => 'Please select a unit',
			'cost.required' => 'cost is required'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->cost == null) {
			return back()->with('error', 'Cost field is required');
		}


		$parcelService = ParcelService::findOrFail($id);

		$parcelService->parcel_type_id = $request->parcel_type_id;
		$parcelService->parcel_unit_id = $request->parcel_unit_id;
		$parcelService->cost = $request->cost;
		$parcelService->status = $request->status;
		$parcelService->save();
		return back()->with('success', 'Parcel Service Updated Successfully!');
	}

	public function getSelectedParcelTypeUnit(Request $request){
		$results = ParcelUnit::where('parcel_type_id', $request->id)->get();
		return response($results);
	}
}
