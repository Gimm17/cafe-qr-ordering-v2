<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModGroup;
use App\Models\ModOption;
use Illuminate\Http\Request;

class AdminModifierController extends Controller
{
    public function index()
    {
        $modGroups = ModGroup::with('options')->orderBy('sort_order')->get();
        return view('admin.modifiers', compact('modGroups'));
    }

    public function create()
    {
        return view('admin.modifier_form', [
            'modGroup' => null,
            'title' => 'Tambah Modifier Group'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:SINGLE,MULTIPLE',
            'is_required' => 'boolean',
        ]);

        ModGroup::create([
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->boolean('is_required'),
            'sort_order' => ModGroup::max('sort_order') + 1,
        ]);

        return redirect()->route('admin.modifiers')->with('success', 'Modifier group berhasil ditambahkan');
    }

    public function edit(ModGroup $modGroup)
    {
        $modGroup->load('options');
        return view('admin.modifier_form', [
            'modGroup' => $modGroup,
            'title' => 'Edit Modifier Group'
        ]);
    }

    public function update(Request $request, ModGroup $modGroup)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:SINGLE,MULTIPLE',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $modGroup->update([
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.modifiers')->with('success', 'Modifier group berhasil diupdate');
    }

    public function destroy(ModGroup $modGroup)
    {
        $modGroup->delete();
        return redirect()->route('admin.modifiers')->with('success', 'Modifier group berhasil dihapus');
    }

    // Modifier Options
    public function storeOption(Request $request, ModGroup $modGroup)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price_modifier' => 'required|integer',
        ]);

        $modGroup->options()->create([
            'name' => $request->name,
            'price_modifier' => $request->price_modifier,
            'sort_order' => $modGroup->options()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.modifiers.edit', $modGroup)->with('success', 'Option berhasil ditambahkan');
    }

    public function updateOption(Request $request, ModOption $option)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price_modifier' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $option->update([
            'name' => $request->name,
            'price_modifier' => $request->price_modifier,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.modifiers.edit', $option->modGroup)->with('success', 'Option berhasil diupdate');
    }

    public function destroyOption(ModOption $option)
    {
        $modGroup = $option->modGroup;
        $option->delete();
        return redirect()->route('admin.modifiers.edit', $modGroup)->with('success', 'Option berhasil dihapus');
    }
}
