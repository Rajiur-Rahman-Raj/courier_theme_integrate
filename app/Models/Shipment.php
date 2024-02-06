<?php

namespace App\Models;

use App\Models\Admin\PackageVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nette\Utils\DateTime;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

	protected $guarded = ['id'];
	protected $table = 'shipments';
	protected $dates = ['deleted_at'];

	protected $fillable = [
		'shipment_identifier',
		'shipment_type',
		'shipment_id',
		'receive_amount',
		'shipment_date',
		'delivery_date',
		'sender_branch',
		'receiver_branch',
		'sender_id',
		'receiver_id',
		'from_country_id',
		'from_state_id',
		'from_city_id',
		'from_area_id',
		'to_country_id',
		'to_state_id',
		'to_city_id',
		'to_area_id',
		'payment_by',
		'payment_type',
		'payment_status',
		'packing_services',
		'parcel_information',
		'parcel_details',
		'discount',
		'discount_amount',
		'sub_total',
		'shipping_cost',
		'return_shipment_cost',
		'cod_return_shipment_cost',
		'tax',
		'insurance',
		'pickup_cost',
		'supply_cost',
		'first_fiv',
		'last_fiv',
		'total_pay',
		'status',
		'shipment_by'
	];

	protected $casts = [
		'packing_services' => 'array',
		'parcel_information' => 'array',
		'deleted_by' => 'array',
	];

	public function transactional()
	{
		return $this->morphMany(Transaction::class, 'transactional');
	}

	public function shipmentAttachments(){
		return $this->hasMany(ShipmentAttatchment::class, 'shipment_id', 'id');
	}

	public function senderBranch(){
		return $this->belongsTo(Branch::class, 'sender_branch', 'id');
	}

	public function receiverBranch(){
		return $this->belongsTo(Branch::class, 'receiver_branch', 'id');
	}

	public function sender(){
		return $this->belongsTo(User::class, 'sender_id', 'id');
	}

	public function receiver(){
		return $this->belongsTo(User::class, 'receiver_id', 'id');
	}

	public function fromCountry(){
		return $this->belongsTo(Country::class, 'from_country_id', 'id');
	}

	public function fromState(){
		return $this->belongsTo(State::class, 'from_state_id', 'id');
	}

	public function fromCity(){
		return $this->belongsTo(City::class, 'from_city_id', 'id');
	}

	public function fromArea(){
		return $this->belongsTo(Area::class, 'from_area_id', 'id');
	}

	public function toCountry(){
		return $this->belongsTo(Country::class, 'to_country_id', 'id');
	}

	public function toState(){
		return $this->belongsTo(State::class, 'to_state_id', 'id');
	}

	public function toCity(){
		return $this->belongsTo(City::class, 'to_city_id', 'id');
	}

	public function toArea(){
		return $this->belongsTo(Area::class, 'to_area_id', 'id');
	}

	public function shipmentTypeFormat(){
		return Str::title(str_replace('_', ' ', $this->shipment_type));
	}

	public function packageName($packageId){
		$package = Package::findOrFail($packageId);
		return $package->package_name;
	}

	public function variantName($variantId){
		$packageVariant = PackageVariant::findOrFail($variantId);
		return $packageVariant->variant;
	}

	public function parcelType($parcelTypeId){
		$parcelType = ParcelType::findOrFail($parcelTypeId);
		return $parcelType->parcel_type;
	}

	public function parcelUnit($parcelUnitId){
		$parcelUnit = ParcelUnit::findOrFail($parcelUnitId);
		return $parcelUnit->unit;
	}

	public function assignToCollect(){
		return $this->belongsTo(Admin::class, 'assign_to_collect', 'id');
	}

	public function assignToDelivery(){
		return $this->belongsTo(Admin::class, 'assign_to_delivery', 'id');
	}

	public function shipmentElapsedTime(){
		$todayDate = now();
		$createdDate = $this->created_at;
		$datetime1 = new DateTime($todayDate);
		$datetime2 = new DateTime($createdDate);

		$difference = $datetime1->diff($datetime2);

		return [
			'difference'  => $difference
		];
	}

}
