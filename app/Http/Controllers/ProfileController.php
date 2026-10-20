<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\TenantStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $clientId = $user->client_id;

        $usedBytes = TenantStorageService::getTenantStorageUsageBytes($clientId);
        $client = $user->client;
        $limitMb = ($client && $client->storage_limit_mb) ? (int) $client->storage_limit_mb : (int) env('TENANT_STORAGE_LIMIT_MB', 1024);
        $limitBytes = $limitMb * 1024 * 1024;
        $percentage = $limitBytes > 0 ? min(100, round(($usedBytes / $limitBytes) * 100, 1)) : 0;

        $storageInfo = [
            'used_bytes' => $usedBytes,
            'limit_bytes' => $limitBytes,
            'used_formatted' => TenantStorageService::formatBytes($usedBytes),
            'limit_formatted' => TenantStorageService::formatBytes($limitBytes),
            'percentage' => $percentage,
            'is_tenant' => !empty($clientId),
        ];

        return view('profile.edit', [
            'user' => $user,
            'storageInfo' => $storageInfo,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
