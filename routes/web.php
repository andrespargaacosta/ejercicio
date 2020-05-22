<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//In this endpoint we get a JWT
$router->get('token', 'JWTController@getToken');


//We add a common middleware, only accepting json and showing data if the JWT exists and was sent
$router->group(['middleware' => ['json.check','jwt.check'] ], function () use ($router) {
	/*

	Since lumen it's a micro framework, we need to define each route and their verbs

	First, the courses:
	1.- shows the students, takes an optional path (/page/) and a "page" (an int) parameter for pagination
	2.- takes a required parameter (an int) to show a course, returns a 404 if can't match the required id 
	3.- show all the courses, takes no argument
	4.- creates a course based on the requiered id (int) plus sent JSON sent through POST
	5.- updates a course based on the requiered id (int) plus sent JSON sent through PUT
	6.- deletes the course based on the path id, returns a 404 if can't match the required id 
	*/
	$router->get('courses[/page/{page:[0-9]+}]','CourseController@index');
	$router->get('courses/{id:[0-9]+}','CourseController@show');
	$router->get('courses/all','CourseController@all');
	$router->post('courses/','CourseController@create');
	$router->put('courses/{id:[0-9]+}','CourseController@update');
	$router->delete('courses/{id:[0-9]+}','CourseController@delete');

	/*
	Now, the student routes and methods
	1.- shows the students, takes an optional path (/page/) and a "page" (an int) parameter for pagination
	2.- takes a required parameter (an int) to show a course, returns a 404 if can't match the required id 
	3.- show all the students, takes no argument
	4.- creates a student based on the requiered id (int) plus sent JSON sent through POST
	5.- updates a student based on the requiered id (int) plus sent JSON sent through PUT
	6.- deletes the student based on the path id, returns a 404 if can't match the required id 
	*/
	$router->get('students[/page/{page:[0-9]+}]','StudentController@index');
	$router->get('students/{id:[0-9]+}','StudentController@show');
	$router->get('students/all','StudentController@all');
	$router->post('students/','StudentController@create');
	$router->put('students/{id:[0-9]+}','StudentController@update');
	$router->delete('students/{id:[0-9]+}','StudentController@delete');
});