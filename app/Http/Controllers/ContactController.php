<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Отправляет письмо с формы контакта на адрес из конфигурации.
     */
    public function send(ContactRequest $request): RedirectResponse
    {
        if ($request->filled('website')) {
            return back();
        }

        Mail::to(config('mail.contact_to'))
            ->send(new ContactFormMail($request->validated()));

        return back()->with('contact_success', true);
    }
}
