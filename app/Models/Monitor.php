<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'type', 'featured', 'set_featured_at', 'status', 'dest_ip', 'flag', 'logs'];

    public function uptimeRecords(): HasMany
    {
        return $this->hasMany(UptimeRecord::class, 'monitor_id', 'id');
    }

    public function incidents() : HasMany
    {
        return $this->hasMany(Incident::class, 'monitor_id', 'id');
    }

    public function tabs() : BelongsToMany
    {
        return $this->BelongsToMany(Tab::class, 'monitor_tab');
    }
}
