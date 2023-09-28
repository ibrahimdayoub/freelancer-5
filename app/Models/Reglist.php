<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Reglist extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "first_name",
        "last_name",
        "gender",
        "father_name",
        "mother_name",
        "governorate",
        "email",
        "phone",
        "is_disabled",
        "collage",
        "collage_id",
        "year",
        "is_successded"
    ];
}
