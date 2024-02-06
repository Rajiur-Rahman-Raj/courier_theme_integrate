<?php

namespace App\Models\Admin;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingService extends Model
{
    use HasFactory;

	protected $guarded = ['id'];
	protected $appends = ['statusMessage'];

	public function package(){
		return $this->belongsTo(Package::class, 'package_id', 'id');
	}

	public function variant(){
		return $this->belongsTo(PackageVariant::class, 'variant_id', 'id');
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
