<?php

namespace App\Http\Controllers;

use App\Services\BotService;

class BotController extends Controller
{
    protected $botService;

    /**
     * Create a new controller instance.
     *
     * @param  Api  $telegram
     */
    public function __construct(BotService $botService)
    {
        $this->botService = $botService;
    }

    public function chat()
    {
        $this->botService->handle();
    }
}
