<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    // Alternative method for routing customer Appontiments details on webhook
     public function customerBookAppointment(Request $request)
    {
        // Validate the form data (adjust fields based on your form)
       try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string|max:500',

        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();

    }
        // Prepare the JSON payload for webhook
        $payload = [
            'name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'listing_id' => $request->listing_id, // Assuming this is passed in the request
            'listing_type_id' => $request->listing_type_id, // Assuming this is
            'agent_id' => $request->agent_id, // Assuming this is passed in the request
            'submitted_at' => now()->toDateTimeString(), // Consistent with previous form
        ];

        // Get the webhook URL from config
        $webhookUrl = config('app.webhook_url');

        // Send the POST request to webhook
        $webhookResponse = Http::post($webhookUrl, $payload);

        // Log the webhook attempt
        Log::info('Webhook sent for appointment', [
            'status' => $webhookResponse->status(),
            'payload' => $payload,
        ]);

        // Check webhook response and log errors (doesn't affect user response)
        if (!$webhookResponse->successful()) {
            Log::error('Webhook failed: ' . $webhookResponse->status() . ' - ' . $webhookResponse->body());
        }

        // Return response to user
        return redirect()->back()->with('message', 'You will be contacted soon.');
    }
}

