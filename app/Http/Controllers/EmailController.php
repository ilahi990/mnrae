<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use App\Mail\SubscriptionMail;

use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller

{
    // new method added by fahad
    // public function sendEmail(Request $request)
    // {

    //     // Prepare the payload as an array (this will be automatically converted to JSON)
    //     $payload = [
    //         'name' => $request->customer_name,
    //         'email' => $request->email,
    //         'company' => $request->company,
    //         'description' => $request->description,
    //         'phone' => $request->phone,
    //         // Include any additional details, like timestamp or user ID
    //         'submitted_at' => now()->toDateTimeString(),
    //     ];

    //     // Get the webhook URL from config
    //     $webhookUrl = config('app.webhook_url');

    //     // Send the POST request
    //     $response = Http::post($webhookUrl, $payload);

    //     // Optional: Check if the send was successful (status 200-299)
    //     if ($response->successful()) {
    //         // Handle success (e.g., redirect with a success message)
    //         return redirect()->back()->with('success', 'Message sent successfully!');
    //     } else {
    //         // Handle failure (e.g., log the error, but still show success to user if you don't want to block them)
    //         Log::error('Webhook failed: ' . $response->body());
    //         return redirect()->back()->with('error', 'There was an issue sending your message. Please try again.');
    //     }
    // }

    public function sendEmail(Request $request)
    {
        config([
            'mail.mailers.smtp.transport' => get_settings('smtp_protocol'),
            'mail.mailers.smtp.host' => get_settings('smtp_host'),
            'mail.mailers.smtp.port' => get_settings('smtp_port'),
            'mail.mailers.smtp.username' => get_settings('smtp_user'),
            'mail.mailers.smtp.password' => get_settings('smtp_pass'),
            'mail.mailers.smtp.encryption' => get_settings('smtp_crypto'),
            'mail.from.address' => $request->input('email'),
            'mail.from.name' => get_settings('system_title'),
        ]);

        $input = $request->all();
        if (get_settings('recaptcha_status') == 1) {
            $recaptcha_secret = get_settings('recaptcha_secretkey');
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $recaptcha_secret . "&response=" . $input['g-recaptcha-response']);
            $response = json_decode($response, true);
        } else {
            $response['success'] = true;
        }

        if ($response['success'] === true) {
            // Retrieve user data from the request
            $name = $request->input('customer_name');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $company = $request->input('company');
            $mailDescription = $request->input('description');

            // Compose the email content
            $subject = 'New Mail from ' . $name;
            $body = "Name: $name\nEmail: $email\nPhone: $phone\nCompany: $company\n\n$mailDescription";

            // Send the email
            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->from($email, get_settings('system_title'))
                    ->to(get_settings('system_email'))
                    ->subject($subject);
            });

            // Prepare the JSON payload for webhook
            $payload = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'message' => $mailDescription,
                'submitted_at' => now()->toDateTimeString(), // Optional: Add timestamp
            ];

            // Get the webhook URL from config (assuming it's set in .env and config/app.php as per previous instructions)
            $webhookUrl = config('app.webhook_url');

            // Send the POST request to webhook
            $webhookResponse = Http::post($webhookUrl, $payload);
            Log::info('Webhook sent successfully', [
                'status' => $webhookResponse->status(),
                'payload' => $payload,
            ]);
            // Optional: Check webhook response and log errors (doesn't affect user response)
            if (!$webhookResponse->successful()) {
                Log::error('Webhook failed: ' . $webhookResponse->status() . ' - ' . $webhookResponse->body());
            }

            // Return a response or redirect as desired
            return redirect()->back()->with('message', 'Email sent successfully.');
        } else {
            return redirect()->back()->with('error', 'You have to provide captcha');
        }
    }
}
