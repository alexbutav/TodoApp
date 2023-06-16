<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @property int $id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property string $name
 * @property string $email
 * @property string $body
 * @property bool $done
 * @property bool $edited
 */
class Task extends Eloquent
{
    protected $fillable = ['email', 'name', 'body', 'edited', 'done'];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'done' => 'boolean',
        'edited' => 'boolean',
    ];
}
