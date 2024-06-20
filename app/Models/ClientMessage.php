<?php

namespace App\Models;

use App\BigChainDB\BigChainModel;

class ClientMessage extends BigChainModel
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table = 'client_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user',
        'user_phone',
        'created_at',
        'message',
        'from',
        'channel',
        'from_phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}

