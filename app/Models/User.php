<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isFriendWith(User $otherUser): bool
    {
        if ($this->email_verified_at != null && $otherUser->email_verified_at != null) {
            return Friendship::where('from_user_id', $this->id)
                ->where('to_user_id', $otherUser->id)
                ->exists()
                &&
                Friendship::where('from_user_id', $otherUser->id)
                    ->where('to_user_id', $this->id)
                    ->exists();
        }

        return false;
    }
}
