<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CariGroup;

class CariGroupController extends Controller
{
    // Cari grupları listesi
    public function index()
    {
        $cariGroups = CariGroup::withCount('cariHesaplar')->latest()->get();
        return view('cari.groups.index', compact('cariGroups'));
    }

    // Yeni grup kaydet
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:cari_groups,name',
            'description' => 'nullable|string',
        ]);

        CariGroup::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cari grubu başarıyla eklendi!'
        ]);
    }

    // Grup sil
    public function destroy($id)
    {
        $group = CariGroup::find($id);
        
        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Cari grubu bulunamadı!'
            ], 404);
        }

        // Eğer bu gruba ait cari varsa silinmesin
        if ($group->cariHesaplar()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu gruba ait cari hesaplar bulunmaktadır. Önce cari hesapları silmelisiniz!'
            ], 400);
        }

        $group->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cari grubu başarıyla silindi!'
        ]);
    }
}
