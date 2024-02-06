<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
	use HasFactory;

	public function transactional()
	{
		return $this->morphTo();
	}

	public function branch(){
		return $this->belongsTo(Branch::class, 'branch_id', 'id');
	}

	public function user(){
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
