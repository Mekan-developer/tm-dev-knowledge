<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService,
    ) {}

    /**
     * Отправляет письмо с формы контакта на адрес из конфигурации.
     */
    public function send(ContactRequest $request): RedirectResponse
    {
        $this->contactService->sendContactMessage($request->validated());

        return back()->with('contact_success', true);
    }
}
