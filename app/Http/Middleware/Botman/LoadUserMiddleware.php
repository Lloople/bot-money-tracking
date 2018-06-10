<?php

namespace App\Http\Middleware\Botman;

use App\Models\Group;
use App\Models\User;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class LoadUserMiddleware implements Received
{
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $user = User::findOrCreateTelegram($bot->getDriver()->getUser($message));

        $group = Group::findOrCreateTelegram((object)$message->getPayload()->get('chat'));

        $user->addToGroup($group);

        $user->group = $group;

        auth()->login($user);

        return $next($message);
    }
}