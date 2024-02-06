<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRateOperatorCountry extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

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

	public function fromArea(){
		return $this->belongsTo(Area::class, 'from_area_id', 'id');
	}

	public function toArea(){
		return $this->belongsTo(Area::class, 'to_area_id', 'id');
	}

	public function parcelType(){
		return $this->belongsTo(ParcelType::class, 'parcel_type_id', 'id');
	}

	public function getTotalState($id){
		return ShippingRateOperatorCountry::whereNull(['from_city_id', 'from_area_id'])->where('parcel_type_id', $id)->count();
	}

	public function getTotalCity($id){
		return ShippingRateOperatorCountry::whereNotNull('from_city_id')->whereNull('from_area_id')->where('parcel_type_id', $id)->count();
	}

	public function getTotalArea($id){
		return ShippingRateOperatorCountry::whereNotNull('from_area_id')->where('parcel_type_id', $id)->count();
	}
}
