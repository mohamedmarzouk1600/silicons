<?php

namespace MaxDev\Imports;

use MaxDev\Models\ContactEmail;
use MaxDev\Models\Contact;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Mail;

class ContactsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        try{
            if($row[0] !== null && $row[0] !== 'email' && $row[1] !== 'phone')
            {
                $contact = Contact::create([
                            'email'           => $row[0],
                            'phone'           => $row[1],
                            'event_id'  => $row[2],
                        ]);
                // ContactEmail::create([
                //     'contact_id' => $contact->id,
                //     'event_id'   => $row[2],
                //     'qr'         => $this->generateQr(),
                // ]);
                return $contact;
            }
        }

        catch (\Exception $e) {

            // return $e->getMessage();
        }
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
