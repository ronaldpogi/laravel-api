<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sprout\Attributes\TenantRelation;

class Role extends Model
{
    use HasFactory;

    protected $table = 'saas_roles';

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    #[TenantRelation]
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Role::class, 'saas_role_permission', 'permission_id', 'role_id');

    }

    public function users()
    {
        return $this->belongsToMany(Role::class, 'saas_role_user', 'user_id', 'role_id');
    }
}
