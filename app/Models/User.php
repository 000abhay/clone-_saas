<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_summary',
        'status',
        'last_active_at',
        'failed_login_attempts',
        'locked_until',
    ];

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
            'last_active_at' => 'datetime',
            'locked_until' => 'datetime',
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'owner_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'owner_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_user_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class, 'assignee_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'sales_manager' => 'Sales Manager',
            'sales_exec' => 'Sales Executive',
            'support_agent' => 'Support Agent',
            default => ucfirst(str_replace('_', ' ', (string) $this->role)),
        };
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn (string $part) => strtoupper($part[0]))
            ->take(2)
            ->implode('');
    }

    public function canManageTeam(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function canAccessModule(string $module): bool
    {
        return in_array($module, $this->accessibleModules(), true);
    }

    public function accessibleModules(): array
    {
        return match ($this->role) {
            'super_admin', 'admin' => ['dashboard', 'accounts', 'contacts', 'leads', 'pipeline', 'tasks', 'tickets', 'reports', 'team', 'settings'],
            'sales_manager' => ['dashboard', 'accounts', 'contacts', 'leads', 'pipeline', 'tasks', 'tickets', 'reports', 'settings'],
            'sales_exec' => ['dashboard', 'accounts', 'contacts', 'leads', 'pipeline', 'tasks', 'settings'],
            'support_agent' => ['dashboard', 'accounts', 'contacts', 'tasks', 'tickets', 'reports', 'settings'],
            default => ['dashboard', 'settings'],
        };
    }

    public function isAdminLike(): bool
    {
        return in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function isSalesUser(): bool
    {
        return in_array($this->role, ['sales_manager', 'sales_exec'], true);
    }

    public function canViewReports(): bool
    {
        return $this->canAccessModule('reports');
    }

    public function canManageSalesData(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'sales_manager', 'sales_exec'], true);
    }

    public function canManageSupport(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'support_agent'], true);
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status ?: 'active');
    }

    public function avatarTone(): string
    {
        return match ($this->role) {
            'super_admin' => 'teal',
            'admin' => 'amber',
            'sales_manager' => 'blue',
            'sales_exec' => 'slate',
            'support_agent' => 'green',
            default => 'slate',
        };
    }
}
