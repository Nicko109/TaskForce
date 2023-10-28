<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'cities';

    public function tasks()
    {
        return $this->hasMany(Task::class, 'category_id', 'id');
    }
}
