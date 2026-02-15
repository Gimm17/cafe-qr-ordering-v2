<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeTable;
use App\Models\TableToken;
use App\Services\QrService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTableController extends Controller
{
    public function __construct(private QrService $qr) {}

    public function index()
    {
        $tables = CafeTable::with('tokens')->orderBy('table_no')->get();
        return view('admin.tables', ['tables' => $tables]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'table_no' => ['required','integer','min:1','max:999'],
            'name' => ['nullable','string','max:80'],
        ]);

        $table = CafeTable::create([
            'table_no' => $data['table_no'],
            'name' => $data['name'] ?? null,
            'is_active' => true,
        ]);

        TableToken::create([
            'table_id' => $table->id,
            'token' => $this->newToken(),
            'is_active' => true,
        ]);

        return back()->with('success','Meja dibuat + token QR dibuat.');
    }

    public function rotateToken(CafeTable $table)
    {
        $table->tokens()->where('is_active', true)->update(['is_active' => false, 'rotated_at' => now()]);

        TableToken::create([
            'table_id' => $table->id,
            'token' => $this->newToken(),
            'is_active' => true,
        ]);

        return back()->with('success','Token QR di-rotate.');
    }

    public function qrPng(CafeTable $table)
    {
        $token = $table->activeToken();
        abort_if(!$token, 404);

        $url = url('/t/'.$token->token);
        $png = $this->qr->png($url, 360);

        return response($png, 200)->header('Content-Type', 'image/png');
    }

    /**
     * Download QR code with styled card (logo, cafe name, table number, instruction)
     */
    public function downloadQr(CafeTable $table, string $format)
    {
        $token = $table->activeToken();
        abort_if(!$token, 404);

        $cafeName   = \App\Models\Setting::getValue('cafe_name', config('app.name', 'Nindito'));
        $cafeTag    = \App\Models\Setting::getValue('cafe_tagline', 'Coffee & Friends');
        $tableNo    = $table->table_no;
        $tableName  = $table->name ?: "Meja {$tableNo}";
        $url        = url('/t/'.$token->token);

        // Generate QR code
        $qrPng = $this->qr->png($url, 300);

        // === Canvas ===
        $width  = 420;
        $height = 600;
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);

        // --- Colors ---
        $white      = imagecolorallocate($img, 255, 255, 255);
        $offWhite   = imagecolorallocate($img, 250, 247, 243); // bone bg
        $indigo     = imagecolorallocate($img, 35, 74, 230);   // #234AE6
        $indigoDark = imagecolorallocate($img, 28, 55, 180);
        $textDark   = imagecolorallocate($img, 31, 41, 55);
        $textMuted  = imagecolorallocate($img, 120, 113, 108);
        $borderClr  = imagecolorallocate($img, 229, 225, 220);

        // Background
        imagefilledrectangle($img, 0, 0, $width, $height, $offWhite);

        // === Header bar (indigo gradient simulation) ===
        $headerH = 90;
        for ($y = 0; $y < $headerH; $y++) {
            $ratio = $y / $headerH;
            $r = (int)(35 + (28 - 35) * $ratio);
            $g = (int)(74 + (55 - 74) * $ratio);
            $b = (int)(230 + (180 - 230) * $ratio);
            $lineColor = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $width, $y, $lineColor);
        }

        // === Logo (circular) ===
        $logoPath = public_path('assets/brand/logo.png');
        $logoSize = 50;
        $logoX = ($width - $logoSize) / 2;
        $logoY = 6;

        if (file_exists($logoPath)) {
            $logoSrc = imagecreatefrompng($logoPath);
            if ($logoSrc) {
                $srcW = imagesx($logoSrc);
                $srcH = imagesy($logoSrc);

                // Create circular mask via temp image
                $circle = imagecreatetruecolor($logoSize, $logoSize);
                imagesavealpha($circle, true);
                $transparent = imagecolorallocatealpha($circle, 0, 0, 0, 127);
                imagefill($circle, 0, 0, $transparent);

                // Scale logo into circle
                $scaled = imagecreatetruecolor($logoSize, $logoSize);
                imagecopyresampled($scaled, $logoSrc, 0, 0, 0, 0, $logoSize, $logoSize, $srcW, $srcH);

                // Draw white circle background
                imagefilledellipse($img, (int)($logoX + $logoSize / 2), (int)($logoY + $logoSize / 2), $logoSize + 4, $logoSize + 4, $white);

                // Copy scaled logo
                imagecopyresampled($img, $logoSrc, (int)$logoX, (int)$logoY, 0, 0, $logoSize, $logoSize, $srcW, $srcH);

                imagedestroy($logoSrc);
                imagedestroy($circle);
                imagedestroy($scaled);
            }
        }

        // === Cafe name (header) ===
        $font = 5;
        $nameW = imagefontwidth($font) * strlen($cafeName);
        $nameX = ($width - $nameW) / 2;
        imagestring($img, $font, (int)$nameX, $logoY + $logoSize + 6, $cafeName, $white);

        // === Tagline ===
        $tagFont = 2;
        $tagW = imagefontwidth($tagFont) * strlen($cafeTag);
        $tagX = ($width - $tagW) / 2;
        imagestring($img, $tagFont, (int)$tagX, $logoY + $logoSize + 24, $cafeTag, imagecolorallocate($img, 200, 210, 255));

        // === QR Code (centered, with white card) ===
        $qrImage = imagecreatefromstring($qrPng);
        if ($qrImage) {
            $qrSize = 300;
            $cardPad = 15;
            $cardW = $qrSize + $cardPad * 2;
            $cardH = $qrSize + $cardPad * 2;
            $cardX = ($width - $cardW) / 2;
            $cardY = $headerH + 15;

            // White card behind QR
            imagefilledrectangle($img, (int)$cardX, (int)$cardY, (int)($cardX + $cardW), (int)($cardY + $cardH), $white);
            imagerectangle($img, (int)$cardX, (int)$cardY, (int)($cardX + $cardW), (int)($cardY + $cardH), $borderClr);

            // QR image
            $qrX = $cardX + $cardPad;
            $qrY = $cardY + $cardPad;
            imagecopyresampled($img, $qrImage, (int)$qrX, (int)$qrY, 0, 0, $qrSize, $qrSize, imagesx($qrImage), imagesy($qrImage));
            imagedestroy($qrImage);
        }

        // === Table badge (indigo pill) ===
        $badgeText = "MEJA " . $tableNo;
        $badgeW = imagefontwidth(5) * strlen($badgeText) + 40;
        $badgeH = 36;
        $badgeX = ($width - $badgeW) / 2;
        $badgeY = ($cardY ?? $headerH + 15) + ($cardH ?? 330) + 15;

        // Rounded rectangle for badge
        imagefilledrectangle($img, (int)$badgeX, (int)$badgeY, (int)($badgeX + $badgeW), (int)($badgeY + $badgeH), $indigo);
        // Simulated rounded corners
        imagefilledellipse($img, (int)($badgeX + 8), (int)($badgeY + 8), 16, 16, $indigo);
        imagefilledellipse($img, (int)($badgeX + $badgeW - 8), (int)($badgeY + 8), 16, 16, $indigo);
        imagefilledellipse($img, (int)($badgeX + 8), (int)($badgeY + $badgeH - 8), 16, 16, $indigo);
        imagefilledellipse($img, (int)($badgeX + $badgeW - 8), (int)($badgeY + $badgeH - 8), 16, 16, $indigo);

        // Badge text
        $btW = imagefontwidth(5) * strlen($badgeText);
        $btX = ($width - $btW) / 2;
        imagestring($img, 5, (int)$btX, (int)($badgeY + 10), $badgeText, $white);

        // === Table name (if custom) ===
        if ($table->name) {
            $tnW = imagefontwidth(3) * strlen($table->name);
            $tnX = ($width - $tnW) / 2;
            imagestring($img, 3, (int)$tnX, (int)($badgeY + $badgeH + 8), $table->name, $textDark);
        }

        // === Instruction ===
        $inst1 = "Scan QR untuk pesan.";
        $inst2 = "Bisa juga langsung ke kasir.";
        $instY = $badgeY + $badgeH + ($table->name ? 30 : 15);

        $i1W = imagefontwidth(2) * strlen($inst1);
        $i2W = imagefontwidth(2) * strlen($inst2);
        imagestring($img, 2, (int)(($width - $i1W) / 2), (int)$instY, $inst1, $textMuted);
        imagestring($img, 2, (int)(($width - $i2W) / 2), (int)($instY + 18), $inst2, $textMuted);

        // === Thin bottom bar (indigo accent) ===
        imagefilledrectangle($img, 0, $height - 4, $width, $height, $indigo);

        // === Outer border ===
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $borderClr);

        $filename = "QR_Meja_{$tableNo}";

        if ($format === 'pdf') {
            ob_start();
            imagepng($img);
            $pngData = ob_get_clean();
            imagedestroy($img);

            $base64 = base64_encode($pngData);
            $html = "<!DOCTYPE html><html><head><title>QR Meja {$tableNo}</title><style>
                @page { size: A6; margin: 0; }
                body { margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #FAF7F3; }
                img { max-width: 100%; height: auto; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
                @media print { body { -webkit-print-color-adjust: exact; } }
            </style></head><body><img src='data:image/png;base64,{$base64}'><script>window.print();</script></body></html>";

            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', "inline; filename=\"{$filename}.html\"");
        }

        // PNG download
        ob_start();
        imagepng($img);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return response($pngData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.png\"");
    }

    private function newToken(): string
    {
        return Str::random(32);
    }
}

