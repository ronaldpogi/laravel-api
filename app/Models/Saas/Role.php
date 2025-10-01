<?php

namespace App\Models\Saas;

use App\Enums\Role as EnumsRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sprout\Attributes\TenantRelation;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'saas_roles';

    protected $fillable = [
        'tenant_id',
        'name',
    ];

    protected function casts(): array
    {
        return [
            'name' => EnumsRole::class,
        ];
    }

    #[TenantRelation]
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'saas_role_permission', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'saas_role_user', 'role_id', 'user_id');
    }
}
