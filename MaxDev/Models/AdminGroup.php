<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/9/2018 4:21 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Model;
use MaxDev\Models\Traits\Uuids;
use MaxDev\Traits\HasStatus;

/**
 * MaxDev\Models\AdminGroup
 *

 */
class AdminGroup extends Model
{
    use HasStatus;
    use Uuids;

    protected $table = 'admin_groups';
    public $timestamps = true;
    protected $fillable = ['name', 'status', 'home_url', 'user_group', 'url_index'];


    public function permissions()
    {
        return $this->hasMany(AdminPermission::class, 'admin_group_id', 'id');
    }

    public function admins()
    {
        return $this->hasMany(Admin::class, 'user_group', 'user_group');
    }
}
