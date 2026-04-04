<?php

namespace App\Jobs;

use App\Mail\SendPdfToCustomer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPdfEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    protected $user;
    protected $pdfPaths;
    protected $orderId;

    public function __construct($user, $pdfPaths, $orderId)
    {
        $this->user = $user;
        $this->pdfPaths = $pdfPaths;
        $this->orderId = $orderId;
    }

    public function handle(): void
    {
        Mail::to($this->user->email)->send(new SendPdfToCustomer(
            $this->user,
            $this->pdfPaths,
            $this->orderId
        ));
    }
}
