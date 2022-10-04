<?php

namespace App\Models;

class NoteItem extends \Illuminate\Database\Eloquent\Model
{

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

}
