<?php

namespace App\Services;

use App\Models\Berita;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Generate a physical static XML sitemap file in public/sitemap.xml.
     * Compliant with Google Search Console (GSC) sitemap schema.
     */
    public static function generate(): string
    {
        $baseUrl = rtrim(config('app.url', url('/')), '/');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        // Static core public pages
        $staticPages = [
            ['path' => '/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['path' => '/profil', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['path' => '/layanan', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/berita', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['path' => '/agenda', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/umkm', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['path' => '/galeri', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['path' => '/pengaduan', 'changefreq' => 'daily', 'priority' => '0.9'],
        ];

        $nowAtom = now()->tz('UTC')->toAtomString();

        foreach ($staticPages as $page) {
            $loc = $page['path'] === '/' ? $baseUrl : "{$baseUrl}{$page['path']}";
            $xml .= '    <url>' . PHP_EOL;
            $xml .= "        <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>" . PHP_EOL;
            $xml .= "        <lastmod>{$nowAtom}</lastmod>" . PHP_EOL;
            $xml .= "        <changefreq>{$page['changefreq']}</changefreq>" . PHP_EOL;
            $xml .= "        <priority>{$page['priority']}</priority>" . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        // Dynamic news & articles
        try {
            $beritas = Berita::where('status', 'Publikasi')
                ->orWhereNull('status')
                ->latest('updated_at')
                ->get();

            foreach ($beritas as $berita) {
                $slug = $berita->slug ?: $berita->id;
                $loc = "{$baseUrl}/berita/{$slug}";
                $lastmod = $berita->updated_at ? $berita->updated_at->tz('UTC')->toAtomString() : $nowAtom;

                $xml .= '    <url>' . PHP_EOL;
                $xml .= "        <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>" . PHP_EOL;
                $xml .= "        <lastmod>{$lastmod}</lastmod>" . PHP_EOL;
                $xml .= "        <changefreq>weekly</changefreq>" . PHP_EOL;
                $xml .= "        <priority>0.7</priority>" . PHP_EOL;
                $xml .= '    </url>' . PHP_EOL;
            }
        } catch (\Exception $e) {
            // Silently fallback if table not migrated in isolated tests
        }

        $xml .= '</urlset>' . PHP_EOL;

        $targetPath = public_path('sitemap.xml');
        File::put($targetPath, $xml);

        return $targetPath;
    }
}
