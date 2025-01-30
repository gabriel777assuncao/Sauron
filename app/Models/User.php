<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    public function like(Question $question): void
    {
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            ['likes' => 1, 'unlikes' => 0]
        );
    }

    public function unlike(Question $question): void
    {
        $this->votes()->updateOrCreate(
            ['question_id' => $question->id],
            ['likes' => 0, 'unlikes' => 1]
        );
    }
}
