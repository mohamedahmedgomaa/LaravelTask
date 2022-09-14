<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UserService
{
    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Validate user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return User|array
     */
    public function saveUserData($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email|max:199',
            'password' => 'required|min:6|max:199',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        $result = $this->userRepository->save($data);


        return [
            'status' => 200,
            'message' => 'success',
            'data' => new UserResource($result),
        ];
    }

    /**
     * Validate user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return array|JsonResponse
     */
    public function checkUserData(array $data): JsonResponse|array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:199',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        $token = $this->userRepository->check($data);

        if (!$token) {
            return [
                'status' => 401,
                'message' => 'Unauthorized',
            ];
        }

        return [
            'status' => 200,
            'message' => 'success',
            'data' => $token,
        ];
    }


    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return responseJson(200, 'Successfully logged out', null);
    }


    /**
     * Validate user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return User|array
     */
    public function userSendEmailReset(array $data): User|array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        $this->userRepository->sendEmail($data);


        return [
            'status' => 200,
            'message' => 'We have e-mailed your password reset link!',
            'data' => null,
        ];
    }


    /**
     * Validate user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return array
     */
    public function userUpdatePassword(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }

        $this->userRepository->editPassword($data);


        return [
            'status' => 200,
            'message' => 'Password Has Updated Successfully',
            'data' => null,
        ];
    }

    /**
     * Validate user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return array
     */
    public function userConfirmedToken(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 400,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors(),
            ];
        }
        $passwordResets = DB::table('password_resets')->where('email', $data['email'])->where('token', $data['token'])->first();
        if ($passwordResets == NULL) {
            return [
                'status' => 416,
                'message' => 'Token Not Confirmed',
                'data' => null,
            ];
        } else {
            $this->userRepository->confirmed($data);

            return [
                'status' => 200,
                'message' => 'Token Confirmed',
                'data' => null,
            ];
        }

    }

}
