<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 * @property string name
 * @package App
 */
class Currency extends Model
{
	protected $fillable = [
		'name',
	];


}