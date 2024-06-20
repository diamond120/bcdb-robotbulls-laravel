<?php

namespace App\Models;

use App\BigChainDB\BigChainModel;

class Note extends BigChainModel
{
    /*
     * Table Name Specified
     */
    protected static $table = 'note';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
}
