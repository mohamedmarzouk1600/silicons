<?php

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MaxDev\Models\AdminPermission
 *
 * @property int $id
 * @property int $admin_group_id
 * @property string $route_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereAdminGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminPermission extends Model
{
    protected $table = 'admin_permissions';
    public $timestamps = true;
    protected $fillable = ['admin_group_id', 'route_name'];
}
