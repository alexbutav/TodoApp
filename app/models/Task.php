<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model as Eloquent;

    class Task extends Eloquent {
        protected $fillable = ['email', 'name', 'body'];
    }