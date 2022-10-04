<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Note;
use App\Models\NoteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class NotesController extends Controller
{

    public function index()
    {
        $notes = Note::with(['customer', 'note_items.item'])->paginate(10);
        $items = Item::all();
        $customers = Customer::all();
        return Inertia::render('Notes', [
            'notes' => $notes,
            'items' => $items,
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
        $note = new Note();
        $note->customer_id = $request->input('customer_id');
        $note->total = $request->input('total');
        $note->date = $request->input('date');
        $note->save();
        foreach ($request->input('note_items') as $note_item) {
            $noteItem = new NoteItem();
            $noteItem->note_id = $note->id;
            $noteItem->item_id = $note_item['item_id'];
            $noteItem->quantity = $note_item['quantity'];
            $noteItem->total = $note_item['total'];
            $noteItem->save();
        }

        return redirect()->route('notes');
    }

    public function update(Request $request, $id)
    {
        $note = Note::find($id);
        $note->customer_id = $request->input('customer_id');
        $note->total = $request->input('total');
        $note->date = $request->input('date');
        $note->save();
        $note->note_items()->delete();
        foreach ($request->input('note_items') as $note_item) {
            $noteItem = new NoteItem();
            $noteItem->note_id = $note->id;
            $noteItem->item_id = $note_item['item_id'];
            $noteItem->quantity = $note_item['quantity'];
            $noteItem->total = $note_item['total'];
            $noteItem->save();
        }

        return redirect()->route('notes');
    }

    public function destroy($id)
    {
        $note = Note::find($id);
        $note->note_items()->delete();
        $note->delete();
        return Redirect::route('notes');
    }

}
