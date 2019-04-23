<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment; 
use App\Post;
use App\User;
use App\Http\Requests\CommentRequest ;
use App\Http\Requests\PostRequest ;
use Illuminate\Support\Facades\Auth; 
use App\Policies; 


class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




    public function index()
    {
          if(!is_null(Auth::user()) &&Auth::user()->can('estAdmin', User::class))
      {

        $posts = \App\Post::orderBy('post_date','desc')->get();
    	return view('Articles',array( 
       'posts' => $posts));
   }else{


    return view('Message')->with('Ok'," Désolé , cette partie est réservé seulement au administrateur 
             de l'administrateur");
   }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
      
     $this->authorize('create',new Post());
       return view ('article/create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    { 
         $post= new Post();  

         $this->authorize('restore', $post);
         $post->post_author= Auth::user()->id; 
         $post->post_content= $request->input('post_content'); 
         $post->post_title= $request->input('post_title'); 
         $post->post_type= $request->input('post_type'); 
         $post->post_name= $request->input('post_name'); 


   
 
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            $image = $request->file('image'); 
            $chemin = config('images.path');
            $extension = $image->getClientOriginalExtension();

            do {
                $nom = $request->input('post_name').str_random(10) . '.' . $extension;
            } while(file_exists($chemin . '/' . $nom));

            if($image->move($chemin, $nom)) {
                $post->post_image ="http://127.0.0.1:8000/".$chemin . '/' . $nom; 
            }
            }
        

         $post->save(); 
   return view('/posts/single',array( 
       'post' => $post));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     
         $post = \App\Post::where('id',$id)->first();
       
   return view('/posts/single',array('post' => $post ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $post = \App\Post::where('id',$id)->first();
       return view('article/edit',array('post' => $post)); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $Request, $id)
    {
    	//die($Request); 
        $post = \App\Post::where('id',$id)->first(); 
        $user = Auth::user();
        $this->authorize('update', $post);
         
        $post->post_author=$user->id; 
         $post->post_content= $Request->input('post_content'); 
         $post->post_title= $Request->input('post_title'); 
         $post->post_type= $Request->input('post_type'); 
         $post->post_name= $Request->input('post_name'); 
         
         


        if($Request->hasFile('image') && $Request->file('image')->isValid())
        {
            $image = $Request->file('image');
            $chemin = config('images.path');
            $extension = $image->getClientOriginalExtension();

            do {
                $nom = $Request->input('post_name').str_random(10) . '.' . $extension;
            } while(file_exists($chemin . '/' . $nom));

            if($image->move($chemin, $nom)) {
                $post->post_image ="http://127.0.0.1:8000/".$chemin . '/' . $nom; 
            }
        }

         $post->save();
        /*
        $post->update(['post_name' =>$request->input('post_name') , 'post_title' => $request->input('post_title'),'post_content' => $request->input('post_content') , 'post_type' => $request->input('post_type'),'post_author' => $request->input('post_author')]);*/
       // return redirect("articles/$id")->with("Ok","Le post " . $Request->input('post_name') . " a été modifié avec succès.");
          return view('/posts/single', array('post' => $post))->with("Ok","Le post " . $Request->input('post_name') . " a été modifié avec succès.");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    	 $post= new Post(); 
        $post->destroy($id);

		return redirect()->back();
    }
}
