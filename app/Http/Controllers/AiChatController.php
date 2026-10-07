<?php

namespace App\Http\Controllers;

use App\Services\BusinessContextBuilder;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function index()
    {
        return view('ai.chat');
    }

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
        ]);

        $businessContext = $contextBuilder->build();

        $answer = $geminiService->ask(
            $validated['question'],
            $businessContext
        );

        return response()->json([
            'success' => true,
            'question' => $validated['question'],
            'answer' => $answer,
        ]);
    }
}
