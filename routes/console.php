<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('library:send-overdue-notifications')->dailyAt('08:00');