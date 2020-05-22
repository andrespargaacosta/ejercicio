<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {    

protected $table = 'students';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'name',
        'username',
        'password'
    ];

    protected $hidden = [ 'password' ];
}