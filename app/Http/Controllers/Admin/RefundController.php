<?php

namespace App\Http\Controllers\Admin;

use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with('booking', 'user')
            ->latest()
            ->paginate(20);
        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(Refund $refund)
    {
        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(Refund $refund)
    {
        if ($refund->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending refunds can be approved!');
        }

        $refund->update([
            'status' => 'approved',
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Refund approved successfully!');
    }

    public function reject(Refund $refund, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string',
        ]);

        $refund->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'reason' => $request->rejection_reason ?? $refund->reason,
        ]);

        return redirect()->back()->with('success', 'Refund rejected!');
    }

    public function complete(Refund $refund)
    {
        if ($refund->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved refunds can be completed!');
        }

        $refund->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Refund completed!');
    }
}
