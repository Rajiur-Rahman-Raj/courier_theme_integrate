<?php

namespace App\Models\Admin;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageVariant extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $appends = ['statusMessage', 'images', 'editVariantRoute'];

	public function package()
	{
		return $this->belongsTo(Package::class, 'package_id', 'id');
	}

	public function packingService(){
		return $this->hasOne(PackingService::class, 'variant_id');
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

	public function getImagesAttribute()
	{
		return getFile($this->driver, $this->image);
	}

	public function getEditVariantRouteAttribute()
	{
		return route('variantUpdate',$this->id);
	}
}
