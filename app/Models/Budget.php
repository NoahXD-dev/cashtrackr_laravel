<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'amount', 'type', 'user_id'])]
class Budget extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
