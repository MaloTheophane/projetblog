<?php

namespace App\Http\Controllers;
use App\User;
use App\Post;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    { 
      if(!is_null(Auth::user()) && Auth::user()->can('estAdmin', User::class))
      {
        $users = \App\User::orderBy('id','desc')->get();
      
       return view('User',array( 
       'users' => $users ));
       }else {

        return view('Message')->with('Ok'," Désolé , cette partie est réservé seulement au administrateur 
             de l'administrateur");
       }


    }
public function faireadministrer($id){
     
         $user = \App\User::where('id',$id)->first();
        $user->role="admin"; 
        $user->save(); 
       // echo $user; 
        //die('ici')
        return redirect()->back();
   


    }
    public function delete($id)
    {

       $user = \App\User::where('id',$id)->first();
       $posts=\App\Post::where('post_author',$id)->get();
       foreach ($posts as $post ) {
         $post->destroy($post->id); 
       }
        $user->destroy($id);

		return redirect()->back();
         

    }
}
