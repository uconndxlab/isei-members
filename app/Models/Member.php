<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model

{
    protected $fillable = [
        'last_name',
        'first_name',
        'degree',
        'position',
        'organization',
        'email',
        'country',
        'gen_int1',
        'gen_int2',
        'gen_int3',
        'entry_date',
        'member_id',
    ];
    use HasFactory;
}
