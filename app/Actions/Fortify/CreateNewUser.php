<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /*
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {

        $validate = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required_without:phone',
                'nullable',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users',
            ],
            
            'phone' => ['required_without:email', 'nullable', 'digits:11', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:11', 'string', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        //dd($input);
        // return User::create($validate);

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
