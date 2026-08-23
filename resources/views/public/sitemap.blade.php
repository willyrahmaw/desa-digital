{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/profil') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/layanan') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/berita') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
@if(isset($beritas))
@foreach($beritas as $berita)
    <url>
        <loc>{{ url('/berita/' . ($berita->slug ?? $berita->id)) }}</loc>
        <lastmod>{{ optional($berita->updated_at)->toAtomString() ?? date('c') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@endif
    <url>
        <loc>{{ url('/agenda') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/umkm') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/galeri') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/pengaduan') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
</urlset>
