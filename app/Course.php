<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {    

protected $table = 'courses';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'name',
        'code'
    ];
}