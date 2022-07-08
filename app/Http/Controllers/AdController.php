<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    public function index (Request $request) {
        $query = Ad::all();
        // bouton search utilisé
        if($request->has('my_ads_btn') || $request->has('author_id')) {
            if(!empty($request->author_id)) {
                $data = Ad::where('author_id', '=', $request->author_id);
            } else {
                $query = Ad::paginate(3);    
                $query->appends($request->all());
                return view('ads.index', ['ads' => $query]);
            }
                        $query = $data->paginate(3);    
                        $query->appends($request->all());
                        return view('ads.index', ['ads' => $query]);        
        }

        if(isset($request->search) && !empty($request->search)) {
            $data = Ad::where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%')
                        ->orWhere('location', 'like', '%' . $request->search . '%');
                        $query = $data->paginate(3);
        } elseif ($request->has('filter_btn') || $request->has('filter_category') || $request->has('filter_price') || $request->has('filter_location')) {
            $data = Ad::where('title', 'like', '%%');
            if($request->has('filter_category') && !empty($request->filter_category)) {
                $data->where('category', '=', $request->filter_category);
            }
            if($request->has('filter_price') && !empty($request->filter_price)) {
                $data->where('price', '<=', $request->filter_price);
            }
            if($request->has('filter_location') && !empty($request->filter_location)) {
                $data->where('location', 'like', '%' . $request->filter_location . '%');
            }
            if($request->has('filter_author_id') && !empty($request->filter_author_id)) {
                $data->where('author_id', '=', $request->filter_author_id);
            }
            
            // préparation du passage d'argument pour la closure
            $filter_search = $request->filter_search;
            $data->where(function($q) use($filter_search) {
                            $q->where('title', 'like', '%' . $filter_search . '%')
                            ->orWhere('description', 'like', '%' . $filter_search . '%')
                            ->orWhere('location', 'like', '%' . $filter_search . '%');
                        });
            $query = $data->paginate(3);
        } else {
            // sinon chargement de la page utilisé
            $query = Ad::paginate(3);
        }
        
        $query->appends($request->all());
        return view('ads.index', ['ads' => $query]);
    }

    public function show($id) {
        $ad = Ad::findOrFail($id);
        return view('ads.show', ['ad' => $ad]);
    }

    public function create() {
        return view('ads.create');
    }

    public function store(Request $request) {
        $ad = new Ad();
        $ad->title = $request->title;
        $ad->description = $request->description;
        $ad->category = $request->category;
        $ad->location = $request->location;
        $ad->price = $request->price;
        $ad->author_id = $request->author_id;

        // gestion de l'upload des images d'une même annonce
        $allowedfileExtension=['jpeg','jpg','png'];
        $files = $request->file('pictures');
        $maxsize = 2000000;
        $size = 0;
        $output = "";
        $validImages = [];

        try {
            $files = $request->file('pictures');
            foreach ($files as $key => $value) {
                $size += $value->getSize();
            }
            if($size == false || $size > $maxsize) {
                return redirect()->route('ads.create')
                ->with('picture_error', 'File(s) too large');
            }
        }
        catch (Exception $e) {
            return redirect()->route('ads.create')
            ->with('picture_error', 'File(s) too large');
        }

        foreach($files as $file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            // $size += $file->getSize();
            if($check) {
                array_push($validImages,$file);
            } else {
                $validImages = [];
                return redirect()->route('ads.create')
                                ->with('picture_error', 'Format not supported');
            }
        }

        foreach($validImages as $file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
                $imageName = uniqid().'.'.$extension;
                $output .= ($imageName . ",");
                $file->move(public_path('img'), $imageName);
        }

        $ad->picture = (substr($output, 0, -1));


        $ad->save();

        return redirect()->route('ads.index');
    }

    public function update(Request $request) {
        $ad = Ad::findOrFail($request->id);
        $ad->title = $request->title;
        $ad->description = $request->description;
        $ad->category = $request->category;
        $ad->location = $request->location;
        $ad->price = $request->price;
        $ad->author_id = $request->author_id;
        
        if(isset($request->pictures) && !empty($request->pictures)) {
            
            // gestion de l'upload des images d'une même annonce
            $allowedfileExtension=['jpeg','jpg','png'];
            $files = $request->file('pictures');
            $maxsize = 2000000;
            $size = 0;
            $output = "";
            $validImages = [];

            try {
                $files = $request->file('pictures');
                foreach ($files as $key => $value) {
                    $size += $value->getSize();
                }
                if($size == false || $size > $maxsize) {
                    return redirect()->route('ads.update', $ad->id)
                    ->with('picture_error', 'File(s) too large');
                }
            }
            catch (Exception $e) {
                return redirect()->route('ads.update', $ad->id)
                ->with('picture_error', 'File(s) too large');
            }
    
            foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check=in_array($extension,$allowedfileExtension);
                // $size += $file->getSize();
                if($check) {
                    array_push($validImages,$file);
                } else {
                    $validImages = [];
                    return redirect()->route('ads.update', $ad->id)
                                    ->with('picture_error', 'Format not supported');
                }
            }


        // protection en cas d'utilisation de Postman ou autre
        if($ad->author_id == Auth::user()->id) {            
            // suppression des images existantes sur le serveur
            // après validation des nouvelles images
            $images = explode(',', $ad->picture);

            foreach ($images as $key => $imageName) {
                unlink(public_path('img').'/'.$imageName);
            }

            foreach($validImages as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                    $imageName = uniqid().'.'.$extension;
                    $output .= ($imageName . ",");
                    $file->move(public_path('img'), $imageName);
            }

            $ad->picture = (substr($output, 0, -1));
        }      

            $ad->save();
        }

        return redirect()->route('ads.index')->with('success', 'Your ad has been updated');
    }

    public function destroy($id) {
        $ad = Ad::findOrFail($id);
        
        // protection en cas d'utilisation de Postman ou autre
        if($ad->author_id == Auth::user()->id) {
            if(!empty($ad->picture)) {
                $images=explode(',', $ad->picture);

                foreach ($images as $key => $imageName) {
                unlink(public_path('img').'/'.$imageName);
                }
            }
            
            $ad->delete();
        }

        return redirect()->route('ads.index')->with('success', 'Your ad has been deleted');
    }
}
