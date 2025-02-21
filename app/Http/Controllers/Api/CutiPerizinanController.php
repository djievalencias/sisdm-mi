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

    public function getAllPermohonan(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $cutiPerizinan = CutiPerizinan::with('user')->orderBy('created_at', 'desc')->paginate($perPage);
        return response()->json($cutiPerizinan);
    }

    public function getPermohonanById($id)
    {
        $cutiPerizinan = CutiPerizinan::with('user')->find($id);

        if (!$cutiPerizinan) {
            return response()->json(['message' => 'Permohonan tidak ditemukan.'], 404);
        }

        return response()->json($cutiPerizinan);
    }

    public function update(Request $request, $id): JsonResponse
    {
        Log::info('Raw request:', $request->all());

        $cutiPerizinan = CutiPerizinan::find($id);
        if (!$cutiPerizinan) {
            return response()->json(['message' => 'Data permohonan izin tidak ditemukan.'], 404);
        }

        // Use reusable validation
        $validator = $this->validateCutiPerizinan($request, 'update');

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // Only update fields that are provided in request
        $updateData = $request->only(['id_user', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'jenis']);
        $cutiPerizinan->update(array_filter($updateData, fn($value) => !is_null($value)));

        // Handle file upload
        if ($request->hasFile('surat_izin')) {
            if ($cutiPerizinan->surat_izin) {
                Storage::disk('public')->delete($cutiPerizinan->surat_izin);
            }

            $filePath = $request->file('surat_izin')->store('surat_izin', 'public');
            $cutiPerizinan->update(['surat_izin' => $filePath]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cutiPerizinan->fresh(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = $this->validateCutiPerizinan($request, 'store');

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        // Handle file upload
        $filePath = $request->hasFile('surat_izin')
            ? $request->file('surat_izin')->store('surat_izin', 'public')
            : null;

        // Save data
        $cutiPerizinan = CutiPerizinan::create([
            'id_user' => $request->id_user,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'jenis' => $request->jenis,
            'status_pengajuan' => 'diajukan',
            'surat_izin' => $filePath,
        ]);

        return response()->json($cutiPerizinan, 201);
    }

    private function validateCutiPerizinan(Request $request, $type)
    {
        $rules = [
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
            'surat_izin' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        if ($type === 'update') {
            // Allow optional fields for update
            $rules = array_map(fn($rule) => str_replace('required|', '', $rule), $rules);
        }

        return Validator::make($request->all(), $rules);
    }
}