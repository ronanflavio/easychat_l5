<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Users table
    |--------------------------------------------------------------------------
    |
    | Below you will set the model, table and fields names from users table.
    |
    */

    'users' => array(

        /**
         * Set the Model name:
         */

        'model' => 'App\User',

        /**
         * Set the Table name:
         */

        'table' => 'usuarios',

        /**
         * Set the Fields names:
         */

        'id'         => 'id',
        'name'       => 'nome',
        'photo'      => null,
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ),

    /*
    |--------------------------------------------------------------------------
    | Users Messages table
    |--------------------------------------------------------------------------
    |
    | Below you will set the model, table and fields names from user messages
    | table.
    |
    */

    'user_messages' => array(

        /**
         * Set the Model name:
         */

        'model' => 'Ronanflavio\Easychat\Models\UserMessage',

        /**
         * Set the Table name:
         */

        'table' => 'ec_user_messages',

        /**
         * Set the Fields names:
         */

        'id'                => 'id',
        'user_id'           => 'user_id',
        'sent_by'           => 'sent_by',
        'sent_to'           => 'sent_to',
        'server_message_id' => 'server_message_id',
        'created_at'        => 'created_at',
        'updated_at'        => 'updated_at',
    ),

    /*
    |--------------------------------------------------------------------------
    | Server Messages table
    |--------------------------------------------------------------------------
    |
    | Below you will set the model, table and fields names from rooms table.
    |
    */

    'server_messages' => array(

        /**
         * Set the Model name:
         */

        'model' => 'Ronanflavio\Easychat\Models\ServerMessage',

        /**
         * Set the Table name:
         */

        'table' => 'ec_server_messages',

        /**
         * Set the Fields names:
         */

        'id' => 'id',
        'sent_by' => 'sent_by',
        'sent_to' => 'sent_to',
        'room_id' => 'room_id',
        'text' => 'text',
        'col_size' => 'col_size',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ),

    /*
    |--------------------------------------------------------------------------
    | Rooms table
    |--------------------------------------------------------------------------
    |
    | Below you will set the model, table and fields names from rooms table.
    |
    */

    'rooms' => array(

        /**
         * Set the Model name:
         */

        'model' => 'Ronanflavio\Easychat\Models\Room',

        /**
         * Set the Table name:
         */

        'table' => 'ec_rooms',

        /**
         * Set the Fields names:
         */

        'id' => 'id',
        'user_a' => 'user_a',
        'user_b' => 'user_b',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ),

);
