<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Services;
use Illuminate\Support\Collection;
use MaxDev\Models\ContactEmail;
use MaxDev\Models\EmailModel;
use MaxDev\Models\Contact;
use MaxDev\Services\ContactemailService;

class ContactemailService
{

    /**
     * @return Collection|null
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminIndex()
    {
        return ContactEmail::select(['id','qr','send_email','send_message','scan_qr','email_model_id','contact_id']);
    }

    /**
     * @param $data
     * @return ContactEmail
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminCreate($data)
    {
        return ContactEmail::create($data);
    }

    /**
     * @param $data
     * @param ContactEmail $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminUpdate($data, $model)
    {
        return $model->update($data);
    }


    /**
     * @param ContactEmail $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminDelete($model)
    {
        return $model->delete();
    }

}
