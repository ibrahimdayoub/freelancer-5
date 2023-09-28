<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class House extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "type",
        "build",
        "floor",
        "room",
        "bed",
        "is_protected",
        "is_freind",
        "discount",
        "fees",
        "student_id",
        "status",
    ];
}
