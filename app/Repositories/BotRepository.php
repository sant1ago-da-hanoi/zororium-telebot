<?php

namespace App\Repositories;

use Telegram\Bot\Api;

class BotRepository
{
    protected $telegram;
    protected $response;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function pullResponses($params = [])
    {
        return $this->telegram->getUpdates($params);
    }

    public function commandFilter($chatId, $language, $text): bool
    {
        $command        = strtolower($text);
        $messages       = config('bot_messages.' . $language);
        $errorMessage   = $command[0] === '/' && substr_count($command, '/') ? null : $messages['error'];

        if (!is_null($errorMessage)) {
            $this->sendMessage($chatId, $errorMessage);
            return false;
        }

        $messageChooser = str_replace('/', '', $command);
        $message        = $messages[$messageChooser] ?? $messages['error'];
        $this->sendMessage($chatId, $message);
        return true;
    }

    public function sendMessage(int $chatId, string $text)
    {
        $this->telegram->sendMessage([
            'chat_id'   => $chatId,
            'text'      => $text,
        ]);
        return 0;
    }

    public function createKeyboard(array $keyboard, bool $resizeKeyboard = true, bool $oneTimeKeyboard = true, bool $selective = false)
    {
        return $this->telegram->replyKeyboardMarkup([
            'keyboard'          => $keyboard,
            'resize_keyboard'   => $resizeKeyboard,
            'one_time_keyboard' => $oneTimeKeyboard,
            'selective'         => $selective,
        ]);
    }
}
