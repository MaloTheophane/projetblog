<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment; 
use App\Post;
use App\User;
use App\Http\Requests\CommentRequest ;
use App\Http\Requests\PostRequest ;

class CommentController extends Controller
{



  public function edit($id)
    {
       $comment = \App\Comment::where('id',$id)->first();

       return view('/comment/edit', array('comment' => $comment));
    }




     public function update(CommentRequest $Request, $id)
    {
          
         $comment = \App\Comment::where('id',$id)->first(); 
         $comment->comment_name= $Request->input('comment_name'); 
         $comment->comment_email= $Request->input('comment_email'); 
         $comment->comment_content= $Request->input('comment_content'); 
         $comment->save(); 
        // $comments = \App\Comment::where('post_id',$Request->input('post_id'))->get(); 
         $post = \App\Post::where('id',$comment->post_id)->first();
      
           return view('/posts/single', array('post' => $post)); 

    }

    public function autoriser($id)
    {


       $comment = \App\Comment::where('id',$id)->first();
        $comment->comment_statut="";
         $comment->save(); 
     return redirect()->back();
    }
     


     public function bloquer($id)
    {
       $comment = \App\Comment::where('id',$id)->first();
       $comment->comment_statut="display:none";
         $comment->save(); 
       return redirect()->back();
    }

}
