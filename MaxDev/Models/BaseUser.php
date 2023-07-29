<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 04 ON 09 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MaxDev\Models\Traits\Uuids;
use MaxDev\Traits\HasStatus;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * MaxDev\Models\BaseUser
 *
 * @property int $id
 * @property string $fullname
 * @property string $email
 * @property int $user_group
 * @property string $status
 * @property string $password
 * @property string $language
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser active()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser inActive()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BaseUser whereUserGroup($value)
 * @mixin \Eloquent
 */
abstract class BaseUser extends Authenticatable implements HasMedia
{
    use HasFactory;
    use LogsActivity;
    use ExtraLogActivity;
    use HasStatus;
    use Uuids;
    use Notifiable;
    use InteractsWithMedia;

    public $hidden = array('password', 'remember_token');
    public $fillable = ['fullname', 'email', 'user_group', 'status','user_status', 'password', 'language', 'device_token'];
    public $casts = ['email_verified_at' => 'datetime',];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()->logOnlyDirty();
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }
}
