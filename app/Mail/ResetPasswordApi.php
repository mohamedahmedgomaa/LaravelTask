<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordApi extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;
    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $token)
    {
        $this->details = $details;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset')->view('auth.reset_password_api')->with([
            'token' => $this->token,
            'details' => $this->details
        ]);
    }
}
