<?php

namespace Modules\Acl\Listeners;

use Mail;
use Modules\Acl\Events\onboardingEmail;

class SendMailOnboarding
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(onboardingEmail $event): void
    {
      
        Mail::send('acl::dropshipper.email.onboarding',['data' =>$event], function($message) use ($event) {
            $message->to($event->email);
            $message->subject('أهلا بك فى  Olldrop');
        });
    }
}
