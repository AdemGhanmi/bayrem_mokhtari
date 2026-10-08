<?php

namespace App\Models;

use App\Models\Concerns\Translates;
use Illuminate\Database\Eloquent\Model;

class Honour extends Model
{
    use Translates;

    protected $fillable = ['title', 'description', 'year', 'image', 'source_url', 'sort_order', 'is_active'];

    protected $casts = ['title' => 'array', 'description' => 'array', 'is_active' => 'boolean'];

}
