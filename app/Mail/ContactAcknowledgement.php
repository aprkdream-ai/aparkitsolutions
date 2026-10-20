<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Auto-reply to the person who submitted the contact form.
 * Deliberately does not echo the free-text message, so the form can't be abused to relay spam.
 */
class ContactAcknowledgement extends Mailable
{
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        $replyTo = config('site.contact.email');

        return new Envelope(
            replyTo: $replyTo ? [new Address($replyTo, config('site.name'))] : [],
            subject: 'We have received your enquiry — '.config('site.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.acknowledgement', text: 'mail.acknowledgement-text');
    }
}
