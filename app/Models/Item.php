<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    public function note_items()
    {
        return $this->hasMany(NoteItem::class);
    }
}
