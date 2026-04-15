<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|regex:/^[0-9]{10}$/',
            'email' => 'required|email|max:255',
            'city' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ], [
            'mobile_number.regex' => 'Please enter a valid 10-digit mobile number.',
        ]);

        // You can store this in database or send email
        // For now, just send success message
        // Optionally: Store in database
        // ContactMessage::create($validated);

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
