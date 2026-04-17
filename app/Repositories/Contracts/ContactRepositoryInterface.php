<?php

namespace App\Repositories\Contracts;

interface ContactRepositoryInterface
{
    /**
     * Отправляет сообщение из контактной формы на адрес получателя.
     *
     * @param  array{name: string, email: string, subject: string, message: string}  $payload
     */
    public function sendContactMessage(array $payload): void;
}
