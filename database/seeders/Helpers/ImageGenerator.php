<?php

namespace Database\Seeders\Helpers;

use Illuminate\Support\Facades\File;

class ImageGenerator
{
    /**
     * Get primary TTF font paths if available.
     */
    private static function getFonts(): array
    {
        $regular = null;
        $bold = null;

        $fontCandidatesRegular = [
            'C:/Windows/Fonts/segoeui.ttf',
            'C:/Windows/Fonts/arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];

        $fontCandidatesBold = [
            'C:/Windows/Fonts/segoeuib.ttf',
            'C:/Windows/Fonts/arialbd.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];

        foreach ($fontCandidatesRegular as $font) {
            if (file_exists($font)) {
                $regular = $font;
                break;
            }
        }

        foreach ($fontCandidatesBold as $font) {
            if (file_exists($font)) {
                $bold = $font;
                break;
            }
        }

        return ['regular' => $regular, 'bold' => $bold];
    }

    /**
     * Ensure destination directory exists.
     */
    private static function ensureDirectory(string $path): void
    {
        $dir = dirname($path);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }
    }

    /**
     * Get theme color schemes.
     */
    private static function getThemeColors(string $theme): array
    {
        $themes = [
            'blue' => [
                'bg_top' => [15, 23, 42],       // #0f172a slate 900
                'bg_bottom' => [30, 58, 138],   // #1e3a8a blue 900
                'accent' => [59, 130, 246],     // #3b82f6 blue 500
                'badge_bg' => [37, 99, 235],    // #2563eb blue 600
                'badge_text' => [239, 246, 255],// #eff6ff blue 50
                'text_main' => [255, 255, 255],
                'text_sub' => [191, 219, 254],  // #bfdbfe blue 200
            ],
            'emerald' => [
                'bg_top' => [6, 78, 59],        // #064e3b emerald 900
                'bg_bottom' => [15, 23, 42],    // #0f172a slate 900
                'accent' => [16, 185, 129],     // #10b981 emerald 500
                'badge_bg' => [5, 150, 105],    // #059669 emerald 600
                'badge_text' => [236, 253, 245],// #ecfdf5 emerald 50
                'text_main' => [255, 255, 255],
                'text_sub' => [167, 243, 208],  // #a7f3d0 emerald 200
            ],
            'amber' => [
                'bg_top' => [120, 53, 15],      // #78350f amber 900
                'bg_bottom' => [26, 36, 43],    // dark slate
                'accent' => [245, 158, 11],     // #f59e0b amber 500
                'badge_bg' => [217, 119, 6],    // #d97706 amber 600
                'badge_text' => [254, 243, 199],// #fef3c7 amber 100
                'text_main' => [255, 255, 255],
                'text_sub' => [253, 230, 138],  // #fde68a amber 200
            ],
            'purple' => [
                'bg_top' => [88, 28, 135],      // #581c87 purple 900
                'bg_bottom' => [15, 23, 42],
                'accent' => [168, 85, 247],     // #a855f7 purple 500
                'badge_bg' => [126, 34, 206],
                'badge_text' => [250, 245, 255],
                'text_main' => [255, 255, 255],
                'text_sub' => [233, 213, 255],
            ],
            'cyan' => [
                'bg_top' => [14, 116, 144],     // #0e7490 cyan 700
                'bg_bottom' => [15, 23, 42],
                'accent' => [6, 182, 212],      // #06b6d4 cyan 500
                'badge_bg' => [8, 145, 178],
                'badge_text' => [236, 254, 255],
                'text_main' => [255, 255, 255],
                'text_sub' => [165, 243, 252],
            ],
            'indigo' => [
                'bg_top' => [49, 46, 129],      // #312e81 indigo 900
                'bg_bottom' => [15, 23, 42],
                'accent' => [99, 102, 241],     // #6366f1 indigo 500
                'badge_bg' => [67, 56, 202],
                'badge_text' => [238, 242, 255],
                'text_main' => [255, 255, 255],
                'text_sub' => [199, 210, 254],
            ],
        ];

        return $themes[$theme] ?? $themes['blue'];
    }

    /**
     * Render gradient background on GD image.
     */
    private static function drawGradient($im, int $w, int $h, array $topRgb, array $bottomRgb): void
    {
        for ($y = 0; $y < $h; $y++) {
            $ratio = $y / (float)$h;
            $r = (int)($topRgb[0] * (1 - $ratio) + $bottomRgb[0] * $ratio);
            $g = (int)($topRgb[1] * (1 - $ratio) + $bottomRgb[1] * $ratio);
            $b = (int)($topRgb[2] * (1 - $ratio) + $bottomRgb[2] * $ratio);
            $color = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $w, $y, $color);
        }
    }

    /**
     * Draw modern geometric decorative lines & dots.
     */
    private static function drawDecorations($im, int $w, int $h, array $accentRgb): void
    {
        // Grid pattern
        $gridColor = imagecolorallocatealpha($im, $accentRgb[0], $accentRgb[1], $accentRgb[2], 115);
        $dotColor = imagecolorallocatealpha($im, 255, 255, 255, 110);
        $glowColor = imagecolorallocatealpha($im, $accentRgb[0], $accentRgb[1], $accentRgb[2], 120);

        // Ambient glow circle
        imagefilledellipse($im, (int)($w * 0.85), (int)($h * 0.25), (int)($w * 0.6), (int)($w * 0.6), $glowColor);
        imagefilledellipse($im, (int)($w * 0.15), (int)($h * 0.85), (int)($w * 0.4), (int)($w * 0.4), $glowColor);

        // Dot matrix
        for ($x = 40; $x < min(280, $w - 40); $x += 24) {
            for ($y = 40; $y < min(180, $h - 40); $y += 24) {
                imagefilledellipse($im, $x, $y, 3, 3, $dotColor);
            }
        }

        // Tech lines
        imagesetthickness($im, 2);
        imageline($im, $w - 240, 40, $w - 40, 40, $gridColor);
        imageline($im, $w - 40, 40, $w - 40, 140, $gridColor);
        imagesetthickness($im, 1);
    }

    /**
     * Draw text with word-wrap and shadow support.
     */
    private static function drawWrappedText($im, string $text, int $x, int $y, int $fontSize, array $textColor, ?string $fontFile, int $maxWidth, int $lineHeight, bool $shadow = true): int
    {
        $color = imagecolorallocate($im, $textColor[0], $textColor[1], $textColor[2]);
        $shadowColor = imagecolorallocatealpha($im, 0, 0, 0, 80);

        if (!$fontFile) {
            // GD built-in fallback
            $words = explode(' ', $text);
            $currentLine = '';
            $currentY = $y;

            foreach ($words as $word) {
                $testLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
                if (strlen($testLine) * 9 > $maxWidth) {
                    imagestring($im, 5, $x, $currentY, $currentLine, $color);
                    $currentY += 20;
                    $currentLine = $word;
                } else {
                    $currentLine = $testLine;
                }
            }
            if ($currentLine !== '') {
                imagestring($im, 5, $x, $currentY, $currentLine, $color);
                $currentY += 20;
            }
            return $currentY;
        }

        $words = explode(' ', $text);
        $currentLine = '';
        $currentY = $y;

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            $box = imagettfbbox($fontSize, 0, $fontFile, $testLine);
            $lineWidth = abs($box[4] - $box[0]);

            if ($lineWidth > $maxWidth && $currentLine !== '') {
                if ($shadow) {
                    imagettftext($im, $fontSize, 0, $x + 2, $currentY + 2, $shadowColor, $fontFile, $currentLine);
                }
                imagettftext($im, $fontSize, 0, $x, $currentY, $color, $fontFile, $currentLine);
                $currentY += $lineHeight;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine !== '') {
            if ($shadow) {
                imagettftext($im, $fontSize, 0, $x + 2, $currentY + 2, $shadowColor, $fontFile, $currentLine);
            }
            imagettftext($im, $fontSize, 0, $x, $currentY, $color, $fontFile, $currentLine);
            $currentY += $lineHeight;
        }

        return $currentY;
    }

    /**
     * Draw Badge Pill
     */
    private static function drawBadge($im, string $text, int $x, int $y, array $bgRgb, array $textRgb, ?string $fontFile): void
    {
        $paddingX = 16;
        $paddingY = 8;
        $fontSize = 12;

        if ($fontFile) {
            $box = imagettfbbox($fontSize, 0, $fontFile, $text);
            $textWidth = abs($box[4] - $box[0]);
            $textHeight = abs($box[5] - $box[1]);
        } else {
            $textWidth = strlen($text) * 8;
            $textHeight = 14;
        }

        $badgeWidth = $textWidth + ($paddingX * 2);
        $badgeHeight = $textHeight + ($paddingY * 2);

        $bgColor = imagecolorallocate($im, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
        $textColor = imagecolorallocate($im, $textRgb[0], $textRgb[1], $textRgb[2]);

        // Rounded badge rectangle
        imagefilledrectangle($im, $x, $y, $x + $badgeWidth, $y + $badgeHeight, $bgColor);

        if ($fontFile) {
            imagettftext($im, $fontSize, 0, $x + $paddingX, $y + $badgeHeight - $paddingY + 2, $textColor, $fontFile, $text);
        } else {
            imagestring($im, 3, $x + $paddingX, $y + $paddingY - 2, $text, $textColor);
        }
    }

    /**
     * 1. CREATE HERO BANNER (1920x800)
     */
    public static function createBanner(string $relativePath, string $title, string $subtitle, string $tag = 'PORTAL RESMI', string $theme = 'blue'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 1920;
        $h = 800;
        $im = imagecreatetruecolor($w, $h);
        $colors = self::getThemeColors($theme);
        $fonts = self::getFonts();

        // Background & decorative ambient graphics
        self::drawGradient($im, $w, $h, $colors['bg_top'], $colors['bg_bottom']);
        self::drawDecorations($im, $w, $h, $colors['accent']);

        // Vignette gradient overlay
        $darkOverlay = imagecolorallocatealpha($im, 10, 15, 30, 40);
        imagefilledrectangle($im, 0, 0, $w, $h, $darkOverlay);

        // Header institutional watermark
        if ($fonts['bold']) {
            $watermarkColor = imagecolorallocatealpha($im, 255, 255, 255, 115);
            imagettftext($im, 14, 0, 140, 150, $watermarkColor, $fonts['bold'], "SISTEM INFORMASI PELAYANAN & PEMERINTAHAN DESA DIGITAL");
        }

        // Tag Badge
        self::drawBadge($im, strtoupper($tag), 140, 190, $colors['badge_bg'], $colors['badge_text'], $fonts['bold']);

        // Main Title (Large, Bold)
        $titleY = 280;
        $nextY = self::drawWrappedText(
            $im,
            $title,
            140,
            $titleY,
            38,
            $colors['text_main'],
            $fonts['bold'],
            1350,
            56,
            true
        );

        // Subtitle (Medium, Regular)
        self::drawWrappedText(
            $im,
            $subtitle,
            140,
            $nextY + 25,
            20,
            $colors['text_sub'],
            $fonts['regular'],
            1200,
            32,
            true
        );

        // Bottom Institutional Border Accent
        $accentColor = imagecolorallocate($im, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2]);
        imagefilledrectangle($im, 0, $h - 12, $w, $h, $accentColor);

        imagejpeg($im, $fullPath, 92);
        return $relativePath;
    }

    /**
     * 2. CREATE NEWS / BERITA COVER (800x500)
     */
    public static function createBeritaCover(string $relativePath, string $title, string $category, string $theme = 'emerald'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 800;
        $h = 500;
        $im = imagecreatetruecolor($w, $h);
        $colors = self::getThemeColors($theme);
        $fonts = self::getFonts();

        self::drawGradient($im, $w, $h, $colors['bg_top'], $colors['bg_bottom']);
        self::drawDecorations($im, $w, $h, $colors['accent']);

        // Category Tag
        self::drawBadge($im, strtoupper($category), 50, 70, $colors['badge_bg'], $colors['badge_text'], $fonts['bold']);

        // News Title
        $nextY = self::drawWrappedText(
            $im,
            $title,
            50,
            140,
            22,
            $colors['text_main'],
            $fonts['bold'],
            700,
            34,
            true
        );

        // Official news seal badge at bottom right
        $badgeBg = imagecolorallocatealpha($im, 255, 255, 255, 110);
        imagefilledellipse($im, 720, 420, 90, 90, $badgeBg);
        if ($fonts['bold']) {
            $sealText = imagecolorallocate($im, 255, 255, 255);
            imagettftext($im, 9, 0, 688, 415, $sealText, $fonts['bold'], "WARTA");
            imagettftext($im, 9, 0, 692, 430, $sealText, $fonts['bold'], "DESA");
        }

        // Bottom line
        $accentColor = imagecolorallocate($im, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2]);
        imagefilledrectangle($im, 0, $h - 8, $w, $h, $accentColor);

        imagejpeg($im, $fullPath, 90);


        return $relativePath;
    }

    /**
     * 3. CREATE PERANGKAT DESA PORTRAIT (500x600)
     */
    public static function createPerangkatPortrait(string $relativePath, string $name, string $jabatan, string $gender = 'male'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 500;
        $h = 600;
        $im = imagecreatetruecolor($w, $h);
        $fonts = self::getFonts();

        // Background studio backdrop gradient (professional dark slate/navy)
        $bgTop = [30, 41, 59];      // slate 800
        $bgBottom = [15, 23, 42];   // slate 900
        self::drawGradient($im, $w, $h, $bgTop, $bgBottom);

        // Soft spotlight circle behind avatar
        $spotlight = imagecolorallocatealpha($im, 59, 130, 246, 115);
        imagefilledellipse($im, 250, 230, 320, 320, $spotlight);

        // Avatar Head & Shoulders Silhouette / Illustration
        $skinColor = imagecolorallocate($im, 238, 206, 179);
        $hairColor = imagecolorallocate($im, 30, 30, 35);
        $suitColor = imagecolorallocate($im, 24, 43, 73);      // Official Navy Suit / Batik
        $tieColor = imagecolorallocate($im, 220, 38, 38);       // Red tie / Gold pin
        $goldPin = imagecolorallocate($im, 234, 179, 8);        // KORPRI / Kades Garuda Gold Pin

        // Body / Shoulders
        imagefilledellipse($im, 250, 440, 340, 240, $suitColor);

        // Shirt Collar (White)
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefilledpolygon($im, [
            250, 320,
            210, 370,
            290, 370
        ], $white);

        if ($gender === 'male') {
            // Red Tie
            imagefilledpolygon($im, [
                250, 335,
                242, 340,
                244, 430,
                250, 445,
                256, 430,
                258, 340
            ], $tieColor);

            // Head (Oval)
            imagefilledellipse($im, 250, 240, 160, 190, $skinColor);

            // Hair (Male Short)
            imagefilledarc($im, 250, 220, 170, 170, 180, 360, $hairColor, IMG_ARC_PIE);
        } else {
            // Hijab / Female Hair (Navy/Cream Hijab)
            $hijabColor = imagecolorallocate($im, 15, 76, 129);
            imagefilledellipse($im, 250, 235, 190, 220, $hijabColor);
            imagefilledellipse($im, 250, 250, 130, 160, $skinColor);
        }

        // Gold Emblem / Pin on chest
        imagefilledellipse($im, 180, 390, 18, 18, $goldPin);

        // Bottom Identity Card
        $cardBg = imagecolorallocatealpha($im, 15, 23, 42, 20);
        imagefilledrectangle($im, 20, 470, 480, 575, $cardBg);

        // Border around card
        $cardBorder = imagecolorallocatealpha($im, 59, 130, 246, 80);
        imagerectangle($im, 20, 470, 480, 575, $cardBorder);

        // Name & Jabatan on Portrait
        $nameColor = imagecolorallocate($im, 255, 255, 255);
        $jabatanColor = imagecolorallocate($im, 147, 197, 253);

        if ($fonts['bold']) {
            // Centered name
            $box = imagettfbbox(15, 0, $fonts['bold'], $name);
            $nw = abs($box[4] - $box[0]);
            imagettftext($im, 15, 0, max(30, (int)((500 - $nw) / 2)), 515, $nameColor, $fonts['bold'], $name);

            // Centered jabatan
            $box2 = imagettfbbox(12, 0, $fonts['regular'] ?? $fonts['bold'], $jabatan);
            $jw = abs($box2[4] - $box2[0]);
            imagettftext($im, 12, 0, max(30, (int)((500 - $jw) / 2)), 545, $jabatanColor, $fonts['regular'] ?? $fonts['bold'], $jabatan);
        } else {
            imagestring($im, 5, 40, 500, $name, $nameColor);
            imagestring($im, 3, 40, 530, $jabatan, $jabatanColor);
        }

        imagejpeg($im, $fullPath, 92);
        return $relativePath;
    }

    /**
     * 4. CREATE GALERI PHOTO (1000x700)
     */
    public static function createGaleriPhoto(string $relativePath, string $title, string $albumName, string $theme = 'cyan'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 1000;
        $h = 700;
        $im = imagecreatetruecolor($w, $h);
        $colors = self::getThemeColors($theme);
        $fonts = self::getFonts();

        self::drawGradient($im, $w, $h, $colors['bg_top'], $colors['bg_bottom']);
        self::drawDecorations($im, $w, $h, $colors['accent']);

        // Album Tag
        self::drawBadge($im, "ALBUM: " . strtoupper($albumName), 60, 70, $colors['badge_bg'], $colors['badge_text'], $fonts['bold']);

        // Photo Title / Description
        self::drawWrappedText(
            $im,
            $title,
            60,
            150,
            24,
            $colors['text_main'],
            $fonts['bold'],
            880,
            38,
            true
        );

        // Camera lens graphic in bottom center
        $lensOuter = imagecolorallocatealpha($im, 255, 255, 255, 115);
        $lensInner = imagecolorallocatealpha($im, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2], 80);
        imagefilledellipse($im, 500, 480, 180, 180, $lensOuter);
        imagefilledellipse($im, 500, 480, 130, 130, $lensInner);

        if ($fonts['bold']) {
            $docText = imagecolorallocate($im, 255, 255, 255);
            imagettftext($im, 11, 0, 440, 485, $docText, $fonts['bold'], "DOKUMENTASI");
        }

        imagejpeg($im, $fullPath, 90);


        return $relativePath;
    }

    /**
     * 5. CREATE UMKM PRODUCT PHOTO (700x500)
     */
    public static function createUmkmPhoto(string $relativePath, string $productName, string $category, int $price, string $theme = 'amber'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 700;
        $h = 500;
        $im = imagecreatetruecolor($w, $h);
        $colors = self::getThemeColors($theme);
        $fonts = self::getFonts();

        self::drawGradient($im, $w, $h, $colors['bg_top'], $colors['bg_bottom']);
        self::drawDecorations($im, $w, $h, $colors['accent']);

        // Category Tag
        self::drawBadge($im, strtoupper($category), 50, 60, $colors['badge_bg'], $colors['badge_text'], $fonts['bold']);

        // Product Name
        $nextY = self::drawWrappedText(
            $im,
            $productName,
            50,
            130,
            22,
            $colors['text_main'],
            $fonts['bold'],
            600,
            34,
            true
        );

        // Price Tag Banner
        $priceText = "Rp " . number_format($price, 0, ',', '.');
        $priceBg = imagecolorallocate($im, 16, 185, 129); // Emerald price tag
        $priceTextColor = imagecolorallocate($im, 255, 255, 255);

        imagefilledrectangle($im, 50, $h - 110, 320, $h - 50, $priceBg);
        if ($fonts['bold']) {
            imagettftext($im, 16, 0, 70, $h - 72, $priceTextColor, $fonts['bold'], $priceText);
        } else {
            imagestring($im, 5, 70, $h - 85, $priceText, $priceTextColor);
        }

        // Quality Badge
        $badgeBg = imagecolorallocatealpha($im, 255, 255, 255, 110);
        imagefilledellipse($im, 610, 420, 90, 90, $badgeBg);
        if ($fonts['bold']) {
            $goldText = imagecolorallocate($im, 255, 255, 255);
            imagettftext($im, 8, 0, 580, 415, $goldText, $fonts['bold'], "PRODUK");
            imagettftext($im, 8, 0, 585, 430, $goldText, $fonts['bold'], "LOKAL");
        }

        imagejpeg($im, $fullPath, 90);


        return $relativePath;
    }

    /**
     * 6. CREATE CITIZEN / PENDUDUK PHOTO (400x400)
     */
    public static function createPendudukPhoto(string $relativePath, string $name, string $gender = 'male', string $bgType = 'blue'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 400;
        $h = 400;
        $im = imagecreatetruecolor($w, $h);
        $fonts = self::getFonts();

        // Standard ID Photo background (Formal Blue or Formal Red background)
        if ($bgType === 'red') {
            $bgColor = imagecolorallocate($im, 185, 28, 28);   // Indonesian ID red (#b91c1c)
        } else {
            $bgColor = imagecolorallocate($im, 29, 78, 216);   // Indonesian ID blue (#1d4ed8)
        }
        imagefilledrectangle($im, 0, 0, $w, $h, $bgColor);

        // Subtle gradient on background
        $lightTop = imagecolorallocatealpha($im, 255, 255, 255, 115);
        imagefilledellipse($im, 200, 100, 300, 200, $lightTop);

        // Body & Head
        $skinColor = imagecolorallocate($im, 238, 206, 179);
        $shirtColor = imagecolorallocate($im, 245, 245, 245);
        $hairColor = imagecolorallocate($im, 35, 35, 40);

        // Shirt
        imagefilledellipse($im, 200, 350, 280, 180, $shirtColor);

        // Collar
        $collarShadow = imagecolorallocate($im, 210, 210, 215);
        imagefilledpolygon($im, [200, 260, 160, 300, 240, 300], $collarShadow);

        if ($gender === 'male') {
            // Head
            imagefilledellipse($im, 200, 180, 130, 160, $skinColor);
            // Short Hair
            imagefilledarc($im, 200, 160, 140, 140, 180, 360, $hairColor, IMG_ARC_PIE);
        } else {
            // Female Hijab / Hair
            $hijab = imagecolorallocate($im, 250, 232, 215);
            imagefilledellipse($im, 200, 180, 160, 190, $hijab);
            imagefilledellipse($im, 200, 190, 105, 130, $skinColor);
        }

        imagejpeg($im, $fullPath, 90);


        return $relativePath;
    }

    /**
     * 7. CREATE VILLAGE EMBLEM LOGO (512x512 PNG Transparan)
     */
    public static function createLogoDesa(string $relativePath, string $desaName = 'CANDRALOKA'): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        $w = 512;
        $h = 512;
        $im = imagecreatetruecolor($w, $h);
        imagesavealpha($im, true);
        $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
        imagefill($im, 0, 0, $transparent);

        $fonts = self::getFonts();

        $gold = imagecolorallocate($im, 245, 158, 11);
        $goldDark = imagecolorallocate($im, 180, 83, 9);
        $emerald = imagecolorallocate($im, 16, 185, 129);
        $blue = imagecolorallocate($im, 30, 58, 138);
        $white = imagecolorallocate($im, 255, 255, 255);

        // Outer Shield / Perisai Lambang
        imagefilledpolygon($im, [
            256, 40,
            440, 100,
            440, 300,
            256, 470,
            72, 300,
            72, 100
        ], $goldDark);

        imagefilledpolygon($im, [
            256, 52,
            428, 110,
            428, 292,
            256, 456,
            84, 292,
            84, 110
        ], $blue);

        // Inner Circle
        imagefilledellipse($im, 256, 230, 250, 250, $emerald);
        imagefilledellipse($im, 256, 230, 220, 220, $blue);

        // Golden Star at Top
        imagefilledellipse($im, 256, 160, 44, 44, $gold);

        // Rice and Cotton stylized branches
        imagefilledarc($im, 200, 240, 120, 160, 90, 270, $gold, IMG_ARC_NOFILL);
        imagefilledarc($im, 312, 240, 120, 160, 270, 90, $white, IMG_ARC_NOFILL);

        // Text ribbon at bottom
        imagefilledrectangle($im, 100, 370, 412, 420, $gold);
        if ($fonts['bold']) {
            $bannerText = imagecolorallocate($im, 15, 23, 42);
            $box = imagettfbbox(13, 0, $fonts['bold'], $desaName);
            $tw = abs($box[4] - $box[0]);
            imagettftext($im, 13, 0, max(110, (int)((512 - $tw) / 2)), 402, $bannerText, $fonts['bold'], $desaName);
        }

        imagepng($im, $fullPath);


        return $relativePath;
    }

    /**
     * 8. CREATE SAMPLE PDF FILE (FOR BUMDES REPORTS)
     */
    public static function createSamplePdf(string $relativePath, string $title): string
    {
        $fullPath = storage_path('app/public/' . $relativePath);
        self::ensureDirectory($fullPath);

        // Minimalist valid PDF file content
        $pdfContent = "%PDF-1.4\n" .
            "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n" .
            "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n" .
            "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n" .
            "4 0 obj << /Length 120 >> stream\n" .
            "BT /F1 18 Tf 50 720 Td (" . addcslashes($title, "()") . ") Tj ET\n" .
            "BT /F1 12 Tf 50 680 Td (Dokumen Laporan Resmi BUMDes Terverifikasi.) Tj ET\n" .
            "endstream endobj\n" .
            "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n" .
            "xref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000414 00000 n \n" .
            "trailer << /Size 6 /Root 1 0 R >>\nstartxref\n492\n%%EOF";

        File::put($fullPath, $pdfContent);

        return $relativePath;
    }
}
