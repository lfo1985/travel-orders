<?php

namespace App\Services\Order;

use App\Mail\UpdateStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class SendEmailUpdateStatusService 
{
    public function sendEmail(
        Order $order,
        string $oldStatus,
        string $newStatus
    ): bool {
        Mail::to($order->user->email)->send(new UpdateStatusMail(
            $order,
            $oldStatus,
            $newStatus
        ));
        return true;
    }
}