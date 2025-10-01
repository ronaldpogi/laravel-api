<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sprout\Attributes\TenantRelation;

class Permission extends Model
{
    use HasFactory, HasUuids;

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
