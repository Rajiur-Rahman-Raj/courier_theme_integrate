<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PackageVariant;
use App\Models\Admin\PackingService;
use App\Models\Package;
use App\Models\ParcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;
use App\Traits\Upload;

class PackingController extends Controller
{
	use Upload;
	public function packingServiceList(Request $request)
	{
		$search = $request->all();
		$data['allPackages'] = Package::latest()->paginate(config('basic.paginate'));

		$data['allPackageVariants'] = PackageVariant::get();

		$data['allVariants'] = PackageVariant::with('package')
			->latest()
			->groupBy('package_id')
			->paginate(config('basic.paginate'));


		$data['allPackingService'] = PackingService::with('package', 'variant')
			->when(isset($search['package']), function ($query) use ($search){
				return $query->whereHas('package', function ($q) use ($search) {
					$q->whereRaw("package_name REGEXP '[[:<:]]{$search['package']}[[:>:]]'");
				});
			})
			->when(isset($search['variant']), function ($query) use ($search){
				return $query->whereHas('variant', function ($q) use ($search) {
					$q->whereRaw("variant REGEXP '[[:<:]]{$search['variant']}[[:>:]]'");
				});
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q3) use ($search) {
				return $q3->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q4) use ($search) {
				return $q4->where('status', 0);
			})
			->orderBy('package_id', 'ASC')
			->paginate(config('basic.paginate'));
		$data['adminAccessRoute'] = adminAccessRoute(config('permissionList.Packaging_Service.Service_List.permission.edit'));

		return view('admin.packing.index', $data);
	}

	public function packageStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'package_name' => ['required'],
		];

		$message = [
			'package_name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->package_name == null) {
			return back()->with('error', 'Package Name field is required');
		}

		$package = new Package();
		$package->package_name = $request->package_name;
		$package->status = $request->status;
		$package->save();

		return back()->with('success', 'Package Created Successfully!');
	}

	public function packageUpdate(Request $request, $id){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'package_name' => ['required'],
		];

		$message = [
			'package_name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->package_name == null) {
			return back()->with('error', 'Package Name field is required');
		}

		$package = Package::findOrFail($id);
		$package->package_name = $request->package_name;
		$package->status = $request->status;
		$package->save();

		return back()->with('success', 'Package Updated Successfully!');
	}

	public function variantStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'package_id' => ['required', 'exists:packages,id'],
			'variant' => ['required', 'string', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'package_id.required' => 'Please select a package',
			'variant.required' => 'Variant name is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->variant == null) {
			return back()->with('error', 'Variant field is required');
		}

		$packageVariant = new PackageVariant();
		$packageVariant->package_id = $request->package_id;
		$packageVariant->variant = $request->variant;
		$packageVariant->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.packing.path'));
				if ($image) {
					$packageVariant->image = $image['path'] ?? null;
					$packageVariant->driver = $image['driver'] ?? null;
				}
			}
		catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$packageVariant->save();

		return back()->with('success', 'Package Variant Created Successfully!');
	}

	public function variantUpdate(Request $request, $id){
		$purifiedData = Purify::clean($request->except('_token', '_method', 'image'));

		$rules = [
			'package_id' => ['required', 'exists:packages,id'],
			'variant' => ['required', 'string', 'max:100'],
			'image' => ['nullable', 'max:3072', 'mimes:jpg,jpeg,png']
		];

		$message = [
			'package_id.required' => 'Please select a package',
			'variant.required' => 'Variant name is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->variant == null) {
			return back()->with('error', 'Variant field is required');
		}

		$packageVariant = PackageVariant::findOrFail($id);

		$packageVariant->package_id = $request->package_id;
		$packageVariant->variant = $request->variant;
		$packageVariant->status = $request->status;

		if ($request->hasFile('image')) {
			try {
				$image = $this->fileUpload($request->image, config('location.packing.path'));
				if ($image) {
					$packageVariant->image = $image['path'] ?? null;
					$packageVariant->driver = $image['driver'] ?? null;
				}
			}
			catch (\Exception $exp) {
				return back()->with('error', 'Image could not be uploaded.');
			}
		}

		$packageVariant->save();

		return back()->with('success', 'Package Variant Updated Successfully!');
	}

	public function packingServiceStore(Request $request){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'package_id' => ['required', 'exists:packages,id'],
			'variant_id' => ['required', 'exists:package_variants,id'],
			'cost' => ['required', 'numeric', 'min:1', 'not_in:0'],
			'weight' => ['required', 'numeric', 'min:0.5', 'not_in:0']
		];

		$message = [
			'package_id.required' => 'Please select a package',
			'variant_id.required' => 'Please select a variant',
			'cost.required' => 'Variant cost is required',
			'weight.required' => 'Variant weight is required'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->cost == null) {
			return back()->with('error', 'Cost field is required');
		}

		if ($request->weight == null) {
			return back()->with('error', 'Weight field is required');
		}

		$packingService = new PackingService();

		$packingService->package_id = $request->package_id;
		$packingService->variant_id = $request->variant_id;
		$packingService->cost = $request->cost;
		$packingService->weight = $request->weight;
		$packingService->status = $request->status;
		$packingService->save();
		return back()->with('success', 'Packing Service Created Successfully!');
	}

	public function packingServiceUpdate(Request $request, $id){
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'package_id' => ['required', 'exists:packages,id'],
			'variant_id' => ['required', 'exists:package_variants,id'],
			'cost' => ['required', 'numeric', 'min:1', 'not_in:0'],
			'weight' => ['required', 'numeric', 'min:0.5', 'not_in:0']
		];

		$message = [
			'package_id.required' => 'Please select a package',
			'variant_id.required' => 'Please select a variant',
			'cost.required' => 'Variant cost is required',
			'weight.required' => 'Variant weight is required'
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->cost == null) {
			return back()->with('error', 'Cost field is required');
		}

		if ($request->weight == null) {
			return back()->with('error', 'Weight field is required');
		}

		$packingService = PackingService::findOrFail($id);

		$packingService->package_id = $request->package_id;
		$packingService->variant_id = $request->variant_id;
		$packingService->cost = $request->cost;
		$packingService->weight = $request->weight;
		$packingService->status = $request->status;
		$packingService->save();
		return back()->with('success', 'Packing Service Updated Successfully!');
	}

	public function getSelectedPackageVariant(Request $request){
		$results = PackageVariant::where('package_id', $request->id)->where('status', 1)->get();
		return response($results);
	}

	public function getSelectedVariantService(Request $request){
		$results = PackingService::where('package_id', $request->packageId)->where('variant_id', $request->variantId)->where('status', 1)->get();
		return response($results);
	}

	public function getSelectedParcelUnitService(Request $request){
		$results = ParcelService::where('parcel_type_id', $request->parcelTypeId)->where('parcel_unit_id', $request->parcelUnitId)->where('status', 1)->get();
		return response($results);
	}

}
