<?php

namespace App\Models;

use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'preferred_locale', 'phone', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasMedia, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasActivityLog, HasFactory, HasRoles, HasUuid, InteractsWithMedia, Notifiable, SoftDeletes {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'User';

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
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && $this->hasAnyRole(['director', 'admin', 'editor']);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * Never logs 'password' or 'remember_token' — both explicitly excluded
     * on top of only allow-listing safe, admin-relevant attributes.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()
            ->logOnly(['name', 'email', 'preferred_locale', 'phone', 'is_active'])
            ->logExcept(['password', 'remember_token']);
    }
}
