<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::with(['project.client', 'creator', 'tags'])
            ->latest()
            ->get();

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        $projects = Project::with('client')->latest()->get();
        return view('admin.portfolios.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category'       => 'nullable|string|max:100',
            'is_public'      => 'boolean',
            'tags'           => 'nullable|string',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('portfolios', 'public');
        }

        $portfolio = Portfolio::create([
            'project_id'     => $request->project_id,
            'created_by'     => auth()->id(),
            'title'          => $request->title,
            'description'    => $request->description,
            'thumbnail_path' => $thumbnailPath,
            'category'       => $request->category,
            'is_public'      => $request->boolean('is_public'),
            'published_at'   => $request->boolean('is_public') ? now() : null,
        ]);

        // Simpan tags
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            foreach ($tags as $tag) {
                if (!empty($tag)) {
                    $portfolio->tags()->create(['tag' => $tag]);
                }
            }
        }

        // Notifikasi ke admin lain jika langsung dipublikasikan
        if ($request->boolean('is_public')) {
            NotificationHelper::notifyAdmins(
                type: 'portfolio_published',
                title: 'Portofolio Baru: ' . $portfolio->title,
                message: "Portofolio baru telah dipublikasikan untuk project {$portfolio->project->name}.",
                data: ['portfolio_id' => $portfolio->id],
                actionUrl: route('admin.portfolios.show', $portfolio),
            );
        }

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function show(Portfolio $portfolio)
    {
        $portfolio->load(['project.client', 'creator', 'tags']);
        return view('admin.portfolios.show', compact('portfolio'));
    }

    public function edit(Portfolio $portfolio)
    {
        $projects = Project::with('client')->latest()->get();
        $tags = $portfolio->tags->pluck('tag')->implode(', ');
        return view('admin.portfolios.edit', compact('portfolio', 'projects', 'tags'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'project_id'     => 'required|exists:projects,id',
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'thumbnail'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category'       => 'nullable|string|max:100',
            'is_public'      => 'boolean',
            'tags'           => 'nullable|string',
        ]);

        $data = [
            'project_id'  => $request->project_id,
            'title'       => $request->title,
            'description' => $request->description,
            'category'    => $request->category,
            'is_public'   => $request->boolean('is_public'),
        ];

        if ($request->boolean('is_public') && !$portfolio->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama
            if ($portfolio->thumbnail_path) {
                Storage::disk('public')->delete($portfolio->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('portfolios', 'public');
        }

        $portfolio->update($data);

        // Update tags: hapus semua, bikin ulang
        $portfolio->tags()->delete();
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            foreach ($tags as $tag) {
                if (!empty($tag)) {
                    $portfolio->tags()->create(['tag' => $tag]);
                }
            }
        }

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portofolio berhasil diupdate.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->thumbnail_path) {
            Storage::disk('public')->delete($portfolio->thumbnail_path);
        }

        $portfolio->tags()->delete();
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portofolio berhasil dihapus.');
    }

    public function togglePublic(Portfolio $portfolio)
    {
        $newPublic = !$portfolio->is_public;

        $portfolio->update([
            'is_public'     => $newPublic,
            'published_at'  => $newPublic ? now() : $portfolio->published_at,
        ]);

        // Notifikasi jika dipublikasikan
        if ($newPublic) {
            NotificationHelper::notifyAdmins(
                type: 'portfolio_published',
                title: 'Portofolio Dipublikasikan: ' . $portfolio->title,
                message: "Portofolio \"{$portfolio->title}\" telah dipublikasikan.",
                data: ['portfolio_id' => $portfolio->id],
                actionUrl: route('admin.portfolios.show', $portfolio),
            );
        }

        $status = $newPublic ? 'dipublikasikan' : 'ditutup';
        return back()->with('success', "Portofolio berhasil {$status}.");
    }
}
