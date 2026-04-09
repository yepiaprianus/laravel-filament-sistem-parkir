<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'password',
    ];

    // Konstanta Role
    const ROLE_ADMIN = 'admin';
    const ROLE_PETUGAS = 'officer';
    const ROLE_OWNER = 'owner';

    // Opsional: Helper untuk cek role
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isPetugas()
    {
        return $this->role === self::ROLE_PETUGAS;
    }
    public function isOwner()
    {
        return $this->role === self::ROLE_OWNER;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
        // 'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel($panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'officer') {
            return in_array($this->role, ['admin', 'officer']);
        }

        if ($panel->getId() === 'owner') {
            return in_array($this->role, ['admin', 'owner']);
        }

        return false;
    }
}
