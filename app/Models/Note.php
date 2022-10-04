<?php

namespace App\Models;

class Note extends \Illuminate\Database\Eloquent\Model
{

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function note_items(){
        return $this->hasMany(NoteItem::class);
    }
}
