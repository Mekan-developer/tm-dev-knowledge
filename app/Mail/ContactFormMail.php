<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name:string,email:string,subject:string,message:string}  $payload
     */
    public function __construct(
        public array $payload,
    ) {}

    /**
     * Тема и reply-to для быстрого ответа прямо из Gmail.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('[DevKnowledge] %s — %s tarapyndan', $this->payload['subject'], $this->payload['name']),
            replyTo: [
                new Address($this->payload['email'], $this->payload['name']),
            ],
        );
    }

    /**
     * Шаблон письма с данными формы.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form',
            with: [
                'payload' => $this->payload,
                'sentAt' => now()->toDateTimeString(),
            ],
        );
    }
}
