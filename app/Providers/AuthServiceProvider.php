<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Notifications\Messages\MailMessage;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
    $this->registerPolicies();
    VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $parsedUrl = parse_url($url);
            parse_str($parsedUrl['query'] ?? '', $queryParams);

            $id        = $notifiable->getKey();
            $hash      = sha1($notifiable->getEmailForVerification());
            $expires   = $queryParams['expires'] ?? null;
            $signature = $queryParams['signature'] ?? null;

            // Build a frontend URL (React app)
            $frontendUrl = config('app.frontend_url') . '/signup/email-verification/verify?' . http_build_query([
                'id'        => $id,
                'hash'      => $hash,
                'expires'   => $expires,
                'signature' => $signature,
            ]);

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $frontendUrl);
        });
    }

    }

    
        
    


