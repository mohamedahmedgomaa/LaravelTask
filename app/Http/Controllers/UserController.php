<?php

namespace App\Http\Controllers;

use App\Mail\customerResetPassword;
use App\Mail\ResetPasswordApi;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * @var userService
     */
    protected $userService;

    /**
     * UserController Constructor
     *
     * @param UserService $userService
     *
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'redirectUnauthorized'
            ,'confirmedToken','updatePassword', 'sendEmailReset']]);
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->only([
            'email',
            'password',
        ]);

        try {

            $result = $this->userService->checkUserData($data);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * Register a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        try {
            $result = $this->userService->saveUserData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendEmailReset(Request $request): JsonResponse
    {
        $data = $request->only([
            'email',
        ]);

        try {
            $result = $this->userService->userSendEmailReset($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmedToken(Request $request): JsonResponse
    {
        $data = $request->only([
            'email',
            'token',
        ]);

        try {
            $result = $this->userService->userConfirmedToken($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->only([
            'email',
            'password',
            'password_confirmation',
        ]);

        try {
            $result = $this->userService->userUpdatePassword($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }

        return responseJson($result['status'], $result['message'], $result['data'] ?? null);

    }

    /**
     * @return JsonResponse
     */
    public function redirectUnauthorized(): JsonResponse
    {
        return responseJson(401, 'Unauthorized');
    }
}
