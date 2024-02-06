<?php

namespace App\Models;

use App\Models\Admin\PackageVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	protected $appends = ['statusMessage'];

	public function variant(){
		return $this->hasMany(PackageVariant::class, 'package_id', 'id');
	}

	public function totalVariant(){
		$totalVariant = PackageVariant::where('package_id', $this->id)->count();
		return $totalVariant;
	}

	public function getStatusMessageAttribute()
	{
		if ($this->status == 0) {
			return '<span class="badge badge-light">
            <i class="fa fa-circle text-danger danger font-12"></i> '. trans('Deactive') . '</span>';
		}
		return '<span class="badge badge-light">
            <i class="fa fa-circle text-success success font-12"></i> '. trans('Active') . '</span>';
	}
}
