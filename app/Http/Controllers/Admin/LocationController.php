<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

use Illuminate\Support\Facades\File;

class LocationController extends Controller
{
	public function countryList(Request $request)
	{
		$search = $request->all();
		$data['allCountries'] = Country::when(isset($search['country']), function ($q) use ($search) {
			return $q->whereRaw("name REGEXP '[[:<:]]{$search['country']}[[:>:]]'");
		})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q2) use ($search) {
				return $q2->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q3) use ($search) {
				return $q3->where('status', 0);
			})
			->orderBy('status', 'desc')
			->paginate(config('basic.paginate'));

		return view('admin.country.index', $data);
	}

	public function countryStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$country = new Country();

		$country->name = $request->name;
		$country->status = $request->status;

		$country->save();
		return back()->with('success', 'Country Created Successfully!');
	}

	public function countryUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$country = Country::findOrFail($id);

		$country->name = $request->name;
		$country->status = $request->status;

		$country->save();
		return back()->with('success', 'Country Updated Successfully!');
	}


	public function countryEnable($id){

		try {
			$country = Country::findOrFail($id);
			$country->status = 1;
			$country->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}

	}

	public function countryDisable($id){
		try {
			$country = Country::findOrFail($id);
			$country->status = 0;
			$country->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}

	}

	public function enableMultipleCountry(Request $request){

		if ($request->strIds == null) {
			session()->flash('error', __('You do not select country.'));
			return response()->json(['error' => 1]);
		} else {
			Country::whereIn('id',$request->strIds)->update(['status'=>1]);
			session()->flash('success', __('Country Status Has Been Enabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function disableMultipleCountry(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select country.'));
			return response()->json(['error' => 1]);
		} else {
			Country::whereIn('id',$request->strIds)->update(['status'=>0]);
			session()->flash('success', __('Country Status Has Been Disabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function multipleCountryDeleted(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select country.'));
			return response()->json(['error' => 1]);
		} else {
			foreach ($request->strIds as $country_id){
				$country = Country::with('states', 'cities', 'areas')->findOrFail($country_id);
				if ($country->states){
					foreach ($country->states as $state){
						$state->delete();
					}
				}

				if ($country->cities){
					foreach ($country->cities as $city){
						$city->delete();
					}
				}

				if ($country->areas){
					foreach ($country->areas as $area){
						$area->delete();
					}
				}
				$country->delete();
			}

			session()->flash('success', __('Countries Has Been Deleted'));
			return response()->json(['success' => 1]);
		}
	}


	public function stateList(Request $request, $type=null, $id=null)
	{
		$stateManagement = config('stateManagement');
		$types = array_keys($stateManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $stateManagement[$type]['title'];

		$search = $request->all();
		$data['allCountires'] = Country::where('status', 1)->get();

		$data['allStates'] = State::with('country')
			->whereHas('country', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->when(isset($search['country']), function ($query) use ($search) {
				$query->whereHas('country', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['country']}[[:>:]]'");
				});
			})
			->when(isset($search['name']), function ($q) use ($search) {
				return $q->whereRaw("name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q2) use ($search) {
				return $q2->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q3) use ($search) {
				return $q3->where('status', 0);
			})
			->when(isset($type) && $type == 'state-list', function ($q) use ($search){
				$q->groupBy('country_id');
			})
			->when(isset($id) && $id != null, function ($query) use ($id){
				return $query->where('country_id', $id);
			})
			->orderBy('status', 'desc')
			->paginate(config('basic.paginate'));


		return view($stateManagement[$type]['state_view'], $data);
	}

	public function stateStore(Request $request)
	{

		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'name.required' => 'Name field is required',
			'country_id.required' => 'Please select a country',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$state = new State();

		$state->country_id = $request->country_id;
		$state->name = $request->name;
		$state->status = $request->status;
		$state->save();

		return back()->with('success', 'State Created Successfully!');
	}

	public function stateUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));


		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'name' => ['required', 'max:100', 'string'],
		];

		$message = [
			'country_id.required' => 'Please select a country',
			'name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}


		$state = State::findOrFail($id);

		$state->country_id = $request->country_id;
		$state->name = $request->name;
		$state->status = $request->status;
		$state->save();
		return back()->with('success', 'State Updated Successfully!');
	}

	public function stateEnable($id){
		try {
			$state = State::findOrFail($id);
			$state->status = 1;
			$state->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function stateDisable($id){
		try {
			$state = State::findOrFail($id);
			$state->status = 0;
			$state->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function enableMultipleState(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select state.'));
			return response()->json(['error' => 1]);
		} else {
			State::whereIn('id',$request->strIds)->update(['status'=>1]);
			session()->flash('success', __('State Status Has Been Enabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function disableMultipleState(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select state.'));
			return response()->json(['error' => 1]);
		} else {
			State::whereIn('id',$request->strIds)->update(['status'=>0]);
			session()->flash('success', __('State Status Has Been Disabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function cityList(Request $request, $type=null, $id=null)
	{

		$cityManagement = config('cityManagement');
		$types = array_keys($cityManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $cityManagement[$type]['title'];

		$search = $request->all();
		$data['allCountires'] = Country::where('status', 1)->get();
		$data['allStates'] = State::where('status', 1)->get();

		$data['allCities'] = City::with('country', 'state')
			->whereHas('country', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->whereHas('state', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->when(isset($search['country']), function ($query) use ($search) {
				$query->whereHas('country', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['country']}[[:>:]]'");
				});
			})
			->when(isset($search['state']), function ($query) use ($search) {
				$query->whereHas('state', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['state']}[[:>:]]'");
				});
			})
			->when(isset($search['name']), function ($q) use ($search) {
				return $q->whereRaw("name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q2) use ($search) {
				return $q2->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q3) use ($search) {
				return $q3->where('status', 0);
			})
			->when(isset($type) && $type == 'city-list', function ($q) use ($search){
				$q->groupBy('state_id');
			})
			->when(isset($id) && $id != null, function ($query) use ($id){
				return $query->where('state_id', $id);
			})
			->orderBy('country_id', 'ASC')
			->orderBy('status', 'desc')
			->paginate(config('basic.paginate'));

		return view($cityManagement[$type]['city_view'], $data);
	}

	public function cityStore(Request $request)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'name' => ['required', 'max:191', 'string'],
		];

		$message = [
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state',
			'name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$city = new City();

		$city->country_id = $request->country_id;
		$city->state_id = $request->state_id;
		$city->name = $request->name;
		$city->status = $request->status;
		$city->save();

		return back()->with('success', 'City Created Successfully!');
	}

	public function cityUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'name' => ['required', 'max:191', 'string'],
		];

		$message = [
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state',
			'name.required' => 'Name field is required',
		];


		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$city = City::findOrFail($id);

		$city->country_id = $request->country_id;
		$city->state_id = $request->state_id;
		$city->name = $request->name;
		$city->status = $request->status;
		$city->save();

		return back()->with('success', 'City Updated Successfully!');
	}

	public function cityEnable($id){
		try {
			$city = City::findOrFail($id);
			$city->status = 1;
			$city->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function cityDisable($id){
		try {
			$city = City::findOrFail($id);
			$city->status = 0;
			$city->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function enableMultipleCity(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select city.'));
			return response()->json(['error' => 1]);
		} else {
			City::whereIn('id',$request->strIds)->update(['status'=>1]);
			session()->flash('success', __('City Status Has Been Enabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function disableMultipleCity(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select city.'));
			return response()->json(['error' => 1]);
		} else {
			City::whereIn('id',$request->strIds)->update(['status'=>0]);
			session()->flash('success', __('City Status Has Been Disabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function areaList(Request $request, $type=null, $id=null)
	{
		$areaManagement = config('areaManagement');
		$types = array_keys($areaManagement);
		abort_if(!in_array($type, $types), 404);
		$data['title'] = $areaManagement[$type]['title'];

		$search = $request->all();

		$data['allCountires'] = Country::where('status', 1)->get();
		$data['allStates'] = State::where('status', 1)->get();
		$data['allCities'] = City::where('status', 1)->get();


		$data['allAreas'] = Area::with('country', 'state', 'city')
			->whereHas('country', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->whereHas('state', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->whereHas('city', function ($query) use ($search){
				return $query->where('status', 1);
			})
			->when(isset($search['country']), function ($query) use ($search) {
				$query->whereHas('country', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['country']}[[:>:]]'");
				});
			})
			->when(isset($search['state']), function ($query) use ($search) {
				$query->whereHas('state', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['state']}[[:>:]]'");
				});
			})
			->when(isset($search['city']), function ($query) use ($search) {
				$query->whereHas('city', function ($q) use ($search) {
					return $q->whereRaw("name REGEXP '[[:<:]]{$search['city']}[[:>:]]'");
				});
			})
			->when(isset($search['name']), function ($q) use ($search) {
				return $q->whereRaw("name REGEXP '[[:<:]]{$search['name']}[[:>:]]'");
			})
			->when(isset($search['status']) && $search['status'] == 'active', function ($q2) use ($search) {
				return $q2->where('status', 1);
			})
			->when(isset($search['status']) && $search['status'] == 'deactive', function ($q3) use ($search) {
				return $q3->where('status', 0);
			})
			->when(isset($type) && $type == 'area-list', function ($q) use ($search){
				$q->groupBy('city_id');
			})
			->when(isset($id) && $id != null, function ($query) use ($id){
				return $query->where('city_id', $id);

			})
			->orderBy('country_id', 'ASC')
			->orderBy('status', 'desc')
			->paginate(config('basic.paginate'));

		return view($areaManagement[$type]['area_view'], $data);
	}

	public function areaStore(Request $request)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'city_id' => ['required', 'exists:cities,id'],
			'name' => ['required', 'max:191', 'string'],
		];

		$message = [
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state',
			'city_id.required' => 'Please select a city',
			'name.required' => 'Name field is required',
		];

		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$area = new Area();

		$area->country_id = $request->country_id;
		$area->state_id = $request->state_id;
		$area->city_id = $request->city_id;
		$area->name = $request->name;
		$area->status = $request->status;
		$area->save();

		return back()->with('success', 'Area Created Successfully!');
	}

	public function areaUpdate(Request $request, $id)
	{
		$purifiedData = Purify::clean($request->except('_token', '_method'));

		$rules = [
			'country_id' => ['required', 'exists:countries,id'],
			'state_id' => ['required', 'exists:states,id'],
			'city_id' => ['required', 'exists:cities,id'],
			'name' => ['required', 'max:191', 'string'],
		];

		$message = [
			'country_id.required' => 'Please select a country',
			'state_id.required' => 'Please select a state',
			'city_id.required' => 'Please select a city',
			'name.required' => 'Name field is required',
		];


		$validate = Validator::make($purifiedData, $rules, $message);

		if ($validate->fails()) {
			return back()->withInput()->withErrors($validate);
		}

		if ($request->name == null) {
			return back()->with('error', 'Name field is required');
		}

		$area = Area::findOrFail($id);

		$area->country_id = $request->country_id;
		$area->state_id = $request->state_id;
		$area->city_id = $request->city_id;
		$area->name = $request->name;
		$area->status = $request->status;
		$area->save();

		return back()->with('success', 'Area Updated Successfully!');
	}

	public function areaEnable($id){
		try {
			$area = Area::findOrFail($id);
			$area->status = 1;
			$area->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function areaDisable($id){
		try {
			$area = Area::findOrFail($id);
			$area->status = 0;
			$area->save();
			return back()->with('success', 'Update Successfully!');
		}catch (\Exception $exception){
			return back()->with('error', $exception->getMessage());
		}
	}

	public function enableMultipleArea(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select area.'));
			return response()->json(['error' => 1]);
		} else {
			Area::whereIn('id',$request->strIds)->update(['status'=>1]);
			session()->flash('success', __('Area Status Has Been Enabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function disableMultipleArea(Request $request){
		if ($request->strIds == null) {
			session()->flash('error', __('You do not select area.'));
			return response()->json(['error' => 1]);
		} else {
			Area::whereIn('id',$request->strIds)->update(['status'=>0]);
			session()->flash('success', __('Area Status Has Been Disabled'));
			return response()->json(['success' => 1]);
		}
	}

	public function getSeletedCountryState(Request $request){
		$results = State::where('country_id', $request->id)->where('status', 1)->get();
		return response($results);
	}

	public function getSeletedStateCity(Request $request){
		$results = City::where('state_id', $request->id)->where('status', 1)->get();
		return response($results);
	}

	public function getSeletedCityArea(Request $request){
		$results = Area::where('city_id', $request->id)->where('status', 1)->get();
		return response($results);
	}


	public function importCountries()
	{

		$this->broadcastMessage('Please wait few moments...');

		if (!File::exists('assets/worldSeeder.txt')){

			$folderToRemove = base_path('vendor/nnjeim/world/src/Database/Migrations');
			if (File::exists($folderToRemove)) {
				File::deleteDirectory($folderToRemove);
			}
			File::put('assets/worldSeeder.txt',''.time());
			$output = new \Symfony\Component\Console\Output\BufferedOutput();
			\Illuminate\Support\Facades\Artisan::call('db:seed --class=WorldSeeder');
			// Retrieve the command output, if needed
			$output = \Illuminate\Support\Facades\Artisan::output();

			$this->broadcastMessage($output->fetch());
		}

		$this->broadcastMessage('Your function completed successfully.');

	}

	protected function broadcastMessage($message)
	{
		// Return a JSON response with the message
		return response()->json(['message' => $message]);
	}


	public function countryDelete($id){
		$country = Country::with('states', 'cities', 'areas')->findOrFail($id);

		if ($country->states){
			foreach ($country->states as $state){
				$state->delete();
			}
		}

		if ($country->cities){
			foreach ($country->cities as $city){
				$city->delete();
			}
		}

		if ($country->areas){
			foreach ($country->areas as $area){
				$area->delete();
			}
		}


		$country->delete();

		return back()->with('success', 'Country Deleted Successfully');

	}

}
