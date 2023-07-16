<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class VideoController extends Controller
{
    public function store(VideoStoreRequest $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $videoId = $this->extractVideoId($request->link);

        Video::create([
            'link' => $request->link,
            'video_id' => $videoId
        ]);

        return response()->json(['message' => 'Video saved successfully'], 201);
    }

    private function extractVideoId(string $link): string
    {
        $pattern = '/(?:youtube\.com\/(?:watch\?.*v=|embed\/|v\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i';
        preg_match($pattern, $link, $matches);

        if (count($matches) === 2) {
            return $matches[1];
        }

        throw ValidationException::withMessages(['link' => 'Invalid YouTube video link']);
    }

}
