<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function branchManager(){
		return $this->hasOne(BranchManager::class, 'branch_id');
	}

	public function branchDriver(){
		return $this->hasMany(BranchDriver::class, 'branch_id');
	}

	public function transaction(){
		return $this->hasMany(Transaction::class, 'branch_id', 'id');
	}

	public function shipments(){
		return $this->hasMany(Shipment::class, 'sender_branch', 'id');
	}
}
