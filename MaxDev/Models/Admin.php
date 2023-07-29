<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 04 ON 09 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\Scopes\AdminScope;

class Admin extends BaseUser
{
    use HasApiTokens;

    protected $table = 'users';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new AdminScope());
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
//        array_push($this->fillable, 'user_status');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "An admin has been {$eventName}";
    }

    public function adminGroup()
    {
        return $this->belongsTo(AdminGroup::class, 'user_group', 'user_group');
    }

    public function permissions()
    {
        return $this->hasManyThrough(
            AdminPermission::class,
            AdminGroup::class,
            'user_group',
            'admin_group_id',
            'user_group'
        );
    }



   
}
