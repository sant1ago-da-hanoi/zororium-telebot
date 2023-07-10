<?php

namespace App\Services;

use App\Repositories\BotRepository;
use App\Repositories\Response\ResponseRepository;
use Illuminate\Support\Facades\Log;

class BotService
{
    protected $botRepository;
    protected $responseRepository;

    public function __construct(BotRepository $botRepository, ResponseRepository $responseRepository)
    {
        $this->botRepository        = $botRepository;
        $this->responseRepository   = $responseRepository;
    }

    public function handle()
    {
        $offset = 0;
        $allResponsesSent = false;
        while (!$allResponsesSent) {
            $options = [
                'offset' => $offset,
                'limit' => 10,
            ];

            $updates = array_reverse($this->botRepository->pullResponses($options));
            if (empty($updates)) {
                $allResponsesSent = true;
                break;
            }

            foreach ($updates as $update) {
                $responseExisted = $this->responseRepository->findWhere(['update_id' => $update->update_id])->count() > 0;

                // If response existed, delete all responses from the last update_id and break the loop
                if ($responseExisted) {
                    $offset = $update->update_id + 1;
                    foreach ($updates as $update) {
                        $this->responseRepository->deleteByUpdateId($update->update_id);
                    }
                    break;
                }

                // If response not existed, create new response and save to database
                $this->responseRepository->create([
                    'update_id'             => $update->update_id,
                    'message_id'            => $update->message->message_id,
                    'from_id'               => $update->message->from->id,
                    'from_is_bot'           => $update->message->from->is_bot,
                    'from_username'         => $update->message->from->username,
                    'from_language_code'    => $update->message->from->language_code,
                    'chat_id'               => $update->message->chat->id,
                    'chat_username'         => $update->message->chat->username,
                    'chat_type'             => $update->message->chat->type,
                    'date'                  => $update->message->date,
                    'text'                  => $update->message->text,
                    'is_command'            => !isset($update->message->entities) ? false : ($update->message->entities[0]->type === 'bot_command' ? true : false),
                ]);

                // Send response to user
                $messageSent = $this->botRepository->commandFilter(
                    $update->message->chat->id,
                    $update->message->from->language_code,
                    $update->message->text
                );

                if ($messageSent) {
                    $this->responseRepository->deleteByUpdateId($update->update_id);
                    $offset = $update->update_id + 1;
                    Log::info("Response sent to chat id: {$update->message->chat->id}");
                }
            }
        }
        return "All responses sent!";
    }
}
