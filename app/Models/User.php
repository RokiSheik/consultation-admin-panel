<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_DEVELOPER = 'DEVELOPER';
    const ROLE_STAFF = 'STAFF';
    // const ROLE_MARKETER = 'MARKETER';
    // const ROLE_SEO = 'SEO';
    // const ROLE_USER = 'USER';
    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_DEVELOPER =>'Developer',
        self::ROLE_STAFF => 'Staff',
        // self::ROLE_MARKETER => 'Marketer',
        // self::ROLE_SEO => 'Seo',
        // self::ROLE_USER => 'User',
    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return  $this->isAdmin() || $this->isDeveloperRole() || $this->isStaff();        
    }
    public function isAdmin(){
        return $this->role === self::ROLE_ADMIN;
    }
    public function isStaff(){
        return $this->role === self::ROLE_STAFF;
    }
    // public function isMarketer(){
    //     return $this->role === self::ROLE_MARKETER;
    // }
    // public function isSeo(){
    //     return $this->role === self::ROLE_SEO;
    // }
    public function isDeveloperRole(){
        return $this->role === self::ROLE_DEVELOPER;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
