<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Pagination\Paginator;

use App\Course;


class CourseController extends BaseController{

	const PAGESIZE = 10;

	/*
	shows the students, takes an optional paramete (an int)r for pagination
	*/

    public function index(Request $request,int $page = 1){
    	$course = Course::all();
    	$total = $course->count();
    	$pages = ceil($total/self::PAGESIZE);
    	if($pages < $page && $total != 0){
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');	
    	}

    	$data = [
    		"courses"=>[],
    		"total" => $total,
    		"path" => Paginator::resolveCurrentPath(),
    		"pages" => $pages,
    		"pageName" => "page"
    	];

    	foreach ($course->forPage($page, self::PAGESIZE) as $course) {
    		$data['courses'][] = $course->toArray();
    	}

    	return response(json_encode($data),200)->header('Content-Type','application/json');
    }
    /*
    takes a required parameter (an int) to show a course, returns a 404 if can't match the required id 
    */
    public function show(Request $request,int $id = 1){

    	$course = Course::where('id',$id)->get()->first();

    	if($course){
    		return response($course->toJson(),200)->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');
    	}
    }

    /*
    show all the courses, takes no argument
    */
    public function all(Request $request){
    	
    	$data = [
    		'courses'=>[]
    	];
    	foreach (Course::all() as $course) {
    		$data['courses'][] = $course->toArray();
    		
    	}
    	return response( json_encode($data),200 )->header('Content-Type', 'application/json');
    }

    /*
    creates a course based on the requiered id (int) plus sent JSON sent through POST
    */
    public function create(Request $request){
    	$data = array_map('trim', $request->all());
    	
    	$course = new Course();
    	$course->rules(array(
			'name' => 'required|string|between:3,255',
			'code' => 'required|unique:courses|size:4',
	    ));
    	
    	if($course->validate($data)){
			$course->fill($data);
			$course->save();
			return response('',201 )->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>$course->errors())),404)->header('Content-Type','application/json');	
    	}
    }

    /*
    updates a course based on the requiered id (int) plus sent JSON sent through PUT
    */
    public function update(Request $request,int $id){
    	$data = array_map('trim', $request->all());
    	$course = Course::where('id',$id)->get()->first();

    	$course->rules(array(
			'name' => 'string|between:3,255',
			'code' => 'unique:courses|size:4',
	    ));
	    
	    if($course->validate($data)){
			$course->update($data);
			$course->save();
			return response('',200 )->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>$course->errors())),404)->header('Content-Type','application/json');	
    	}
    }

    /*
    deletes the course based on the path id, returns a 404 if can't match the required id 
    */
    public function delete(Request $request,int $id){
    	$course = Course::where('id',$id)->delete();
    	if($course){
    		return response('',200)->header('Content-Type','application/json');
    	}else{
    		return response(json_encode(array('error'=>"Resource not found")),404)->header('Content-Type','application/json');	
    	}
    }
}
