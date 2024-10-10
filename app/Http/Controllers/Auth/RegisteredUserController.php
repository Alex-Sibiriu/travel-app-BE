<?php

namespace App\Http\Controllers\Auth;

use App\Functions\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $messages = [
            'name.required' => 'Il nome è obbligatorio.',
            'name.string' => 'Il nome deve essere una stringa valida.',
            'name.max' => 'Il nome non può essere più lungo di :max caratteri.',

            'email.required' => 'L\'email è obbligatoria.',
            'email.string' => 'L\'email deve essere una stringa valida.',
            'email.lowercase' => 'L\'email deve essere in lettere minuscole.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.max' => 'L\'email non può essere più lunga di :max caratteri.',
            'email.unique' => 'L\'email fornita è già stata utilizzata.',

            'password.required' => 'La password è obbligatoria.',
            'password.confirmed' => 'Le password non coincidono.',
            'password.min' => 'La password deve essere lunga almeno :min caratteri.',
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'slug' => Helper::generateSlug($request->name, User::class),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        event(new Registered($user));

        Auth::login($user);


        return response()->json(['user' => $user, 'token' => $token]);
    }
}
