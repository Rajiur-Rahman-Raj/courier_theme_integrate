<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultShippingRateOperatorCountry extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function shippingDay(){
		return $this->belongsTo(ShippingDate::class, 'shipping_date_id', 'id');
	}

	public function parcelType(){
		return $this->belongsTo(ParcelType::class, 'parcel_type_id', 'id');
	}

}
