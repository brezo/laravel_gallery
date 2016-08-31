<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Album;

class AlbumsController extends Controller
{
  public function getList()
  {
    $albums = Album::with('Photos')->get(); 
    
    //return View::make('index')->with('albums',$albums);
    return view('index', ['albums' => $albums]);
  }

  public function getAlbum($id)
  {
    $album = Album::with('Photos')->find($id);
    //return View::make('album')->with('album',$album);
    return view('album', ['album' => $album, 'albums' => Album::with('Photos')->get() ]);
  }

  public function getForm()
  {
    //return View::make('createalbum');
    return view('createalbum');
  }

  public function postCreate(Request $request)
  {
    $rules = array(
      'name' => 'required',
      'cover_image'=>'required|image'
    );
    /*
    $validator = Validator::make(Input::all(), $rules);
    if($validator->fails()){

      return Redirect::route('create_album_form')
      ->withErrors($validator)
      ->withInput();
    }*/

    $file = $request->file('cover_image');
    $random_name = str_random(8);
    $destinationPath = 'albums/';
    $extension = $file->getClientOriginalExtension();
    $filename=$random_name.'_cover.'.$extension;
    $uploadSuccess = $request->file('cover_image')
    ->move($destinationPath, $filename);
    $album = Album::create(array(
      'name' => $request->get('name'),
      'description' => $request->get('description'),
      'cover_image' => $filename,
    ));

    //return Redirect::route('show_album',array('id'=>$album->id));
    return redirect()->route('show_album',array('id'=>$album->id));    
  }

  public function getDelete($id)
  {
    $album = Album::find($id);
    $album->delete();

    //return Redirect::route('index');
    return redirect()->route('index');
  }
}
