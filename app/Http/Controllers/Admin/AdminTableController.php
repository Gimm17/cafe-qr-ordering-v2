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
     * Download QR code with styled card (cafe name, table number, instruction)
     */
    public function downloadQr(CafeTable $table, string $format)
    {
        $token = $table->activeToken();
        abort_if(!$token, 404);

        $cafeName = config('app.name', 'Cafe QR');
        $tableNo = $table->table_no;
        $url = url('/t/'.$token->token);
        
        // Generate QR code
        $qrPng = $this->qr->png($url, 300);
        
        // Create styled image with GD
        $width = 400;
        $height = 520;
        
        $img = imagecreatetruecolor($width, $height);
        
        // Colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $primary = imagecolorallocate($img, 5, 150, 105); // Emerald-600
        $gray = imagecolorallocate($img, 107, 114, 128);
        $darkGray = imagecolorallocate($img, 31, 41, 55);
        
        // Fill background white
        imagefilledrectangle($img, 0, 0, $width, $height, $white);
        
        // Header bar (primary color)
        imagefilledrectangle($img, 0, 0, $width, 50, $primary);
        
        // Cafe name (centered in header)
        $font = 5; // Built-in font
        $textWidth = imagefontwidth($font) * strlen($cafeName);
        $x = ($width - $textWidth) / 2;
        imagestring($img, $font, $x, 17, $cafeName, $white);
        
        // Load QR code from string
        $qrImage = imagecreatefromstring($qrPng);
        if ($qrImage) {
            $qrSize = 300;
            $qrX = ($width - $qrSize) / 2;
            $qrY = 70;
            imagecopy($img, $qrImage, $qrX, $qrY, 0, 0, imagesx($qrImage), imagesy($qrImage));
            imagedestroy($qrImage);
        }
        
        // Table badge (black rounded rectangle simulation)
        $badgeY = 390;
        $badgeWidth = 120;
        $badgeHeight = 35;
        $badgeX = ($width - $badgeWidth) / 2;
        imagefilledrectangle($img, $badgeX, $badgeY, $badgeX + $badgeWidth, $badgeY + $badgeHeight, $darkGray);
        
        // Table text
        $tableText = "MEJA " . $tableNo;
        $tableTextWidth = imagefontwidth(5) * strlen($tableText);
        $tableTextX = ($width - $tableTextWidth) / 2;
        imagestring($img, 5, $tableTextX, $badgeY + 10, $tableText, $white);
        
        // Instruction text
        $instruction1 = "Silahkan scan QR untuk pesan";
        $instruction2 = "atau langsung ke kasir";
        
        $inst1Width = imagefontwidth(2) * strlen($instruction1);
        $inst2Width = imagefontwidth(2) * strlen($instruction2);
        
        imagestring($img, 2, ($width - $inst1Width) / 2, 445, $instruction1, $gray);
        imagestring($img, 2, ($width - $inst2Width) / 2, 465, $instruction2, $gray);
        
        // Border
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $gray);
        
        $filename = "QR_Meja_{$tableNo}";
        
        if ($format === 'pdf') {
            // For PDF, we'll create a simple PDF with the image
            // Using a basic approach without external PDF libraries
            ob_start();
            imagepng($img);
            $pngData = ob_get_clean();
            imagedestroy($img);
            
            // Create a simple HTML wrapper that can be printed as PDF
            $base64 = base64_encode($pngData);
            $html = "<!DOCTYPE html><html><head><title>QR Meja {$tableNo}</title><style>
                @page { size: A6; margin: 0; }
                body { margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
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

