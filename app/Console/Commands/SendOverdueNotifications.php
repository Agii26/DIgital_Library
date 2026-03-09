<?php

namespace App\Console\Commands;

use App\Models\PhysicalBorrow;
use App\Notifications\OverdueNotification;
use Illuminate\Console\Command;

class SendOverdueNotifications extends Command
{
    protected $signature   = 'library:send-overdue-notifications';
    protected $description = 'Send overdue notifications to borrowers';

    public function handle(): void
    {
        $overdueBorrows = PhysicalBorrow::where('status', 'claimed')
            ->where('due_date', '<', now())
            ->with(['user', 'book'])
            ->get();

        foreach ($overdueBorrows as $borrow) {
            $borrow->user->notify(new OverdueNotification($borrow));
            $this->info('Notified: ' . $borrow->user->name . ' for book: ' . $borrow->book->title);
        }

        $this->info('Total notifications sent: ' . $overdueBorrows->count());
    }
}