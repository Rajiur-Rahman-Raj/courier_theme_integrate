<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function country(){
		return $this->belongsTo(Country::class, 'country_id', 'id')->where('status', 1);
	}

	public function getTotalCity(){
		$allCity = City::where('state_id', $this->id)->where('status', 1)->count();
		return $allCity;
	}

}
