<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Services;
use Illuminate\Support\Collection;
use MaxDev\Models\Event;

use MaxDev\Services\EventService;

class EventService
{

    /**
     * @return Collection|null
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminIndex()
    {
        return Event::select(['id','name','description','from_date','to_date','lat','lng']);
    }

    /**
     * @param $data
     * @return Event
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminCreate($data)
    {
        return Event::create($data);
    }

    /**
     * @param $data
     * @param Event $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminUpdate($data, $model)
    {
        return $model->update($data);
    }


    /**
     * @param Event $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminDelete($model)
    {
        return $model->delete();
    }

}
