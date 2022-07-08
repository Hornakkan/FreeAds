<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ad;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id) {
        $user = User::findOrFail($id);
        return view('users.show', ['user' => $user]);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $ads = Ad::where('author_id', $id)->delete();

        // protection en cas d'utilisation de Postman ou autre
        if($id == Auth::user()->id) {
            $user->delete();
        }

        return redirect()->route('ads.index');
    }

    public function update(Request $request) {
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        if($request->password != $user->password && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->phone_number = $request->phone_number;
        $user->updated_at = now();
        // protection en cas d'utilisation de Postman ou autre
        if($request->id == Auth::user()->id) {
            $user->save();
        }

        return redirect()->route('ads.index');
    }
}
