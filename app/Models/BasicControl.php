<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicControl extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected static function boot()
	{
		parent::boot();
		static::saved(function (){
			\Cache::forget('ConfigureSetting');
		});
	}

	public function operatorCountry(){
		return $this->belongsTo(Country::class, 'operator_country');
	}

}
