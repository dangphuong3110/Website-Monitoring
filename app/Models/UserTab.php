<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTab extends Model
{
    use HasFactory;

    protected $table = 'user_tab';

    protected $fillable = ['user_id', 'tab_id'];

    public $timestamps = false;
}
