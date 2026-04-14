<?php

namespace App\Jobs;

use App\Mail\SendPdfToCustomer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $fullPath = Storage::disk('public')->path($this->pdfPaths);
        $filename = basename($this->pdfPaths);

        Mail::to($this->user->email)->send(new SendPdfToCustomer(
            $this->user,
            [['path' => $fullPath, 'filename' => $filename]],
            $this->orderId
        ));
    }
}
