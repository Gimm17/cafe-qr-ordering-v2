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

    private function newToken(): string
    {
        return Str::random(32);
    }
}
