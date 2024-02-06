<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelUnit extends Model
{
    use HasFactory;
	protected $guarded = ['id'];

	protected $appends = ['statusMessage'];

	public function parcelType()
	{
		return $this->belongsTo(ParcelType::class, 'parcel_type_id', 'id');
	}

	public function getStatusMessageAttribute()
	{
		if ($this->status == 0) {
			return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> ' . trans('Deactive') . '</span>';
		}
		return '<span class="badge badge-light">
            <i class="fa fa-circle text-success success font-12"></i> ' . trans('Active') . '</span>';
	}
}
