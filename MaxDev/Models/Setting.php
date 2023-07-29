<?php

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

/**
 * MaxDev\Models\Setting
 *
 * @property int $id
 * @property string $name
 * @property array $value
 * @property string $type
 * @property string $has_translations
 * @property string|null $extra
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read array $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Query\Builder|Setting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereHasTranslations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|Setting withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Setting withoutTrashed()
 * @mixin \Eloquent
 */
class Setting extends Model
{
    use SoftDeletes;
    use HasTranslations;
    use LogsActivity;
    use ExtraLogActivity;

    protected $fillable = ['name','value','type','groups','extra','has_translations'];
    protected $table = 'settings';
    public $timestamps = true;
    public $translatable = ['value'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'extra'=> 'array',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "A setting has been {$eventName}";
    }

}
