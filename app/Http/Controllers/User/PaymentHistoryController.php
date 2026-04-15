<?php

namespace App\Http\Controllers\User;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        $payments = Auth::user()->payments()->with('booking')->latest()->paginate(15);
        return view('user.payments.history', compact('payments'));
    }
}
