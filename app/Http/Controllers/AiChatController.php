<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\BusinessContextBuilder;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class AiChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Chat Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('ai.chat');
    }


    /*
    |--------------------------------------------------------------------------
    | Send Message
    |--------------------------------------------------------------------------
    */

    public function ask(
        Request $request,
        BusinessContextBuilder $contextBuilder,
        GeminiService $geminiService
    ) {
        $validated = $request->validate([
            'question' => [
                'required',
                'string',
                'max:2000',
            ],

            'conversation_id' => [
                'nullable',
                'integer',
                'exists:conversations,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Existing Conversation
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['conversation_id'])) {

            $conversation = Conversation::findOrFail(
                $validated['conversation_id']
            );
        } else {

            /*
            |--------------------------------------------------------------------------
            | Create New Conversation
            |--------------------------------------------------------------------------
            */

            $conversation = Conversation::create([
                'title' => Str::limit(
                    $validated['question'],
                    100
                ),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save User Message
        |--------------------------------------------------------------------------
        */

        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'message' => $validated['question'],
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | Build Business Context
            |--------------------------------------------------------------------------
            */

            $businessContext =
                $contextBuilder->build();


            /*
            |--------------------------------------------------------------------------
            | Ask Gemini
            |--------------------------------------------------------------------------
            */

            $answer = $geminiService->ask(
                $validated['question'],
                $businessContext
            );


            /*
            |--------------------------------------------------------------------------
            | Save AI Response
            |--------------------------------------------------------------------------
            */

            $assistantMessage =
                $conversation->messages()->create([
                    'role' => 'assistant',
                    'message' => $answer,
                ]);


            /*
            |--------------------------------------------------------------------------
            | Update Conversation
            |--------------------------------------------------------------------------
            */

            $conversation->touch();


            /*
            |--------------------------------------------------------------------------
            | Return Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'conversation_id' =>
                $conversation->id,

                'user_message' => [
                    'id' =>
                    $userMessage->id,

                    'role' =>
                    $userMessage->role,

                    'message' =>
                    $userMessage->message,

                    'created_at' =>
                    $userMessage->created_at,
                ],

                'assistant_message' => [
                    'id' =>
                    $assistantMessage->id,

                    'role' =>
                    $assistantMessage->role,

                    'message' =>
                    $assistantMessage->message,

                    'created_at' =>
                    $assistantMessage->created_at,
                ],
            ]);
        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | AI Error
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => false,

                'message' =>
                'Unable to get AI response.',

                'error' =>
                $e->getMessage(),

                'conversation_id' =>
                $conversation->id,
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Retrieve Conversations
    |--------------------------------------------------------------------------
    */

    public function conversations(Request $request)
    {
        $perPage = 10;

        $conversations = Conversation::query()
            ->withCount('messages')
            ->latest('updated_at')
            ->paginate($perPage);


        return response()->json([
            'success' => true,

            'conversations' =>
            $conversations,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Retrieve Conversation Messages
    |--------------------------------------------------------------------------
    */

    public function messages(
        Conversation $conversation
    ) {

        /*
        |--------------------------------------------------------------------------
        | Latest Messages First
        |--------------------------------------------------------------------------
        |
        | Page 1 = latest 20 messages
        | Page 2 = older messages
        |
        */

        $messages = $conversation
            ->messages()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);


        return response()->json([
            'success' => true,

            'conversation_id' =>
            $conversation->id,

            'messages' =>
            $messages,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Conversation
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Conversation $conversation
    ) {

        /*
        |--------------------------------------------------------------------------
        | Delete Conversation
        |--------------------------------------------------------------------------
        |
        | Because chat_messages has cascadeOnDelete(),
        | all messages belonging to this conversation
        | will also be deleted automatically.
        |
        */

        $conversation->delete();


        return response()->json([
            'success' => true,

            'message' =>
            'Conversation deleted successfully.',
        ]);
    }
}
