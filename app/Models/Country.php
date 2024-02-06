<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function getTotalState(){
		$allState = State::where('country_id', $this->id)->where('status', 1)->count();
		return $allState;
	}

	public function state(){
		return State::where('country_id', $this->id)->where('status', 1)->get();
	}

	public function states(){
		return $this->hasMany(State::class, 'country_id');
	}

	public function cities(){
		return $this->hasMany(City::class, 'country_id');
	}

	public function areas(){
		return $this->hasMany(Area::class, 'country_id');
	}

}
