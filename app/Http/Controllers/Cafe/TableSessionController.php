<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\TableToken;
use App\Models\CafeTable;

class TableSessionController extends Controller
{
    public function enter(string $token)
    {
        $tt = TableToken::with('table')
            ->where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$tt || !$tt->table || !$tt->table->is_active) {
            return view('cafe.invalid_qr');
        }

        session([
            'cafe_table_id' => $tt->table->id,
            'cafe_table_no' => $tt->table->table_no,
            'cafe_table_token' => $token,
        ]);

        return view('cafe.table_landing', [
            'tableNo' => $tt->table->table_no,
            'token' => $token,
        ]);
    }

    public function enterByNumber(int $tableNo)
    {
        $table = CafeTable::where('table_no', $tableNo)
            ->where('is_active', true)
            ->first();

        if (!$table) {
            return view('cafe.invalid_qr');
        }

        // Get or create an active token for this table
        $token = $table->tokens()->where('is_active', true)->first();
        
        if (!$token) {
            return view('cafe.invalid_qr');
        }

        session([
            'cafe_table_id' => $table->id,
            'cafe_table_no' => $table->table_no,
            'cafe_table_token' => $token->token,
        ]);

        return view('cafe.table_landing', [
            'tableNo' => $table->table_no,
            'token' => $token->token,
        ]);
    }
}

