<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sprout\Contracts\Tenant as SproutTenant;
use Sprout\Database\Eloquent\Concerns\IsTenant;

class Tenant extends Model implements SproutTenant
{
    use HasFactory, IsTenant;

    protected $table = 'saas_tenants';

    protected $fillable = [
        'name',
        'address',
        'subdomain',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function getTenantIdentifierName(): string
    {
        return 'subdomain';
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
