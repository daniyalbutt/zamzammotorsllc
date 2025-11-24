<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerNoteController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'note' => 'required|string',
        ]);

        CustomerNote::create([
            'customer_id' => $customer->id,
            'note' => $validated['note'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    public function destroy(CustomerNote $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Note deleted successfully!');
    }
}
