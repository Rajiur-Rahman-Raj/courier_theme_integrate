<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function country(){
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	public function state(){
		return $this->belongsTo(State::class, 'state_id', 'id');
	}

	public function city(){
		return $this->belongsTo(City::class, 'city_id', 'id');
	}
}
