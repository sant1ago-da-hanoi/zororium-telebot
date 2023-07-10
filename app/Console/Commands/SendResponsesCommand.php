<?php

namespace App\Console\Commands;

use App\Http\Controllers\BotController;
use Illuminate\Http\Request;
use Illuminate\Console\Command;

class SendResponsesCommand extends Command
{
    protected $signature = 'app:send-responses';

    public function handle()
    {
        $botController = app()->make('App\Http\Controllers\BotController');
        app()->call([$botController, 'chat']);

        return 0;
    }
}
