<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
  	protected $table='address';

	/* Country */
	public function getCountry()
	{
		return $this->hasOne(Country::class, 'id', 'country');
	}

	/* State */
	public function getState()
	{
		return $this->hasOne(State::class, 'id', 'state');
	}
}
