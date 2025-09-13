<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DashboardVersion extends Model
{
    protected $fillable = ['dashboard_id','version_number','snapshot','change_type','created_by'];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(Dashboard::class);
    }
}
