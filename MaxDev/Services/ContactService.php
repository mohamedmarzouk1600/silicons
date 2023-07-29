<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Services;
use Illuminate\Support\Collection;
use MaxDev\Models\Contact;
use MaxDev\Models\Event;
use MaxDev\Services\ContactService;

class ContactService
{

    /**
     * @return Collection|null
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminIndex()
    {
        return Contact::select(['id','email','phone','type','event_id']);
    }

    /**
     * @param $data
     * @return Contact
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminCreate($data)
    {
        return Contact::create($data);
    }

    /**
     * @param $data
     * @param Contact $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminUpdate($data, $model)
    {
        return $model->update($data);
    }


    /**
     * @param Contact $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminDelete($model)
    {
        return $model->delete();
    }

}
