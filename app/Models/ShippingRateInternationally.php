<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRateInternationally extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function fromCountry(){
		return $this->belongsTo(Country::class, 'from_country_id', 'id');
	}

	public function toCountry(){
		return $this->belongsTo(Country::class, 'to_country_id', 'id');
	}

	public function fromState(){
		return $this->belongsTo(State::class, 'from_state_id', 'id');
	}

	public function toState(){
		return $this->belongsTo(State::class, 'to_state_id', 'id');
	}

	public function fromCity(){
		return $this->belongsTo(City::class, 'from_city_id', 'id');
	}

	public function toCity(){
		return $this->belongsTo(City::class, 'to_city_id', 'id');
	}

	public function parcelType(){
		return $this->belongsTo(ParcelType::class, 'parcel_type_id', 'id');
	}

	public function getTotalCountry($id){
		return ShippingRateInternationally::whereNull(['from_state_id', 'from_city_id'])->where('parcel_type_id', $id)->count();
	}

	public function getTotalState($id){
		return ShippingRateInternationally::whereNotNull('from_state_id')->whereNull('from_city_id')->where('parcel_type_id', $id)->count();
	}

	public function getTotalCity($id){
		return ShippingRateInternationally::whereNotNull('from_city_id')->where('parcel_type_id', $id)->count();
	}
}
