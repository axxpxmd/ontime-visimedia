<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Storage;
use Image;
class ProfileController extends Controller
{
    public function updateProfil(Request $request, User $user)
    {
        $data = $request->validate([
            'nama'  => ['required', 'max:32', 'string'],

            'foto'  => ['image', 'mimes:jpeg,png,gif', 'max:2048']
        ]);
        $data['nama']       = $request->nama;

        if ($request->file('foto')) {
            if ($user->foto != 'default.jpg') {
               if( Storage::disk('sftp')->exists($user->foto)){
                Storage::disk('sftp')->delete($user->foto);

               }
            }


            $name = time().".".$request->file('foto')->getClientOriginalExtension();
            $img = Image::make($request->file('foto'))->resize(500, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();
            $upload_path =  'foto-profil/';
            $image_url = $upload_path.$name;
            // $img->save($image_url);
            Storage::disk('sftp')->put($image_url, $img);

            $data['foto'] = $image_url;
        }
        $user->update($data);
        // $user->save();
        return redirect()->back()->with('success','Profil berhasil di perbarui');
    }
}
