<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmsStatusCallback;

class SmsStatusCallbackController extends Controller
{
    public function store(Request $request)
    {
/*        $validatedData = $request->validate([
            'To' => 'required',
            'AccountSid' => 'required',
            'ApiVersion' => 'required',
            'MessageStatus' => 'required',
            'SmsSid' => 'required',
            'From' => 'required',
            'MessageSid' => 'required',
            'SmsStatus' => 'required',
        ]);*/

        $smsStatusCallback = SmsStatusCallback::create([
            'to' => $request->To,
            'message_id' => $request->messageId,
            'status' => $request->MessageStatus,
        ]);
        return response()->json([
            'success' => true,
            '_token' => csrf_token(),
        ]);
    }
}
