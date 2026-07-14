<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceTemplate;
use Illuminate\Http\Request;

class InvoiceTemplateController extends Controller
{
    public function index()
    {
        $templates = InvoiceTemplate::latest()->get();
        return view('admin.invoices.templates.index', compact('templates'));
    }

    public function create()
    {
        $template = new InvoiceTemplate();
        $template->content = file_get_contents(resource_path('views/admin/invoices/template.md'));
        return view('admin.invoices.templates.edit', compact('template'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'content' => 'nullable|string',
            'notes'   => 'nullable|string|max:255',
        ]);

        InvoiceTemplate::create([
            'name'      => $request->name,
            'content'   => $request->content,
            'is_active' => false,
            'notes'     => $request->notes,
        ]);

        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Template berhasil dibuat.');
    }

    public function edit(InvoiceTemplate $template)
    {
        return view('admin.invoices.templates.edit', compact('template'));
    }

    public function update(Request $request, InvoiceTemplate $template)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'content' => 'nullable|string',
            'notes'   => 'nullable|string|max:255',
        ]);

        $template->update([
            'name'    => $request->name,
            'content' => $request->content,
            'notes'   => $request->notes,
        ]);

        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Template berhasil diupdate.');
    }

    public function activate(InvoiceTemplate $template)
    {
        $template->activate();
        return back()->with('success', 'Template "' . $template->name . '" sekarang aktif.');
    }

    public function destroy(InvoiceTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.invoices.templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }
}
