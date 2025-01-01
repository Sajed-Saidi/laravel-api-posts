<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Auth;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    use ApiResponseTrait;

    public function saveProfile(Request $request)
    {
        $user = Auth::user();
        $profileExists = $user->profile()->exists();

        // Validation rules
        $request->validate([
            'bio' => 'required|string|max:255',
            'image' => $profileExists ? 'nullable|image' : 'required|image',
        ]);
        try {
            $data = ['bio' => $request->bio];

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                if ($profileExists) {
                    $this->deleteImage($user->profile->image); // Delete old image if updating
                }
                $data['image'] = $this->storeImage($request->file('image'));
            }

            if ($profileExists) {
                // Update profile
                $user->profile()->update($data);
                $message = 'Profile updated successfully.';
            } else {
                // Create profile
                $user->profile()->create($data);
                $message = 'Profile created successfully.';
            }

            return $this->success(new UserResource(User::with('profile')->findOrFail(auth()->id())), $message);
        } catch (\Exception $e) {
            $errorMessage = $profileExists ? 'Profile update failed.' : 'Profile creation failed.';
            return $this->error(null, $errorMessage, 500);
        }
    }
}
