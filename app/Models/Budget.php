<?php

namespace App\Models;

use App\BudgetType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'amount', 'type', 'user_id'])]
class Budget extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function isGeneral(): bool
    {
        return $this->type === BudgetType::General->value;
    }

    public function isGoal(): bool
    {
        return $this->type === BudgetType::Goal->value;
    }
}
