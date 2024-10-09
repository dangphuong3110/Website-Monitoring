<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function monitors() : BelongsToMany
    {
        return $this->belongsToMany(Monitor::class, 'monitor_tab');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tab');
    }
}
