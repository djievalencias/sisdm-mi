<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CutiPerizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class CutiPerizinanController extends Controller
{

    public function getAllPermohonan()
    {
        $cutiPerizinans = CutiPerizinan::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($cutiPerizinans);
    }

    public function getPermohonanById($id)
    {
        $cutiPerizinan = CutiPerizinan::with('user')->find($id);

        if (!$cutiPerizinan) {
            return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
        }

        return response()->json($cutiPerizinan);
    }

    public function update(Request $request, $id): JsonResponse 
    {
        // Debug untuk melihat data yang diterima
        Log::info('Raw request:', $request->all());
        
        $cutiPerizinan = CutiPerizinan::find($id);
        if (!$cutiPerizinan) {
            return response()->json([
                'message' => 'Data permohonan izin tidak ditemukan.',
            ], 404);
        }
    
        // Ambil data yang ada dari request
        $updateData = array_filter([
            'id_user' => $request->input('id_user', $cutiPerizinan->id_user),
            'tanggal_mulai' => $request->input('tanggal_mulai', $cutiPerizinan->tanggal_mulai),
            'tanggal_selesai' => $request->input('tanggal_selesai', $cutiPerizinan->tanggal_selesai),
            'keterangan' => $request->input('keterangan', $cutiPerizinan->keterangan),
            'jenis' => $request->input('jenis', $cutiPerizinan->jenis),
        ], function($value) {
            return !is_null($value);
        });
    
        // Update data
        $cutiPerizinan->update($updateData);
    
        // Handle file upload jika ada
        if ($request->hasFile('surat_izin')) {
            // Hapus file lama jika ada
            if ($cutiPerizinan->surat_izin) {
                Storage::disk('public')->delete($cutiPerizinan->surat_izin);
            }
            
            $filePath = $request->file('surat_izin')->store('surat_izin', 'public');
            $cutiPerizinan->surat_izin = $filePath;
            $cutiPerizinan->save();
        }
    
        return response()->json([
            'message' => 'Data permohonan izin berhasil diperbarui.',
            'data' => $cutiPerizinan->fresh()
        ], 200);
    }



    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
            'surat_izin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validasi untuk file
        ]);

        // Handle file upload jika ada
        $filePath = null;
        if ($request->hasFile('surat_izin')) {
            $filePath = $request->file('surat_izin')->store('surat_izin', 'public'); // Simpan di storage/public/surat_izin
        }

        // Simpan data ke database
        $cutiPerizinan = CutiPerizinan::create([
            'id_user' => $request->id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'jenis' => $request->jenis,
            'status_pengajuan' => 'diajukan',
            'surat_izin' => $filePath, // Simpan path file dalam database
        ]);

        return response()->json($cutiPerizinan, 201);
    }
}
