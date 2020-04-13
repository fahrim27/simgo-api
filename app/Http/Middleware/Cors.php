<?php
namespace App\Http\Middleware;
use Closure;
class Cors
{
  public function handle($request, Closure $next)
  {
    // return $next($request)
	   //    ->header('Access-Control-Allow-Origin', '*')
	   //    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
	   //    ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');

  		//Budi
	 //    header("Access-Control-Allow-Origin: *");

		// $headers = [
		//  'Access-Control-Allow-Methods'=> 'GET,PUT,POST,DELETE,PATCH,OPTIONS',
		//  'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin, X-Requested-With, Authorization,Pagination'
		// ];

		// if($request->getMethod() == "OPTIONS") {
		//  	return response()->make('OK', 200, $headers);
		// }

		// $response = $next($request);
		// foreach($headers as $key => $value) $response->header($key, $value);
		// return $response;
		$response = $next($request);
		$IlluminateResponse = 'Illuminate\Http\Response';
		$SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
		$headers = [
		    'Access-Control-Allow-Origin' => '*',
		    'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
		    'Access-Control-Allow-Headers' => 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Authorization, Access-Control-Request-Headers, Pagination',
		];

		if($response instanceof $IlluminateResponse) {
		    foreach ($headers as $key => $value) {
		        $response->header($key, $value);
		    }
		    return $response;
		}

		if($response instanceof $SymfonyResopnse) {
		    foreach ($headers as $key => $value) {
		        $response->headers->set($key, $value);
		    }
		    return $response;
		}

		return $response;
    
	}
}