<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Str;

trait ApiResponseTrait
{

    public function success($data, string $message = null, int $status = 200)
    {
        $message = $message ?? __('messages.request_successful');

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public function error($data = null, string $message = null, int $status = 400)
    {
        $message = $message ?? __('messages.error_occurred');

        // if (config('app.env') !== 'production') {
        //     Log::error('API Error', [
        //         'status' => $status,
        //         'message' => $message,
        //         'data' => $data,
        //     ]);
        // }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data ?? [], // Default to an empty array if no data is provided
        ], $status);
    }

    public function storeImage($image)
    {
        $uniqueName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('images', $uniqueName, 'public');
        return $path;
    }

    public function deleteImage($image)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }
    }
}
