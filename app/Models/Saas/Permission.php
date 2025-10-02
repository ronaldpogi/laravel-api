<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sprout\Attributes\TenantRelation;
use Sprout\Database\Eloquent\Concerns\BelongsToTenant;

class Permission extends Model
{
    use BelongsToTenant, HasFactory, HasUuids;

    protected $table = 'saas_permissions';

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    #[TenantRelation]
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'saas_role_permission', 'permission_id', 'role_id');
    }
}
