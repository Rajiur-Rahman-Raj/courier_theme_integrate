<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchManager extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function branch(){
		return $this->belongsTo(Branch::class, 'branch_id', 'id');
	}

	public function admin(){
		return $this->belongsTo(Admin::class, 'admin_id', 'id');
	}

	public function branchEmployees(){
		return $this->hasMany(BranchEmployee::class, 'branch_id', 'branch_id');
	}

	public function branchClients(){
		return $this->hasMany(UserProfile::class, 'branch_id', 'branch_id');
	}
}
