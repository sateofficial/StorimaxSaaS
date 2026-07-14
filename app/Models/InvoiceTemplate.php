<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceTemplate extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'content',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the active template, or create default from file if none exists.
     */
    public static function getActive(): self
    {
        $template = static::where('is_active', true)->first();

        if (!$template) {
            // Seed from file if DB is empty
            $filePath = resource_path('views/admin/invoices/template.md');
            $content = file_exists($filePath) ? file_get_contents($filePath) : '';

            $template = static::create([
                'name'      => 'Default',
                'content'   => $content,
                'is_active' => true,
                'notes'     => 'Template default dari file system.',
            ]);
        }

        return $template;
    }

    /**
     * Activate this template and deactivate others.
     */
    public function activate(): void
    {
        static::where('is_active', true)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }
}
