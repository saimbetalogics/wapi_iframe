<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $rawType = strtolower($request->input('type', 'text'));
        $identity = $request->input('identity', '');

        $apiTypeMap = [
            'text'     => 'TEXT',
            'photo'    => 'IMAGE',
            'image'    => 'IMAGE',
            'document' => 'DOCUMENT',
            'doc'      => 'DOCUMENT',
            'voice'    => 'AUDIO',
            'audio'    => 'AUDIO',
        ];

        $apiType = $apiTypeMap[$rawType] ?? strtoupper($rawType);

        $time = date('g:i A');
        $msgId = rand(500, 9999);

        $outgoingMessage = [
            'id'     => $msgId,
            'type'   => $rawType,
            'out'    => true,
            'time'   => $time,
            'status' => 'sent',
        ];

        $apiResult = null;
        $endpoint = 'https://wapi.betalogics.com/e-web/send-message';

        try {
            if ($apiType === 'TEXT') {
                $textValue = $request->input('value', $request->input('text', ''));
                $outgoingMessage['text'] = $textValue;

                if (!empty($identity)) {
                    $response = Http::withHeaders([
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post($endpoint, [
                        'identity' => "eyJpdiI6InY1Z2pIZ21neENrcTZ5NExMbXpDU3c9PSIsInZhbHVlIjoicFR2Rk4rdG1rZE1rSjkrQjMwaFI3TXlvSFg4RmFsVWFGQ3IyYyt1cTg5OHhTQ3Zwa2REUEZGdmxTb3JiaDFqT0ZsUHFvNHdBWXlzdjBGMUQ2L25IYXc1cTErNlc5RzBUSmtYMnlLMUIvUll1L2ZuV0Fac2R2MklYTWJlTk1qM04iLCJtYWMiOiJkNzg3ZTE4OWFhNTNiMGFiNTRjNTZjYzc3ZWYxNGI3ZThhNzVmMDA2NTQ4MjA5ZjQxMjViMzZhNTJkOGZkY2IzIiwidGFnIjoiIn0=",
                        'type'     => 'TEXT',
                        'value'    => $textValue,
                    ]);
                    $apiResult = $response->json();
                }
            } else {
                $file = $request->file('value') ?: $request->file('file');

                if ($rawType === 'document' || $rawType === 'doc') {
                    if ($file) {
                        $outgoingMessage['fileName'] = $file->getClientOriginalName();
                        $outgoingMessage['fileSize'] = round($file->getSize() / (1024 * 1024), 1) . ' MB';
                    } else {
                        $outgoingMessage['fileName'] = $request->input('fileName', 'Document.pdf');
                        $outgoingMessage['fileSize'] = $request->input('fileSize', '1.5 MB');
                    }
                } elseif ($rawType === 'photo' || $rawType === 'image') {
                    if ($file) {
                        $path = $file->store('uploads', 'public');
                        $outgoingMessage['photoUrl'] = asset('storage/' . $path);
                    } else {
                        $outgoingMessage['photoUrl'] = $request->input('photoUrl', $request->input('value', ''));
                    }
                } elseif ($rawType === 'voice' || $rawType === 'audio') {
                    $outgoingMessage['duration'] = (int) $request->input('duration', 5);
                    $outgoingMessage['peaks']    = json_decode($request->input('peaks', '[]'), true) ?: [0.3, 0.6, 0.4, 0.8, 1.0, 0.7, 0.5, 0.3, 0.6, 0.9, 0.4, 0.2, 0.7, 0.8, 0.5, 0.9, 0.6, 0.3, 0.5, 0.7, 0.4, 0.2, 0.6, 0.8, 0.5, 0.3, 0.4, 0.2];
                }

                if (!empty($identity)) {
                    if ($file) {
                        $response = Http::withHeaders([
                            'Accept' => 'application/json',
                        ])->attach(
                            'value',
                            file_get_contents($file->getRealPath()),
                            $file->getClientOriginalName()
                        )->post($endpoint, [
                            'identity' => $identity,
                            'type'     => $apiType,
                        ]);
                    } else {
                        $response = Http::withHeaders([
                            'Accept'       => 'application/json',
                            'Content-Type' => 'application/json',
                        ])->post($endpoint, [
                            'identity' => $identity,
                            'type'     => $apiType,
                            'value'    => $request->input('value', ''),
                        ]);
                    }
                    $apiResult = $response->json();
                }
            }
        } catch (\Exception $e) {
            Log::error('WAPI Send Message error: ' . $e->getMessage());
        }

        $autoReplies = [
            "Got it! 👍",
            "Sure, sounds great!",
            "I'll get back to you shortly.",
            "Thanks for letting me know 😊",
            "Interesting! Tell me more 🤔",
            "✅ Done!",
            "On it! 🚀",
            "Absolutely, no problem at all!",
            "Let me check and confirm.",
            "That works for me! 🎉",
            "Perfect, thanks! 🙌",
            "Noted! 📝",
        ];

        $replyMsgId = $msgId + 1;
        $replyText = $autoReplies[array_rand($autoReplies)];

        $replyMessage = [
            'id'     => $replyMsgId,
            'text'   => $replyText,
            'type'   => 'text',
            'out'    => false,
            'time'   => date('g:i A'),
            'status' => 'read',
        ];

        $responseData = [
            'success'     => true,
            'api_response'=> $apiResult,
            'data'        => [
                'message' => $outgoingMessage,
                'reply'   => $replyMessage,
            ]
        ];

        if (is_array($apiResult)) {
            if (isset($apiResult['brand'])) {
                $responseData['brand'] = $apiResult['brand'];
            }
            if (isset($apiResult['name'])) {
                $responseData['name'] = $apiResult['name'];
            }
        }

        return response()->json($responseData);
    }
}
