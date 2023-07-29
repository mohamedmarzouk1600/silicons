<?php

namespace MaxDev\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailModel extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','event_id'];


    public function event()
    {
        return $this->belongsTo(Event::class,'event_id');
    }

}
