<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BenchmarkController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'server' => env('SERVER_TYPE', 'unknown'),
            'php' => PHP_VERSION,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    public function users(): JsonResponse
    {
        $users = User::withCount('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($users);
    }

    public function user(User $user): JsonResponse
    {
        $user->load(['posts' => fn ($q) => $q->orderBy('views', 'desc')->limit(10)]);

        return response()->json($user);
    }

    public function posts(): JsonResponse
    {
        $posts = Post::with('user:id,name,email')
            ->orderBy('views', 'desc')
            ->paginate(15);

        return response()->json($posts);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'avg_views' => round(Post::avg('views') ?? 0, 2),
            'top_posts' => Post::with('user:id,name')
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get(),
            'most_active_authors' => User::withCount('posts')
                ->orderBy('posts_count', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'email']),
        ]);
    }
}
