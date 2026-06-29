<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTeam;
use App\Models\ProjectTeamMember;
use Illuminate\Http\Request;

class ProjectTeamController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'team_name'   => 'required|string|max:100',
            'pic_user_id' => 'nullable|exists:users,id',
        ]);

        $project->teams()->create([
            'team_name'   => $request->team_name,
            'pic_user_id' => $request->pic_user_id ?: null,
        ]);

        return back()->with('success', 'Tim berhasil ditambahkan.');
    }

    public function destroy(Project $project, ProjectTeam $team)
    {
        $team->delete();
        return back()->with('success', 'Tim berhasil dihapus.');
    }

    public function addMember(Request $request, Project $project, ProjectTeam $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Cek duplikat
        $exists = $team->members()->where('user_id', $request->user_id)->exists();
        if ($exists) {
            return back()->with('error', 'Crew sudah ada di tim ini.');
        }

        $team->members()->create(['user_id' => $request->user_id]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function removeMember(Project $project, ProjectTeam $team, ProjectTeamMember $member)
    {
        $member->delete();
        return back()->with('success', 'Anggota berhasil dihapus dari tim.');
    }
}