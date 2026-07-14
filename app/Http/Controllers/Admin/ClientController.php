<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('user')->latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contact_name' => 'required|string|max:150',
            'email'        => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password'     => 'required|min:8',
            'company_name' => 'nullable|string|max:150',
            'phone'        => 'nullable|string|max:20',
            'instagram'    => 'nullable|string|max:100',
            'address'      => 'nullable|string',
        ]);

        $user = User::create([
            'name'      => $request->contact_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => UserRole::CLIENT,
            'is_active' => true,
        ]);

        Client::create([
            'user_id'      => $user->id,
            'contact_name' => $request->contact_name,
            'company_name' => $request->company_name,
            'phone'        => $request->phone,
            'instagram'    => $request->instagram,
            'address'      => $request->address,
        ]);

        return back()->with('success', 'Client berhasil ditambahkan.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $rules = [
            'contact_name' => 'required|string|max:150',
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($client->user_id)->whereNull('deleted_at')],
            'password'     => 'nullable|min:8',
            'company_name' => 'nullable|string|max:150',
            'phone'        => 'nullable|string|max:20',
            'instagram'    => 'nullable|string|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ];

        $request->validate($rules);

        $client->update($request->only([
            'contact_name', 'company_name',
            'phone', 'instagram', 'address', 'notes',
        ]));

        // Update akun login
        $userData = [
            'name'  => $request->contact_name,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $client->user->update($userData);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client berhasil diupdate.');
    }

    public function destroy(Client $client)
    {
        // Cascade delete: project (→ jobs → invoices → portfolios) + user login
        $client->delete();

        return back()->with('success', 'Client beserta seluruh data terkait berhasil dihapus.');
    }
}