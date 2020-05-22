<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Pagination\Paginator;
use App\Rules\ValidateRut;

use App\Student;


class StudentController extends BaseController{

	const PAGESIZE = 10;

    /*
    shows the students, takes an optional parameter (an int) for pagination
    */
    public function index(Request $request,int $page = 1){
    	$student = Student::all();
    	$total = $student->count();
    	$pages = ceil($total/self::PAGESIZE);
    	if($pages < $page){
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');	
    	}

    	$data = [
    		"students"=>[],
    		"total" => $total,
    		"path" => Paginator::resolveCurrentPath(),
    		"pages" => $pages,
    		"pageName" => "page"
    	];

    	foreach ($student->forPage($page, self::PAGESIZE) as $student) {
    		$data['students'][] = $student->toArray();
    	}

    	return response(json_encode($data),200)->header('Content-Type','application/json');
    }

    /*
    takes a required parameter (an int) to show a course, returns a 404 if can't match the required id 
    */
    public function show(Request $request,int $id = 1){

    	$student = Student::where('id',$id)->get()->first();

    	if($student){
    		return response($student->toJson(),200)->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');
    	}
    }

    /*
    show all the students, takes no argument
    */
    public function all(Request $request){
    	
    	$data = [
            'students'=>[]
        ];
    	foreach (Student::all() as $student) {
    		$data['students'][] = $student->toArray();
    	}
    	return response( json_encode($data),200 )->header('Content-Type', 'application/json');
    }


    /*
    creates a student based on the requiered id (int) plus sent JSON sent through POST
    */
    public function create(Request $request){
    	$data = array_map('trim', $request->all());
    	
    	$student = new Student();
    	$student->rules(array(
            'rut' =>[new ValidateRut,'unique:students','regex:/[0-9]{7,8}\-[0-9kK]/','required'],
            'name' =>'required|string|between:3,255',
            'lastName' =>'required|string|between:3,255',
            'age' =>'required|boolean',
            'course' => 'string|size:4'
	    ));
    	
    	if($student->validate($data)){
			$student->fill($data);
			$student->save();
			return response('',201 )->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>$student->errors())),404)->header('Content-Type','application/json');	
    	}
    }

    /*
    updates a student based on the requiered id (int) plus sent JSON sent through PUT
    */
    public function update(Request $request,int $id){
    	$data = array_map('trim', $request->all());
    	$student = Student::where('id',$id)->get()->first();

    	$student->rules(array(
            'rut' =>[new ValidateRut,'unique:students',"regex:/[0-9]{7,8}\-[0-9kK]/"],
            'name' =>'string|between:3,255',
            'lastName' =>'string|between:3,255',
            'age' =>'boolean',
            'course' => 'string|size:4'
	    ));
	    
	    if($student->validate($data)){
			$student->update($data);
			$student->save();
			return response('',200 )->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>$student->errors())),404)->header('Content-Type','application/json');	
    	}
    }

    /*
    deletes the student based on the path id, returns a 404 if can't match the required id 
    */
    public function delete(Request $request,int $id){
    	$student = Student::where('id',$id)->delete();
    	if($student){
    		return response('',200)->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');	
    	}
    }
}
