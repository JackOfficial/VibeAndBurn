<?php

namespace App\Http\Controllers;

use App\Models\subscription as Subscription; // Use Alias for consistency
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the subscribers.
     */
    public function index(Request $request): View
    {
        // Added pagination (15 per page) to prevent slow loading as list grows
        $subscribers = Subscription::latest()->paginate(25);
        $subscribersCounter = Subscription::count();

        return view('admin.subscriptions.index', compact('subscribers', 'subscribersCounter'));
    }

    /**
     * Store a newly created subscription.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:subscriptions,email']
        ]);

        try {
            $sub = Subscription::create($validated);

            // Uncomment when ready to send confirmation
            // Mail::to($sub->email)->send(new confirmSubscription($sub->email));

            return back()->with('successSubscription', 'You have been subscribed successfully.');
            
        } catch (\Exception $e) {
            return back()->with('failSubscription', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Remove the specified subscriber from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $deleted = Subscription::where('id', $id)->delete();

        if ($deleted) {
            return back()->with('deleteSubscriberSuccess', 'Subscriber was removed successfully.');
        }

        return back()->with('deleteSubscriberFail', 'Subscriber not found or could not be deleted.');
    }
}