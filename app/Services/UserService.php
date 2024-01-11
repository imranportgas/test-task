<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validateUser(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::default()
            ]
        ], [
            'required' => ':attribute - Обятельное поле',
            'string' => ':attribute - Должно быть строкой',
            'email' => ':attribute - Должен быть email',
            'confirmed' => ':attribute - Должно быть подтверждение пароля',
            'unique' => ':attribute - Должен быть уникальным'
        ]);
    }

    public function createUser(array $data)
    {
        $validator = $this->validateUser($data);

        if ($validator->fails()) {
            return [
                'message' => 'Данные заполнены некорректно',
                $validator->errors()->toArray()
            ];
        }

        $user = User::create($data);
        $token = $user->createToken($user->name)->plainTextToken;

        return ['user' => $user, 'token' => $token];

    }

    public function loginUser(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'message' => 'Данные заполнены некорретно',
                $validator->errors()->toArray()
            ];
        }

        if (!Auth::attempt($data)) {
            return 'Email или пароль неверные';
        }

        /** @var User $user */

        $user = Auth::user();
        $token = $user->createToken($user->name)->plainTextToken;

        return ['user' => $user, 'token' => $token];

    }
}
