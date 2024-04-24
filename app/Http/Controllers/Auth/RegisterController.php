<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

     # This function handles user registration and email confirmation.
    protected function create(array $data)
    #  Method for user creation during registration.
    {
        # Creates a new user record.
        $user = User::create([
            # Sets user's name from provided data.
            'name' => $data['name'],

            # Sets user's email from provided data.
            'email' => $data['email'],

            # Sets hashed password from provided data.
            'password' => Hash::make($data['password'])
        ]);

        # Initializes an array named details.
        $details = [
            # Assigns user's name to 'name' key.
            'name' => $user->name,

            # Assigns application URL from configuration.
            'app_url' => config('app.url')
        ];


        # Sends a registration confirmation email.
        Mail::send('users.emails.register-confirmation', $details, function($message) use($user) {
            $message->from(config('mail.from.address'), config('app.name'))
                    ->to($user->email, $user->name)
                    ->subject('Thank you for registering to Kredo IG App!');
        });

        # Mail::send(...) -> Sends an email using Laravel's Mail service.

        # ('users.emails.register-confirmation', $details, function($message) use($user) { -> Specifies email template, data, and callback function.

        # $message->from(...) -> Sets sender details for the email.

        # ->to($user->email, $user->name) -> Sets recipient email and name.


        # ->subject('Thank you for registering to Kredo IG App!') -> Sets email subject.

        return $user;
        # Returns the created user instance.
    }
}
