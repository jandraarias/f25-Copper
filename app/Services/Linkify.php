<?php

namespace App\Services;

class Linkify
{
    /**
     * Converts plain URLs in text into clickable, shortened hyperlinks (safe and escaped).
     */
    public static function linkify(?string $text): string
    {
        if (empty($text)) return '';

        // Escape all HTML entities first for safety
        $escaped = e($text);

        // Convert URLs to clickable links with shortened display text and tooltips
        return preg_replace_callback(
            '/(https?:\/\/[^\s<]+)(?<![.,?!])/', // match URLs but avoid trailing punctuation
            function ($matches) {
                $url = $matches[1];

                // Normalize and shorten display text
                $display = parse_url($url, PHP_URL_HOST) ?? $url;

                // Optionally append the first path segment (helps contextually)
                $path = parse_url($url, PHP_URL_PATH);
                if ($path && $path !== '/') {
                    $segments = explode('/', trim($path, '/'));
                    if (!empty($segments[0])) {
                        $display .= '/' . $segments[0];
                    }
                }

                // Final clickable link with tooltip and consistent styling
                return sprintf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer" title="%s" class="text-blue-600 dark:text-blue-400 hover:underline break-words">%s</a>',
                    $url,
                    $url,
                    $display
                );
            },
            $escaped
        );
    }
}
