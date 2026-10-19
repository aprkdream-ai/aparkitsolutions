<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactSubmitted extends Mailable
{
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->data['email'], $this->data['name'])],
            subject: 'New enquiry from '.$this->data['name'].' — '.config('site.name'),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.contact', text: 'mail.contact-text');
    }
}
