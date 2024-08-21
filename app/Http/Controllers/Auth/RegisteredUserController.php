<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_image' => ['required','mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        $image = $request->file('profile_image'); // Ensure you get the uploaded file
        // Get the original file name with extension
        $imageName = $image->getClientOriginalName();
        // Store the image in the 'public' disk, which maps to 'storage/app/public' directory
        $image_uploaded_path = $image->storeAs('users', $imageName, 'public');
        // complete URL including the base URL
        $image_url = url(Storage::url($image_uploaded_path));

        $uploadedImageResponse = array(
            "name" => basename($image_uploaded_path),
            "url" => $image_url,
            "type" => $image->getMimeType()
        );



        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'image' => $uploadedImageResponse['url'],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => isset($request->user_role) ? $request->user_role : 'NA',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
