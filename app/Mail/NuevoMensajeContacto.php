<?php

namespace App\Mail;

use App\Models\Contacto\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Aviso al estudio de un mensaje nuevo; "Responder" contesta directamente a quien escribió. */
class NuevoMensajeContacto extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $mensaje) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->mensaje->email, $this->mensaje->name)],
            subject: 'Nuevo mensaje web: '.($this->mensaje->subject ?: $this->mensaje->name),
        );
    }

    public function content(): Content
    {
        return new Content(text: 'emails.contacto-nuevo');
    }
}
