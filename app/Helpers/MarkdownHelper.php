<?php

namespace App\Helpers;

/**
 * MarkdownHelper — Lightweight Markdown parser without external dependencies.
 *
 * Supports: headings, bold, italic, tables, horizontal rules, paragraphs,
 * ordered/unordered lists, line breaks, and {{variable}} placeholders.
 *
 * Usage:
 *   $html = MarkdownHelper::parse(file_get_contents('template.md'), [
 *       'client_name' => 'John Doe',
 *       'total'       => 'Rp 5.000.000',
 *   ]);
 */
class MarkdownHelper
{
    /**
     * Parse a Markdown string into HTML, replacing placeholders.
     *
     * @param string $markdown  Raw Markdown content.
     * @param array  $variables Key-value pairs for {{key}} replacement.
     * @return string Rendered HTML.
     */
    public static function parse(string $markdown, array $variables = []): string
    {
        // 1. Replace variable placeholders first (before markdown conversion)
        $html = static::replaceVariables($markdown, $variables);

        // 2. Convert Markdown syntax to HTML
        $html = static::convertMarkdown($html);

        return $html;
    }

    /**
     * Replace {{variable}} placeholders with actual values.
     * Escapes HTML special chars to prevent XSS.
     */
    public static function replaceVariables(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $text = str_replace(
                '{{' . $key . '}}',
                htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'),
                $text
            );
        }
        return $text;
    }

    /**
     * Convert Markdown syntax to HTML.
     */
    protected static function convertMarkdown(string $text): string
    {
        // Unify line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Escape remaining HTML (except pre-rendered HTML in <!--html-->...<!--/html--> blocks)
        $text = static::protectHtmlBlocks($text);

        // Split into blocks (double newlines)
        $blocks = preg_split("/\n\n+/", $text);
        $html = '';

        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') continue;

            $html .= static::parseBlock($block);
        }

        // Restore protected HTML blocks
        $html = static::restoreHtmlBlocks($html);

        return $html;
    }

    protected static array $htmlBlocks = [];

    protected static function protectHtmlBlocks(string $text): string
    {
        static::$htmlBlocks = [];
        return preg_replace_callback('/<!--html-->(.*?)<!--\/html-->/s', function ($m) {
            static::$htmlBlocks[] = $m[1];
            return '<!--HTMLBLOCK:' . (count(static::$htmlBlocks) - 1) . '-->';
        }, $text);
    }

    protected static function restoreHtmlBlocks(string $text): string
    {
        return preg_replace_callback('/<!--HTMLBLOCK:(\d+)-->/', function ($m) {
            return static::$htmlBlocks[(int) $m[1]] ?? '';
        }, $text);
    }

    protected static function parseBlock(string $block): string
    {
        // HTML block (protected)
        if (str_starts_with($block, '<!--HTMLBLOCK:')) {
            return $block . "\n";
        }

        // Heading level 1
        if (preg_match('/^##### (.+)$/m', $block, $m)) {
            return '<h5>' . static::inlineMarkdown($m[1]) . "</h5>\n";
        }
        if (preg_match('/^#### (.+)$/m', $block, $m)) {
            return '<h4>' . static::inlineMarkdown($m[1]) . "</h4>\n";
        }
        if (preg_match('/^### (.+)$/m', $block, $m)) {
            return '<h3>' . static::inlineMarkdown($m[1]) . "</h3>\n";
        }
        if (preg_match('/^## (.+)$/m', $block, $m)) {
            return '<h2>' . static::inlineMarkdown($m[1]) . "</h2>\n";
        }
        if (preg_match('/^# (.+)$/m', $block, $m)) {
            return '<h1>' . static::inlineMarkdown($m[1]) . "</h1>\n";
        }

        // Horizontal rule
        if (preg_match('/^---+\s*$/', $block)) {
            return "<hr>\n";
        }

        // Table
        if (str_contains($block, '|') && preg_match('/^\|.+\|$/', trim($block), $m)) {
            return static::parseTable($block);
        }

        // Unordered list
        if (preg_match('/^[-*+]\s/', $block)) {
            return static::parseUnorderedList($block);
        }

        // Ordered list
        if (preg_match('/^\d+\.\s/', $block)) {
            return static::parseOrderedList($block);
        }

        // Paragraph
        return '<p>' . static::inlineMarkdown($block) . "</p>\n";
    }

    protected static function inlineMarkdown(string $text): string
    {
        // Bold + Italic ***text***
        $text = preg_replace('/\*\*\*(.+?)\*\*\*/', '<strong><em>$1</em></strong>', $text);
        // Bold **text**
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        // Italic *text*
        $text = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
        // Line break within paragraph
        $text = nl2br($text, false);

        return $text;
    }

    protected static function parseTable(string $block): string
    {
        $lines = explode("\n", trim($block));
        $html = "<table>\n";
        $isHeader = true;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || !str_starts_with($line, '|')) continue;
            if (preg_match('/^\|[\s:-]+\|$/', $line)) continue; // skip separator

            $cells = explode('|', trim($line, '|'));
            $tag = $isHeader ? 'th' : 'td';

            $html .= '    <tr>';
            foreach ($cells as $cell) {
                $cell = trim($cell);
                $html .= "<{$tag}>" . static::inlineMarkdown($cell) . "</{$tag}>";
            }
            $html .= "</tr>\n";

            $isHeader = false;
        }

        $html .= "</table>\n";
        return $html;
    }

    protected static function parseUnorderedList(string $block): string
    {
        $lines = explode("\n", trim($block));
        $html = "<ul>\n";
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^[-*+]\s+(.+)$/', $line, $m)) {
                $html .= '    <li>' . static::inlineMarkdown($m[1]) . "</li>\n";
            }
        }
        $html .= "</ul>\n";
        return $html;
    }

    protected static function parseOrderedList(string $block): string
    {
        $lines = explode("\n", trim($block));
        $html = "<ol>\n";
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^\d+\.\s+(.+)$/', $line, $m)) {
                $html .= '    <li>' . static::inlineMarkdown($m[1]) . "</li>\n";
            }
        }
        $html .= "</ol>\n";
        return $html;
    }
}
