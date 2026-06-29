<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Enums\JobPriority;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['client', 'creator', 'teams'])
            ->latest()
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::with('user')->get();
        $crews   = User::where('role', UserRole::CREW)
                       ->where('is_active', true)
                       ->orderBy('name')
                       ->get();

        return view('admin.projects.create', compact('clients', 'crews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'name'        => 'required|string|max:200',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        // Generate kode project otomatis: STX-2026-001
        $year  = date('Y');
        $count = Project::whereYear('created_at', $year)->count() + 1;
        $code  = 'STX-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $project = Project::create([
            'client_id'   => $request->client_id,
            'created_by'  => auth()->id(),
            'name'        => $request->name,
            'code'        => $code,
            'category'    => $request->category,
            'description' => $request->description,
            'status'      => ProjectStatus::DRAFT,
            'priority'    => $request->priority,
            'deadline'    => $request->deadline,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Project berhasil dibuat. Silakan tambahkan tim.');
    }

    public function show(Project $project)
    {
        $project->load([
            'client.user',
            'creator',
            'teams.pic',
            'teams.members.user.department',
            'jobs.assignee',
        ]);

        $crews = User::where('role', UserRole::CREW)
                     ->where('is_active', true)
                     ->orderBy('name')
                     ->get();

        return view('admin.projects.show', compact('project', 'crews'));
    }

    public function edit(Project $project)
    {
        $clients = Client::with('user')->get();
        return view('admin.projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'name'        => 'required|string|max:200',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high,urgent',
            'deadline'    => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $project->update($request->only([
            'client_id', 'name', 'category',
            'description', 'priority', 'deadline', 'notes',
        ]));

        return redirect()->route('admin.projects.show', $project)
            ->with('success', 'Project berhasil diupdate.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function updateStatus(Request $request, Project $project)
    {
        $request->validate([
            'status' => 'required|in:draft,active,review,done,archived',
        ]);

        $project->update(['status' => $request->status]);

        return back()->with('success', 'Status project berhasil diupdate.');
    }
}