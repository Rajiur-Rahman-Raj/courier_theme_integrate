<?php

namespace App\Models;

use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use HasFactory, Notifiable, Notify;

	protected $appends = ['mobile', 'created_at'];

	public function siteNotificational()
	{
		return $this->morphOne(SiteNotification::class, 'siteNotificational', 'site_notificational_type', 'site_notificational_id');
	}

	public function funds()
	{
		return $this->hasMany(Fund::class)->latest()->where('status', '!=', 0);
	}

	public function payouts()
	{
		return $this->hasMany(Payout::class)->latest()->where('status', '==', 2);
	}

	public function profile()
	{
		return $this->hasOne(UserProfile::class, 'user_id', 'id');
	}

	public function getMobileAttribute()
	{
		return optional($this->profile)->phone_code . optional($this->profile)->phone;
	}

	public function profilePicture()
	{
		$filePath = 'assets/upload/userProfile/';
		$fileName = optional($this->profile)->profile_picture ?? 'boy.png';

		if (file_exists($filePath . $fileName)) {
			$url = asset($filePath . $fileName);
		} else {
			$hashEmail = md5(strtolower(trim($this->email)));
			$url = "https://www.gravatar.com/avatar/$hashEmail?s=500&d=mp";
		}
		return $url;
	}

	protected $guarded = ['id'];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function sendPasswordResetNotification($token)
	{
		$this->mail($this, 'PASSWORD_RESET', $params = [
			'message' => '<a href="' . url('password/reset', $token) . '?email=' . $this->email . '" target="_blank">Click To Reset Password</a>'
		]);

	}

	public function branch(){
		return $this->hasOne(BranchManager::class, 'admin_id');
	}

	public function getCreatedAtAttribute($value)
	{
		return $this->localDateFormat($value);
	}

	protected function localDateFormat($value)
	{
		if (isset($value)) {
			if (isset(auth()->user()->timezone)) {
				return Carbon::parse(Carbon::parse($value)->setTimezone(auth()->user()->timezone)->toDateTimeString());

			}
			return Carbon::parse($value);
		}
		return null;
	}

}
