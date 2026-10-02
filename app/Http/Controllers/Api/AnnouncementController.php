<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(): JsonResponse
    {
        $announcements = Announcement::orderByDesc('published_at')->get();

        return response()->json([
            'success' => true,
            'data' => $announcements->map(fn ($item) => $this->formatAnnouncement($item)),
        ]);
    }

    public function show(Announcement $announcement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->formatAnnouncement($announcement),
        ]);
    }

    public function like(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_initials' => 'required|string|max:10',
        ]);

        $like = Like::create([
            'announcement_id' => $announcement->id,
            'user_name' => $request->user_name,
            'user_initials' => $request->user_initials,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $like,
            'likes_count' => $announcement->likes()->count(),
        ]);
    }

    public function comment(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_initials' => 'required|string|max:10',
            'text' => 'required|string|max:2000',
        ]);

        $comment = Comment::create([
            'announcement_id' => $announcement->id,
            'user_name' => $request->user_name,
            'user_initials' => $request->user_initials,
            'text' => $request->text,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $comment,
        ]);
    }

    public function comments(Announcement $announcement): JsonResponse
    {
        $comments = $announcement->comments()
            ->whereNull('parent_id')
            ->withCount('replies')
            ->withCount('likes')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    public function replies(Announcement $announcement, Comment $comment): JsonResponse
    {
        $replies = $comment->replies()
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $replies,
        ]);
    }

    public function share(Announcement $announcement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Share recorded',
        ]);
    }

    public function likeComment(Request $request, Announcement $announcement, Comment $comment): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_initials' => 'required|string|max:10',
        ]);

        $like = CommentLike::create([
            'comment_id' => $comment->id,
            'user_name' => $request->user_name,
            'user_initials' => $request->user_initials,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $like,
            'likes_count' => $comment->likes()->count(),
        ]);
    }

    public function replyComment(Request $request, Announcement $announcement, Comment $comment): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_initials' => 'required|string|max:10',
            'text' => 'required|string|max:2000',
        ]);

        $reply = Comment::create([
            'announcement_id' => $announcement->id,
            'parent_id' => $comment->id,
            'user_name' => $request->user_name,
            'user_initials' => $request->user_initials,
            'text' => $request->text,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $reply,
        ]);
    }

    private function formatAnnouncement(Announcement $announcement): array
    {
        return [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'message' => $announcement->message,
            'author_name' => $announcement->author_name,
            'author_initials' => $announcement->author_initials,
            'video_url' => $announcement->video_url,
            'video_duration' => $announcement->video_duration,
            'is_new' => $announcement->is_new,
            'published_at' => $announcement->published_at?->diffForHumans(),
            'likes_count' => $announcement->likes()->count(),
            'comments_count' => $announcement->comments()->whereNull('parent_id')->count(),
        ];
    }
}
