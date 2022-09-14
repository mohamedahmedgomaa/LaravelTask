<?php

namespace App\Repositories;

use App\Mail\ResetPasswordApi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Save User
     *
     * @param $data
     * @return User
     */
    public function save($data)
    {
        $user = new $this->user;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return $user->fresh();
    }

    /**
     * Save User
     *
     * @param $data
     * @return User
     */
    public function sendEmail($data)
    {

        $details = "If you didn't request to change the password, please ignore this email. If you would like to proceed.";
        $token = mt_rand(100000, 999999);
        DB::table('password_resets')->insert(
            [
                'email' => $data['email'],
                'token' => $token,
                'created_at' => \Carbon\Carbon::now()
            ]
        );
        Mail::to($data)->send(new ResetPasswordApi($details, $token));

        return null;

    }

    /**
     * Save User
     *
     * @param $data
     * @return null
     */
    public function editPassword($data)
    {
        $user = User::where('email', $data['email'])->first();

        $user->password = Hash::make($data['password']);
        $user->save();

        return null;
    }

    /**
     * Save User
     *
     * @param $data
     * @return null
     */
    public function confirmed($data)
    {
        DB::table('password_resets')->where('email', $data['email'])->where('token', $data['token'])->delete();

        return null;
    }

    /**
     * Save User
     *
     * @param $data
     * @return bool
     */
    public function check($data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $token = auth('api')->attempt($credentials);

        return $token;
    }

}
