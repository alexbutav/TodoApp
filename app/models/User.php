<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $is_admin
 */
class User extends Eloquent
{
    public $timestamps = [];

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
