<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Links;
use App\Models\user_links;
use App\Models\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;




class LinkController extends Controller
{
    /**
     * Store a newly created link in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['userLogin', 'userRegister']]);
    }

     protected function guard()
     {
         return Auth::guard('api');
     }


     // add link by user
     public function store(Request $request)
     {
        // Restrict the method to authenticated users only
         $this->middleware('auth');
     
         $user = Auth::user();
      
         $validator = Validator::make($request->all(), [
             'title' => 'required|string',
             'link' => 'required|url',
             'order' => 'required|integer|min:0',
             'icon' => 'nullable|string',
             'icon_image' => 'nullable|image',
             'background_color' => 'nullable|string',
             'text_color' => 'nullable|string',
             'is_active' => 'required|boolean',
         ]);
     

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }
      // Restrict the method to authenticated users only
      $this->middleware('auth');
     
      $user = Auth::user();
  
    $link = new Links();
    $link->title = $request->input('title');
    $link->link = $request->input('link');
    $link->order = $request->input('order');
    $link->icon = $request->input('icon');
    $link->background_color = $request->input('background_color');
    $link->text_color = $request->input('text_color');
    $link->is_active = $request->input('is_active');


/** */
/*
if (Auth::check()) {
    $userLink = new user_links([
        'user_id' => Auth::user()->id,
        'link_id' => $link->id,
    ]);
    $userLink->save();
}
/** */
    if ($request->hasFile('icon_image')) {
        $file = $request->file('icon_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images', $filename);
        $link->icon_image = $filename;
    }

    $link->save();


    user_links::create([
        'user_id' => Auth::user()->id,
         'link_id' => $link->id,
      ]);


    return response()->json([
        'message' => 'Link added successfully',
        'link' => $link,
        'client' => $user
    ], 201);
}

public function update(Request $request)
{
    // Restrict the method to authenticated users only
    $this->middleware('auth');
 
    $user = Auth::user();
 
    $validator = Validator::make($request->all(), [
        'id' => 'required|integer',
        'title' => 'nullable|string',
        'link' => 'nullable|url',
        'order' => 'nullable|integer|min:0',
        'icon' => 'nullable|string',
        'icon_image' => 'nullable|image',
        'background_color' => 'nullable|string',
        'text_color' => 'nullable|string',
        'is_active' => 'nullable|boolean',
    ]);
 
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }
 
    $link = Links::find($request->input('id'));
 
    if (!$link) {
        return response()->json([
            'message' => 'Link not found',
        ], 404);
    }
 
    if ($request->has('title')) {
        $link->title = $request->input('title');
    }
 
    if ($request->has('link')) {
        $link->link = $request->input('link');
    }
 
    if ($request->has('order')) {
        $link->order = $request->input('order');
    }
 
    if ($request->has('icon')) {
        $link->icon = $request->input('icon');
    }
 
    if ($request->has('background_color')) {
        $link->background_color = $request->input('background_color');
    }
 
    if ($request->has('text_color')) {
        $link->text_color = $request->input('text_color');
    }
 
    if ($request->has('is_active')) {
        $link->is_active = $request->input('is_active');
    }
 
    $link->save();
 
    return response()->json([
        'message' => 'Link updated successfully',
        'link' => $link,
    ]);
}

    
//get link by ID
public function show(Request $request)
{
    $user = Auth::user(); // Get the authenticated user
    $validatedData = $request->validate([
        'link_id' => 'required'
    ]);
   $link = Links::find($request->input('link_id'));

   $userLink = user_links::where('link_id', $validatedData['link_id'])->with('user')->first();

    if (!$link) {
        return response()->json([
            'message' => 'Link not found'
        ], 404);
    }

    return response()->json([
        'link' => $link ,
      //  'user' => $user,
        'user that add the link'=> $userLink,
     //   'user' => $useradd,


    ], 200);
}

public function show_user_link(Request $request)
{
    $user = Auth::user(); // Get the authenticated user
    $validatedData = $request->validate([
        'link_id' => 'required'
    ]);
   $link = Links::find($request->input('link_id'));

   $userLink = user_links::where('link_id', $validatedData['link_id'])->with('user')->first();

    if (!$link) {
        return response()->json([
            'message' => 'Link not found'
        ], 404);
    }

    return response()->json([
        'link' => $link ,
      //  'user' => $user,
        'user that add the link'=> $userLink,
     //   'user' => $useradd,


    ], 200);
}


//get all links

public function show_all(Request $request)
{
    $user = Auth::user();
    $link = Links::all();
    

    return response()->json([
        'links' => $link,
    ]);

}

//get links by authenticated user ID
public function show_link(Request $request)
{
    $user = Auth::user(); // Get the authenticated user

    $links = $user->links()->get(); // Get all the links entered by the authenticated user

    if (!$links) {
        return response()->json([
            'message' => 'Links not found'
        ], 404);
    }

    return response()->json([
        'links' => $links,
        'user' => $user
    ], 200);
}



//Delete link
public function delete(Request $request)
{
    $validator = Validator::make($request->all(), [
        'link_id' => 'required|integer|exists:links,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    $link = Links::findOrFail($request->input('link_id'));
    $link->delete();

    return response()->json([
        'message' => 'Link successfully deleted',
    ], 200);
}



}