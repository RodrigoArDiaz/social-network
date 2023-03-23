<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private $disk = 'public';

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }



    /**
     * Update the user's profile image
     */
    public function update_profile_image(Request $request) {
        //Recupero usuario autenticado
        $user = auth()->user();

        //Recuperacion de  la imagen
        $image_url = $request->image_url;
        $image = $request->file('image');

        //Verifica si se envio una url o un archivo
        if (isset($image_url)) {
            //Update profile image
            $user->profile_image = $request->image_url;

            //Update data user on db
            $user->save();

             //Response
            return response()->json([
                'url_received' => $request->image_url,
                'user' => $user,
                'message' => 'Profile image update succesfull!'
            ],200);
        }


        //Verifica si se envio el archivo de imagen
        if (!isset($image)) {
            return response()->json([
                'message' => 'You have to select an image.',
            ],500);
        }

        //Verificacion del tipo de archivo
        if (strpos($image->getMimeType(), 'image') ===  false) {
            return response()->json([
                'message' => ' File type not allowed. Only images are allowed (png, jpg, ico)',
                'file_type_send' => $image->getMimeType()
            ],500);
        }

        //Se borro la imagen de perfil anterior
        $array = explode('/',$user->profile_image);
        $name_old = $array[array_key_last($array)];
        Storage::disk($this->disk)->delete($name_old); //Borrado

        //Se guarda la imagen
        $name = str_replace(' ','_',$user->name ).'_'.$user->id.'_profile_image';
        $name_with_extension = $image->storeAs('',$name.".".$image->extension(),$this->disk);
        //Se obiene el path de la imagen
        $path_image =asset(Storage::disk($this->disk)->url($name_with_extension));

        //Update data user on db
        $user->profile_image = $path_image;
        $user->save();

        //Response
        return response()->json([
            'url_image' => $path_image,
            'user' => $user,
            'message' => 'Profile image update succesfull!'
        ],200);
    }
}