<?php
namespace App\Http\Controllers;

        use App\Services\AI\ChatService;
        use Illuminate\Http\JsonResponse;
        use Illuminate\Http\Request;

        class AIChatController extends Controller
        {
            public function __construct(protected ChatService $chatService) {}

            /**
             * POST /nexora-ai/chat  { "message": "...", "session_id": "..." (optional) }
             *
             * A plain JSON endpoint so the chatbot can be tested with curl/Postman
             * before Package 5 adds the chat widget UI to ai-insights.blade.php —
             * at that point the widget calls this exact same endpoint, nothing
             * here needs to change.
             */
            public function respond(Request $request): JsonResponse
{
    try {

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $sessionId = $validated['session_id'] ?? $request->session()->getId();

        $result = $this->chatService->ask(
            sessionId: $sessionId,
            message: $validated['message'],
            userId: $request->user()?->id,
        );

        return response()->json($result);

    } catch (\Throwable $e) {

        return response()->json([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);

    }
}
        }
