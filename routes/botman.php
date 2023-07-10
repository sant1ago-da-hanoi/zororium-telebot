<?php

use App\Conversations\StartConversation;

$botman = resolve('botman');

$botman->hears('/help', 'App\Http\Controllers\AnswersController@help');