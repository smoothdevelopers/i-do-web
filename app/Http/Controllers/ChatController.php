<?php

namespace App\Http\Controllers;

use App\Message;
use App\GroupMessage;
use App\MessageBlacklist;
use App\Events\MessageEvent;
use App\Events\GroupMessageEvent;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Requests\Chat\SendGroupMessageRequest;

/**
 * @resource Chat
 *
 * These endpoints will handle chatting for I-Do
 *
 */
class ChatController extends Controller
{
    public function sendMessage(SendMessageRequest $request)
    {
        $recipient =  User::find($request->recipient_id);

        if (! $recipient) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.user_not_found'),
                'error-description' => 'could not find the recipient',
            ], Response::HTTP_NOT_FOUND);
        }

        $blocked = MessageBlacklist::blocked(Auth::user(), $recipient);
        if ($blocked) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has been blocked',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $msg = new Message;
        $msg->message = $request->message;
        $msg->sender()->associate(Auth::user());
        $msg->recipient()->associate($recipient);
        $msg->save();

        emit(new MessageEvent($msg, $user));

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully saved message',
            'id'                => $msg->id,
        ], Response::HTTP_OK);
    }

    public function sendGroupMessage(GroupMessageRequest $request)
    {
        $group = Group::find($request->group_id);

        $msg = new GroupMessage;
        $msg->message = $request->message;
        $msg->group()->associate($group);
        $msg->sender()->associate(Auth::user());
        $msg->save();

        emit(new MessageEvent($msg, $user));
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully saved message',
            'id'                => $msg->id,
        ], Response::HTTP_OK);

    }
}
