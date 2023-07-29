<?php

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['email','phone','type','event_id'];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
