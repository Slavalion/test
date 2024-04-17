<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NotificationChannels\Telegram\TelegramMessage;

class SendTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $chatID;

    private $message;

    /**
     * Create a new job instance.
     */
    public function __construct($chatID, $message)
    {
        $this->chatID = $chatID;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TelegramMessage::create()
            ->to($this->chatID)
            ->content($this->message)
            ->send();
    }
}
