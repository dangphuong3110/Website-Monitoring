<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class TelegramController extends Controller
{
    public function index()
    {
        echo "Telegram by Chungdz";
    }

    public function webhook()
    {
        $update = json_decode(file_get_contents("php://input"));
        $logPath = storage_path('logs/tele.log');
        file_put_contents($logPath, json_encode($update, JSON_PRETTY_PRINT));

        if (!isset($update->message)) {
            echo "Post Webhook Store Zozo.vn";
            return false;
        }

        $message = $update->message;
        $chat_id = $message->chat->id;
        $text = isset($message->text) ? strtolower(trim($message->text)) : '';

        $topic_id = '';
        if (isset($update->message->message_thread_id) && !empty($update->message->message_thread_id)) {
            $topic_id = $update->message->message_thread_id;
        }
        switch ($text) {
            case '/start':
                $this->sendMessage([
                    "chat_id" => $chat_id,
                    "text" => json_encode($update, JSON_PRETTY_PRINT)
                ]);
                break;


            case '/findid':
                $text = "";
                $text .= "Group/User ID: " . $update->message->chat->id . "\n";
                if (isset($update->message->message_thread_id) && !empty($update->message->message_thread_id)) {
                    $text .= "Topic ID: " . $update->message->message_thread_id;
                }
                $this->sendMessage([
                    "reply_to_message_id" => $update->message->message_id,
                    "chat_id" => $chat_id,
                    "message_thread_id" => $topic_id,
                    "text" => $text
                ]);
                break;


            case '/getme':
                $this->sendMessage([
                    "chat_id" => $chat_id,
                    "text" => $this->send('getMe')
                ]);
                break;

            case '/getwebhookinfo':
                $this->sendMessage([
                    "chat_id" => $chat_id,
                    "text" => $this->send('getWebhookInfo')
                ]);
                break;

            default:

                break;
        }
    }

    private function getAction($action)
    {
        return json_encode(['inline_keyboard' => [$action]]);
    }

    public function getWebhookInfo()
    {
        return $this->send('getWebhookInfo');
    }

    public function getUpdates($offset = -1)
    {
        $body = array();
        if ($offset !== null) {
            $body["offset"] = $offset;
        }
        return $this->send('getUpdates', $body);
    }

    private function sendPhoto($data = [])
    {
        $photoUrl = $this->getImageRandom();
        $caption = "getImageRandomz";

        $params = array_merge($data, [
            "photo" => $photoUrl,
            "caption" => $caption
        ]);

        $this->send('sendPhoto', $params);
    }

    private function sendMessage($data = array())
    {
        $this->send('sendMessage', $data);
    }

    function getImageRandom()
    {
        try {
            $client = new Client();
            $response = $client->get('https://aws.random.cat/meow');

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $data = json_decode($response->getBody()->getContents());

            // Check if json_decode was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error decoding JSON: ' . json_last_error_msg());
            }

            return $data->file ?? null;
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it or return a default value)
            error_log('Error in getImageRandom: ' . $e->getMessage());
            return null;
        }
    }


    function send($method, $data = array())
    {
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/" . $method;
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $request = new Request('POST', $url, $headers, json_encode($data));
        try {
            $res = $client->sendAsync($request)->wait();
            if ($res->getStatusCode() !== 200) {
                return 'Error: Status Code ' . $res->getStatusCode();
            }
            return $res->getBody()->getContents();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
