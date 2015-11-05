<?php

namespace Ronanflavio\Easychat\Http;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Ronanflavio\Easychat\Http\EasychatRequest;
use Ronanflavio\Easychat\Models\ServerMessage;
use Ronanflavio\Easychat\Models\UserMessage;
use Ronanflavio\Easychat\Models\Room;
use Auth;
use DB;
use Input;
use Response;

class EasychatController extends Controller
{
    /**
     * Array with basic configs to chat
     *
     * @var array
     */
    private $config = array();

    /**
     * Map of tables used to chat
     *
     * @var array
     */
    private $tables = array();

    /**
     * THe consruct defines the default values from the config e tables values.
     *
     */
    public function __construct()
    {
        $this->config = config('packages.Ronanflavio.Easychat.config');
        $this->tables = config('packages.Ronanflavio.Easychat.tables');
    }

    /**
     * Returns the main view
     *
     * @return View with users list
     */
    public function getIndex()
    {
        $user = $this->tables('users');
        $config = $this->config();

        $activeUsers = $this->usersList(new EasychatRequest());
        $inactiveIds = array(Auth::user()->$user['id']);

        foreach ($activeUsers as $item)
        {
            $inactiveIds = array_merge($inactiveIds, array($item->id));
        }

        $inactiveUsers = $user['model']::whereNotIn($user['id'], $inactiveIds)->get();
        $users = array($activeUsers, $inactiveUsers);

        $view = view('easychat::index', compact('users', 'config'));

        return $view;
    }

    /**
     * Send a new message
     *
     * @return JSON with server_message id
     */
    public function sendMessage(EasychatRequest $request)
    {
        $input = $request->input();

        $user           = $this->tables('users');
        $user_messages  = $this->tables('user_messages');
        $room           = $this->tables('rooms');
        $server_message = $this->tables('server_messages');

        $by = Auth::user()->$user['id'];
        $to = $input['to'];
        $text = $input['text'];

        $obj_room = $room['model']::where($room['user_a'], '=', $by)
            ->where($room['user_b'], '=', $to)
            ->first();

        if ( ! $obj_room)
        {
            $obj_room = $room['model']::where($room['user_b'], '=', $by)
                ->where($room['user_a'], '=', $to)
                ->first();

            if ( ! $obj_room)
            {
                $obj_room = $room['model']::create(array(
                    $room['user_a'] => $by,
                    $room['user_b'] => $to
                ));
            }
        }

        $obj_server_message = $server_message['model']::create(array(
            $server_message['sent_by'] => $by,
            $server_message['sent_to'] => $to,
            $server_message['room_id'] => $obj_room->id,
            $server_message['text'] => $text,
            $server_message['col_size'] => $this->colSize($text)
        ));

        $obj_user_messages_by = $user_messages['model']::create(array(
            $user_messages['user_id'] => $by,
            $user_messages['sent_by'] => $by,
            $user_messages['sent_to'] => $to,
            $user_messages['server_message_id'] => $obj_server_message->id
        ));

        $obj_user_messages_to = $user_messages['model']::create(array(
            $user_messages['user_id'] => $to,
            $user_messages['sent_by'] => $by,
            $user_messages['sent_to'] => $to,
            $user_messages['server_message_id'] => $obj_server_message->id
        ));

        $includes = array(
            'text' => $text,
            'col_size' => $this->colSize($text)
        );
        $conversation = $obj_user_messages_by->toArray();
        $conversation = array_merge($conversation, $includes);

        return Response::json(array(
            'status' => $obj_server_message ? 'success' : 'error',
            'chat' => $conversation,
            'auth_id' => Auth::user()->$user['id'],
            'user_name' => Auth::user()->$user['name']
        ));
    }

    /**
     * Get the messages from a chat roo
     *
     * @return JSON with the messages list
     */
    public function messagesList(EasychatRequest $request)
    {
        $input = $request->input();

        $user            = $this->tables('users');
        $user_messages   = $this->tables('user_messages');
        $room            = $this->tables('rooms');
        $server_messages = $this->tables('server_messages');

        $user_id = $input['user_id'];
        $user_receiver = $user['model']::findOrFail($user_id);
        $conversation = array();
        $chat_count = 0;
        $limit_messages = $input['limit'];

        $obj_room = $room['model']::where($room['user_a'], '=', Auth::user()->$user['id'])
            ->where($room['user_b'], '=', $user_id)
            ->first();

        if ( ! $obj_room)
        {
            $obj_room = $room['model']::where($room['user_b'], '=', Auth::user()->$user['id'])
                ->where($room['user_a'], '=', $user_id)
                ->first();
        }

        if ($obj_room)
        {
            $query = $user_messages['model']::select(array(
                $user_messages['table'].'.*',
                $server_messages['table'].'.'.$server_messages['text'].' as text',
                $server_messages['table'].'.'.$server_messages['col_size'].' as col_size'
                ))
                ->join($server_messages['table'], $server_messages['table'].'.'.$server_messages['id'], '=', $user_messages['table'].'.'.$user_messages['server_message_id'])
                ->join($room['table'], $room['table'].'.'.$room['id'], '=', $server_messages['room_id'])
                ->where($server_messages['table'].'.'.$server_messages['room_id'], '=', $obj_room->$room['id'])
                ->where($user_messages['table'].'.'.$user_messages['user_id'], '=', Auth::user()->$user['id']);

            $user_conversation = $query
                ->distinct($server_messages['id'])
                ->orderBy($user_messages['table'].'.'.$user_messages['created_at'], 'DESC');

            if ($limit_messages == 'true')
            {
                $user_conversation = $user_conversation
                    ->take($this->config('message_limit'));
            }

            $user_conversation = $user_conversation->get()->reverse();
            $chat_count = $query->count();

            if ( ! $user_conversation->isEmpty())
            {
                $conversation = $user_conversation->toArray();
            }
        }

        return Response::json(array(
            'status' => $conversation ? 'success' : 'error',
            'chats' => $conversation,
            'user_name' => $user_receiver->$user['name'],
            'auth_id' => Auth::user()->$user['id'],
            'chat_count' => $chat_count
        ));

    }

    /**
     * Get new messeges from a specific chat
     *
     * @return JSON with the messages list
     */
    public function newMessages(EasychatRequest $request)
    {
        $input = $request->input();

        $user = $this->tables('users');
        $room = $this->tables('rooms');
        $user_messages = $this->tables('user_messages');
        $user_id = $input['user_id'];

        $query = $user_messages['model']::join($room['table'], function($join) use ($room, $user_messages) {
                $join->on($room['table'].'.'.$room['user_a'], '=', $user_messages['user_id'])
                    ->orOn($room['table'].'.'.$room['user_b'], '=', $user_messages['user_id']);
            })
            ->where($user_messages['table'].'.'.$user_messages['created_at'], '>=', DB::raw('DATE_SUB(now(), INTERVAL 20 SECOND)'))
            ->where(function($query) use ($user_messages, $user_id, $user) {
                $query->orWhere($user_messages['table'].'.'.$user_messages['sent_by'], '=', $user_id)
                ->orWhere($user_messages['table'].'.'.$user_messages['sent_to'], '=', $user_id)
                ->orWhere($user_messages['table'].'.'.$user_messages['sent_by'], '=', Auth::user()->$user['id'])
                ->orWhere($user_messages['table'].'.'.$user_messages['sent_to'], '=', Auth::user()->$user['id']);
            })
            ->where($user_messages['table'].'.'.$user_messages['user_id'], '=', Auth::user()->$user['id']);

        $messages_count = $query->count();
        $messages = $query->get();

        return Response::json(array(
            'count' => $messages_count,
            'chats' => $messages
        ));

        return Response::json($messages);
    }

    /**
     * Check if have new messages from a specific user
     *
     * @return JSON with bool result
     */
    public function checkMessages(EasychatRequest $request)
    {
        $input = $request->input();

        $user = $this->tables('users');
        $room = $this->tables('rooms');
        $user_messages = $this->tables('user_messages');
        $user_id = $input['user_id'];

        $resposta = $user_messages['model']::join($room['table'], function($join) use ($room, $user_messages) {
                $join->on($room['table'].'.'.$room['user_a'], '=', $user_messages['user_id'])
                    ->orOn($room['table'].'.'.$room['user_b'], '=', $user_messages['user_id']);
            })
            ->where($user_messages['table'].'.'.$user_messages['created_at'], '>=', DB::raw('DATE_SUB(now(), INTERVAL 20 SECOND)'))
            ->where($user_messages['table'].'.'.$user_messages['sent_by'], '=', $user_id)
            ->where($user_messages['table'].'.'.$user_messages['sent_to'], '=', Auth::user()->$user['id'])
            ->where($user_messages['table'].'.'.$user_messages['user_id'], '=', Auth::user()->$user['id'])
            ->count(array($user_messages['table'].'.'.$user_messages['id']));

        return ($resposta > 0 ? Response::json(true) : Response::json(false));
    }

    /**
     * Check if have any new messages
     *
     * @return JSON with bool result
     */
    public function checkAllMessages(EasychatRequest $request)
    {
        $input = $request->input();

        $user = $this->tables('users');
        $user_messages = $this->tables('user_messages');

        $result = ServerMessage::select(array(
                DB::raw('COUNT('.$user_messages['id'].') as message_count'),
                $user_messages['sent_by']
            ))
            ->where($user_messages['created_at'], '>=', DB::raw('DATE_SUB(now(), INTERVAL 10 SECOND)'))
            ->where($user_messages['sent_to'], '=', Auth::user()->$user['id'])
            ->groupBy($user_messages['sent_by'])
            ->get(array('message_count', 'sent_by'));

        return Response::json($result);
    }

    /**
     * Delete a specified user message
     *
     * @return JSON with bool result
     */
    public function deleteMessage()
    {
        $id = $input['message_id'];
        $user_messages = $this->tables('user_messages');
        $message = $user_messages['model']::findOrFail($id);
        $message->delete();
    }

    /**
     * Return a list of users
     *
     * @return JSON with list
     */
    public function usersList(EasychatRequest $request, $param = null, $impersonate = true)
    {
        $input = $request->input();

        $user = $this->tables('users');
        $server_messages = $this->tables('server_messages');

        if (isset($input['all_users']))
        {
            return Response::json($user['model']::select(array(
                $user['name'].' as username',
                $user['id'].' as user_id'
            ))->lists('username', 'user_id'));
        }

        $result = $user['model']::join($server_messages['table'], function($join) use ($user, $server_messages)
            {
                $join->on($server_messages['table'].'.'.$server_messages['sent_to'], '=', $user['table'].'.'.$user['id'])
                    ->orOn($server_messages['table'].'.'.$server_messages['sent_by'], '=', $user['table'].'.'.$user['id']);
            })
            ->where(function($query) use ($server_messages, $user)
            {
                $query->where($server_messages['table'].'.'.$server_messages['sent_to'], '=', Auth::user()->$user['id'])
                    ->orWhere($server_messages['table'].'.'.$server_messages['sent_by'], '=', Auth::user()->$user['id']);
            });

            if ($impersonate)
            {
                $result = $result->where($user['table'].'.'.$user['id'], '<>', Auth::user()->$user['id']);
            }

        if ($param == 'ajax')
        {
            return Response::json($result->select(array(
                    $user['table'].'.'.$user['name'].' as username',
                    $user['table'].'.'.$user['id'].' as user_id'
                ))->lists('username', 'user_id'));
        }

        return $result->select(array(
                $user['table'].'.*',
                DB::raw('MAX('.$server_messages['table'].'.'.$server_messages['created_at'].') AS maximum')
            ))
            ->groupBy($user['table'].'.'.$user['id'])
            ->orderBy('maximum', 'DESC')
            ->get();
    }

    /**
     * Returns the specific key => value configuration
     *
     * @param  string $key
     * @param  array  $param
     * @return dynamic        string or array value
     */
    private function config($key = null, $param = array())
    {
        switch ($key)
        {
            case 'views':
                return $this->makeView($param);

            case 'error':
                return $this->config[$key];

            case 'div_conversation_size':
                return $this->config[$key];

            case 'message_limit':
                return $this->config[$key];

            case null:
                return $this->config;

            default:
                return false;
        }
    }

    /**
     * Return the table map attributes
     *
     * @param  string $table
     * @param  array  $params
     * @return dynamic         string or array value
     */
    private function tables($table, $params = array())
    {
        return $this->tables[$table];
    }

    /**
     * Return a view string path
     *
     * @param  array  $param
     * @return string
     */
    private function makeView($param = array())
    {
        if (empty($param))
        {
            return $this->config['views'];
        }

        $argument = '';
        $count = 1;

        foreach ($param as $item)
        {
            $argument .= $item;
            if (sizeof($param) < $count)
            {
                $argument .= '.';
            }
        }

        return $this->config['views'].'.'.$argument;
    }

    /**
     * Return a string with the Bootstrap col size for conversation div
     *
     * @param  string $str
     * @return string
     */
    private function colSize($str)
    {
        $size = strlen($str);
        if ($size < 5)
        {
            return 'col-md-2';
        }
        elseif ($size >= 5 AND $size < 10)
        {
            return 'col-md-3';
        }
        elseif ($size >= 10 AND $size < 15)
        {
            return 'col-md-4';
        }
        elseif ($size >= 15 AND $size < 20)
        {
            return 'col-md-5';
        }
        elseif ($size >= 20 AND $size < 25)
        {
            return 'col-md-6';
        }
        elseif ($size >= 25 AND $size < 30)
        {
            return 'col-md-7';
        }
        elseif ($size >= 30 AND $size < 35)
        {
            return 'col-md-8';
        }
        elseif ($size >= 35)
        {
            return 'col-md-9';
        }
    }
}
