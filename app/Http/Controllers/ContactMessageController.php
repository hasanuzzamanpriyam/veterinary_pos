<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'email' => ['required', 'email', 'max:255'],
                'subject' => ['required', 'in:product,order,support,feedback,other'],
                'message' => ['required', 'string'],
            ]);

            $message = ContactMessage::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => 'new',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully. We will get back to you within 24 hours.',
                'data' => $message,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending your message.',
            ], 500);
        }
    }
}
