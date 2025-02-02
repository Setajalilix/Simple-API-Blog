<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as Notifications;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notifications
{
    protected function verificationUrl($notifiable)
    {
        $appUrl = Config::get('app.client_url', Config::get('app.url'));

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['user' => $notifiable->id]
        );

        return str_replace(url('/api'), $appUrl, $url);
    }
}
