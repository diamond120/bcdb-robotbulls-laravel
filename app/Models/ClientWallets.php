<?php

namespace App\Models;

use App\BigChainDB\BigChainModel;

class ClientWallets extends BigChainModel
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected static $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'custom_wallet_name',
        'currency',
        'amount',
        'user_id',
        'privatekey',
        'publickey',
        'wallet_address',
        'created_at',
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

