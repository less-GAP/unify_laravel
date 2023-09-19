<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * A model may have multiple direct permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id')->where('permissions.status', 'active');

    }
//    public function permissions(): BelongsToMany
//    {
//        $relation = $this->morphToMany(
//            config('permission.models.permission'),
//            'model',
//            config('permission.table_names.model_has_permissions'),
//            config('permission.column_names.model_morph_key'),
//            PermissionRegistrar::$pivotPermission
//        )->where('status','active');
//
//        if (! PermissionRegistrar::$teams) {
//            return $relation;
//        }
//
//        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId());
//    }
}
