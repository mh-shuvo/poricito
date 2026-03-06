<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemorialController extends Controller
{
    /**
     * Show all memorials (pending, approved, rejected).
     */
    public function index(Request $request): View
    {
        $query = Memorial::with(['user', 'ward.union.thana.district', 'photos']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $memorials = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.memorials.index', compact('memorials'));
    }

    /**
     * Show a specific memorial.
     */
    public function show(Memorial $memorial): View
    {
        $memorial->load(['user', 'ward.union.thana.district', 'photos']);
        return view('admin.memorials.show', compact('memorial'));
    }

    /**
     * Approve a memorial.
     */
    public function approve(Memorial $memorial): RedirectResponse
    {
        $memorial->update([
            'status' => 'approved',
            'updated_by_admin_id' => auth()->id(),
            'admin_notes' => null,
        ]);

        return redirect()->route('admin.memorials.show', $memorial)
            ->with('success', 'Memorial approved successfully.');
    }

    /**
     * Reject a memorial with notes.
     */
    public function reject(Memorial $memorial, Request $request): RedirectResponse
    {
        $request->validate([
            'admin_notes' => 'required|string|min:10',
        ]);

        $memorial->update([
            'status' => 'rejected',
            'updated_by_admin_id' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.memorials.show', $memorial)
            ->with('success', 'Memorial rejected. Contributor notified to revise.');
    }

    /**
     * Edit a memorial (admin can edit any).
     */
    public function edit(Memorial $memorial): View
    {
        $memorial->load(['ward.union.thana.district']);
        return view('admin.memorials.edit', compact('memorial'));
    }

    /**
     * Update a memorial.
     */
    public function update(Memorial $memorial, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date',
            'bio' => 'nullable|string',
            'ward_id' => 'required|exists:wards,id',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ]);

        $memorial->update([
            'name' => $validated['name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'date_of_death' => $validated['date_of_death'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'ward_id' => $validated['ward_id'],
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $currentCount = $memorial->photos()->count();
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('memorials/' . $memorial->id, 'public');
                $memorial->photos()->create([
                    'photo_path' => $path,
                    'display_order' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.memorials.show', $memorial)
            ->with('success', 'Memorial updated successfully.');
    }

    /**
     * Delete a memorial (soft delete).
     */
    public function destroy(Memorial $memorial): RedirectResponse
    {
        $memorial->delete();

        return redirect()->route('admin.memorials.index')
            ->with('success', 'Memorial deleted successfully.');
    }

    /**
     * Delete a photo from memorial.
     */
    public function deletePhoto($photoId)
    {
        $photo = \App\Models\MemorialPhoto::findOrFail($photoId);
        \Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return response()->json(['success' => true]);
    }
}
