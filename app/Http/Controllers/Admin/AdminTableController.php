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
     * Download QR code with styled card — clean black & white design
     */
    public function downloadQr(CafeTable $table, string $format)
    {
        $token = $table->activeToken();
        abort_if(!$token, 404);

        $cafeName  = \App\Models\Setting::getValue('cafe_name', config('app.name', 'Nindito'));
        $cafeTag   = \App\Models\Setting::getValue('cafe_tagline', 'Coffee & Friends');
        $tableNo   = $table->table_no;
        $url       = url('/t/'.$token->token);

        $qrPng = $this->qr->png($url, 300);

        // === Canvas ===
        $width  = 420;
        $height = 620;
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);

        // --- B&W Colors only ---
        $white    = imagecolorallocate($img, 255, 255, 255);
        $black    = imagecolorallocate($img, 0, 0, 0);
        $darkGray = imagecolorallocate($img, 50, 50, 50);
        $gray     = imagecolorallocate($img, 130, 130, 130);
        $lightGray= imagecolorallocate($img, 200, 200, 200);

        // White background
        imagefilledrectangle($img, 0, 0, $width, $height, $white);

        // === Logo (centered at top) ===
        $logoPath = public_path('assets/brand/logo-nobg.png');
        $logoSize = 80;
        $logoX = (int)(($width - $logoSize) / 2);
        $logoY = 25;

        if (file_exists($logoPath)) {
            $logoSrc = imagecreatefrompng($logoPath);
            if ($logoSrc) {
                imagesavealpha($logoSrc, true);
                $srcW = imagesx($logoSrc);
                $srcH = imagesy($logoSrc);
                imagecopyresampled($img, $logoSrc, $logoX, $logoY, 0, 0, $logoSize, $logoSize, $srcW, $srcH);
                imagedestroy($logoSrc);
            }
        }

        // === Cafe name ===
        $nameFont = 5;
        $nameW = imagefontwidth($nameFont) * strlen($cafeName);
        $nameX = (int)(($width - $nameW) / 2);
        $nameY = $logoY + $logoSize + 10;
        imagestring($img, $nameFont, $nameX, $nameY, $cafeName, $black);

        // === Tagline ===
        $tagFont = 2;
        $tagW = imagefontwidth($tagFont) * strlen($cafeTag);
        $tagX = (int)(($width - $tagW) / 2);
        $tagY = $nameY + 20;
        imagestring($img, $tagFont, $tagX, $tagY, $cafeTag, $gray);

        // === Thin separator line ===
        $sepY = $tagY + 22;
        imageline($img, 40, $sepY, $width - 40, $sepY, $lightGray);

        // === QR Code ===
        $qrImage = imagecreatefromstring($qrPng);
        if ($qrImage) {
            $qrSize = 280;
            $qrX = (int)(($width - $qrSize) / 2);
            $qrY = $sepY + 15;
            imagecopyresampled($img, $qrImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize, imagesx($qrImage), imagesy($qrImage));
            imagedestroy($qrImage);
        }

        // === Thin separator line below QR ===
        $sep2Y = ($qrY ?? $sepY + 15) + ($qrSize ?? 280) + 15;
        imageline($img, 40, $sep2Y, $width - 40, $sep2Y, $lightGray);

        // === Table badge (black pill) ===
        $badgeText = "MEJA " . $tableNo;
        $badgeFont = 5;
        $btW = imagefontwidth($badgeFont) * strlen($badgeText);
        $badgeW = $btW + 50;
        $badgeH = 34;
        $badgeX = (int)(($width - $badgeW) / 2);
        $badgeY = $sep2Y + 12;

        // Rounded pill
        imagefilledrectangle($img, $badgeX, $badgeY, $badgeX + $badgeW, $badgeY + $badgeH, $black);
        imagefilledellipse($img, $badgeX + 10, $badgeY + $badgeH / 2, 20, $badgeH, $black);
        imagefilledellipse($img, $badgeX + $badgeW - 10, $badgeY + $badgeH / 2, 20, $badgeH, $black);

        $btX = (int)(($width - $btW) / 2);
        imagestring($img, $badgeFont, $btX, $badgeY + 9, $badgeText, $white);

        // === Table name (if custom) ===
        $extraOffset = 0;
        if ($table->name) {
            $tnFont = 3;
            $tnW = imagefontwidth($tnFont) * strlen($table->name);
            $tnX = (int)(($width - $tnW) / 2);
            imagestring($img, $tnFont, $tnX, $badgeY + $badgeH + 8, $table->name, $darkGray);
            $extraOffset = 22;
        }

        // === Instruction ===
        $inst1 = "Scan QR untuk pesan.";
        $inst2 = "Bisa juga langsung ke kasir.";
        $instY = $badgeY + $badgeH + 12 + $extraOffset;

        $i1W = imagefontwidth(2) * strlen($inst1);
        $i2W = imagefontwidth(2) * strlen($inst2);
        imagestring($img, 2, (int)(($width - $i1W) / 2), $instY, $inst1, $gray);
        imagestring($img, 2, (int)(($width - $i2W) / 2), $instY + 16, $inst2, $gray);

        // === Thin border ===
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $lightGray);

        $filename = "QR_Meja_{$tableNo}";

        if ($format === 'pdf') {
            ob_start();
            imagepng($img);
            $pngData = ob_get_clean();
            imagedestroy($img);

            $base64 = base64_encode($pngData);
            $html = "<!DOCTYPE html><html><head><title>QR Meja {$tableNo}</title><style>
                @page { size: A6; margin: 0; }
                body { margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #fff; }
                img { max-width: 100%; height: auto; }
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

