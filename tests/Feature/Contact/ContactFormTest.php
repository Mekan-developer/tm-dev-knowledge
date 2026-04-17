<?php

namespace Tests\Feature\Contact;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_form_sends_email_and_returns_success_flag(): void
    {
        Mail::fake();
        Config::set('mail.contact_to', 'support@example.com');

        $response = $this->post(route('contact.send'), [
            'name' => 'Test User',
            'email' => 'user@example.com',
            'subject' => 'Need help',
            'message' => 'This is a valid contact form message with enough length.',
            'website' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success', true);

        Mail::assertSent(ContactFormMail::class);
    }
}
