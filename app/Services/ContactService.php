<?php

namespace App\Services;

use App\Repositories\Contracts\ContactRepositoryInterface;

class ContactService
{
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
    ) {}

    /**
     * Обрабатывает отправку формы контакта.
     *
     * @param  array{name: string, email: string, subject: string, message: string, website?: string|null}  $validated
     */
    public function sendContactMessage(array $validated): void
    {
        $payload = [
            'name' => (string) $validated['name'],
            'email' => (string) $validated['email'],
            'subject' => (string) $validated['subject'],
            'message' => (string) $validated['message'],
        ];

        $this->contactRepository->sendContactMessage($payload);
    }
}
