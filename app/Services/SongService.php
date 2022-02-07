<?php

namespace App\Services;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class SongService
{
    /**
     * @param array $data
     * @return \App\Models\Song
     *
     * @throws \Illuminate\Database\QueryException
     */
    public function store(array $data)
    {
        if (isset($data['preview_image']) && $data['preview_image'] instanceof UploadedFile) {
            $data['preview_image'] = $this->savePreviewImage($data['preview_image']);
        } else {
            $data['preview_image'] = 'images/songs/default.png';
        }

        try {
            $song = Song::create($data);
        } catch (QueryException $e) {
            throw $e;
        }

        return $song;
    }

    /**
     * @param \App\Models\Song $song
     * @param array $data
     * @return \App\Models\Song
     *
     * @throws \Illuminate\Database\QueryException
     */
    public function update(Song $song, array $data)
    {
        if (isset($data['preview_image']) && $data['preview_image'] instanceof UploadedFile) {
            $data['preview_image'] = $this->savePreviewImage($data['preview_image']);
        } else {
            $data['preview_image'] = 'images/songs/default.png';
        }

        try {
            $song->update($data);
        } catch (QueryException $e) {
            throw $e;
        }

        return $song;
    }

    public function savePreviewImage(UploadedFile $image)
    {
        $path = 'songs/' . Carbon::today()->format('Y/m/d');
        $disk = 'public';

        if (!Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->makeDirectory($path);
        }

        $path = Storage::disk($disk)->putFile($path, $image);

        return $path;
    }

    public function getCharts(Request $request)
    {
        $currentDate = now();
        $date = $currentDate->subDay();

        if ($request->get('filter', false)) {
            if ($request->get('period') == 'week') {
                $date = $currentDate->subWeek();
            } else if ($request->get('period') == 'month') {
                $date = $currentDate->subMonth();
            }
            $songs = Song::where('genre_id', $request->get('genre', 1))
                ->whereDate('created_at', '>=', $date)
                ->limit(10)
                ->get();
        } else {
            $songs = Song::whereDate('created_at', '>=', $date)->limit(10)->get();
        }

        return $songs;
    }
}
