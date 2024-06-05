<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
 
    //return success response
    public function sendSuccess($data, $message){
        
       return response()->json([
        'status'=> 1,
        'data'=> $data,
        'message'=> $message], 200);  
    }
 
    //return error response
    public function sendError($error, $message, $code = 400){
        
       return response()->json([
        'status'=> 0,
        'data'=> $error,
        'message'=> $message], $code);  
    }
}
