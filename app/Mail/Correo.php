<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Correo extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $mensaje;
    public $firma;

    public function __construct($asunto, $mensaje, $firma)
    {
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->firma = $firma;
    }

    public function build()
    {
        return $this->view('emails.correo')
            ->subject($this->asunto);

    }
}
