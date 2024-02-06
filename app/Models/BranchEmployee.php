<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchEmployee extends Model
{
    use HasFactory;

	protected $guarded = ['id'];

	public function branch(){
		return $this->belongsTo(Branch::class, 'branch_id', 'id');
	}

	public function admin(){
		return $this->belongsTo(Admin::class, 'admin_id', 'id');
	}

	public function department(){
		return $this->belongsTo(Department::class, 'department_id', 'id');
	}
}
