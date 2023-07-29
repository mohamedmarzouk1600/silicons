<?php

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactEmail extends Model
{
    use HasFactory;
    protected $fillable = ['qr','send_email','send_message','scan_qr','email_model_id','contact_id'];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }


    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function email()
    {
        return $this->belongsTo(EmailModel::class,'email_model_id');
    }

}
