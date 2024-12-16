<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyActionPoint extends Model
{

    use HasFactory;
    protected $table = "daily_action_points";
    protected $fillable = [
        "title",
        "description",
        "date"
    ];
}
