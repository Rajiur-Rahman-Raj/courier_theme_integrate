<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	use HasFactory;

	protected $fillable = ['user_id'];

	public function user(){
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function branch(){
		return $this->belongsTo(Branch::class, 'branch_id', 'id');
	}

	public function country(){
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	public function state(){
		return $this->belongsTo(State::class, 'state_id', 'id');
	}

	public function city(){
		return $this->belongsTo(City::class, 'city_id', 'id');
	}

	public function area(){
		return $this->belongsTo(Area::class, 'area_id', 'id');
	}

	public function getBranch(){
		return $this->belongsTo(BranchManager::class, 'branch_id', 'user_id');
	}

}
