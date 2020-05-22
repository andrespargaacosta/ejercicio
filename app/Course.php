<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Course extends Model {    


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'name',
        'code'
    ];
    protected $hidden = [
    	'created_at',
    	'updated_at'
    ];

    /*
    Store rules & errors for validation
    */
    private $rules;
    private $errors;


    /*
	here we validate our inputs vased on our rules for this model
	*/
    public function validate(array $data){
        // make a new validator object
        $v = Validator::make($data, $this->rules);

        // check for failure
        if ($v->fails()){
            // set errors and return false
            $this->errors = $v->errors()->toArray();
            return false;
        }

        // validation pass
        return true;
    }

    /*
	return the validation errors
    */
    public function errors(){
        return $this->errors;
    }

    /*
    Assign rules for validarion, accepts an array with the rules
    */
    public function rules(array $rules){
    	$this->rules = $rules;
    }
}