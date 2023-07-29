<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Services;
use Illuminate\Support\Collection;
use MaxDev\Models\EmailModel;

use MaxDev\Models\Contact;
use MaxDev\Models\ContactEmail;

class EmailmodelService
{

    /**
     * @return Collection|null
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminIndex()
    {
        return EmailModel::select(['id','name','description']);
    }

    /**
     * @param $data
     * @return EmailModel
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminCreate($data)
    {
        return EmailModel::create($data);
    }

    /**
     * @param $data
     * @param EmailModel $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminUpdate($data, $model)
    {
        return $model->update($data);
    }


    /**
     * @param EmailModel $model
     * @return bool
     * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
     */
    public function adminDelete($model)
    {
        return $model->delete();
    }

    public function insertContactEmail($model)
    {
       $contacts = Contact::where('event_id',$model->event_id)->get();
       $allInserts=[];
       foreach($contacts as $contact){
            $arr['contact_id'] = $contact->id;
            $arr['email_model_id'] = $model->id;
            $arr['qr'] = $this->generateQr();
            $allInserts[]=$arr;
       }
       ContactEmail::insert($allInserts);
       return true;
    }

    public function generateQr() {
    	$qr = mt_rand(100000, 99999999);
    	if(ContactEmail::where('qr',$qr)->count() > 0) {
    		$this->generateQr();
    	} else {
    		return $qr;
    	}
    }

}
