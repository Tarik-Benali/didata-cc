<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends Model
{
    protected $fillable = ['name','description','meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class)->orderBy('position');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DashboardVersion::class);
    }
}
