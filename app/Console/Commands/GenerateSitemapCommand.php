<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapService;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate physical static sitemap.xml in public directory for Google Search Console';

    public function handle(): int
    {
        $path = SitemapService::generate();
        $this->info("Physical sitemap successfully generated at: {$path}");

        return Command::SUCCESS;
    }
}
