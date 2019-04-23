<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment; 
use App\Post;
use App\Http\Requests\CommentRequest ;
use App\Http\Requests\PostRequest ;
use Illuminate\Support\Facades\Auth; 
use App\Policies; 


class PostsController extends Controller
{
    

 public function index()
    {


        $posts = \App\Post::orderBy('post_date','desc')->get();
      return view('All_Articles',array( 
       'posts' => $posts));


    }

 public function shows()
    {
      $user=Auth::user(); 
      return view('Mes_Articles',array( 
       'posts' => $user->posts));


    }

function comment(CommentRequest $Request, $id){
  
     $post = \App\Post::where('id',$id)->first();
         $comment= new Comment(); 
         $comment->post_id= $id;  
         $comment->comment_name= $Request->input('comment_name'); 
         $comment->comment_email= $Request->input('comment_email'); 
         $comment->comment_content= $Request->input('comment_content'); 
         $comment->save(); 
         $comments = \App\Comment::where('post_id',$Request->input('post_id'))->get(); 
       
         return view('/posts/single',array('post' => $post ));
    
        
          
    }





}
