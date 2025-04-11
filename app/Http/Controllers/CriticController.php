<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;

class CriticController extends Controller
{
   public function post(Request $request)
   {
       try {
              $critic = new Critic();
              $critic->user_id = $request->user()->id; 
              $critic->film_id = $request->film_id;
              $critic->score = $request->score;
              $critic->comment = $request->comment;
              $critic->save();
    
              return response()->json(['success' => true, 'message' => 'Critic submitted successfully.']);
       } catch (\Exception $e) {
           return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        
       }
   }
}
