<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;
	protected $guarded = ['id'];

	public function transactional()
	{
		return $this->morphMany(Transaction::class, 'transactional');
	}

}
