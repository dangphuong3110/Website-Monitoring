<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorTab extends Model
{
    use HasFactory;

    protected $table = 'monitor_tab';

    protected $fillable = ['monitor_id', 'tab_id', 'featured', 'set_featured_at'];

    public $timestamps = false;
}
