<?php

namespace App\Repositories;

use App\Mail\ContactFormMail;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * @param  array{name: string, email: string, subject: string, message: string}  $payload
     */
    public function sendContactMessage(array $payload): void
    {
        Mail::to(config('mail.contact_to'))->send(new ContactFormMail($payload));
    }
}
