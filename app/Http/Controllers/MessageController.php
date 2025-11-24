<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Customer $customer)
    {
        // Check if user has access to this customer
        $user = Auth::user();

        if ($user->hasRole('Sales Agent') && $customer->assigned_to != $user->id) {
            abort(403, 'You do not have access to this customer.');
        }

        if ($user->hasRole('Customer') && $customer->user_id != $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $messages = Message::where('customer_id', $customer->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.index', compact('customer', 'messages'));
    }

    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('messages', 'public');
        }

        Message::create([
            'customer_id' => $customer->id,
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
            'attachment' => $attachmentPath,
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
