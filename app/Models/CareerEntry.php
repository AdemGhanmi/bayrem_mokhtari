<?php

namespace App\Models;

use App\Models\Concerns\Translates;
use Illuminate\Database\Eloquent\Model;

class CareerEntry extends Model
{
    use Translates;

    protected $fillable = ['period', 'club', 'role', 'country', 'logo', 'description', 'source_url', 'sort_order', 'is_active'];

    protected $casts = ['club' => 'array', 'role' => 'array', 'description' => 'array', 'is_active' => 'boolean'];

}
