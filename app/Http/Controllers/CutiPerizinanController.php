<?php

namespace App\Http\Controllers;

use App\Models\CutiPerizinan;
use App\Models\User;
use App\Notifications\LeaveRequestDecided;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CutiPerizinanController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', CutiPerizinan::class);

        if ($request->ajax()) {
            return $this->datatable($request, route('cuti-perizinan.index'));
        }

        return view('pages.cuti_perizinan.index');
    }

    /**
     * Shared server-side table for the pending and processed leave lists.
     * Supervisor scoping is applied here too — it must never be bypassed by
     * hitting the ajax endpoint directly.
     */
    private function datatable(Request $request, string $source, ?array $onlyStatuses = null)
    {
        $user = auth()->user();

        $query = CutiPerizinan::query()
            ->with('user:id,nama,id_atasan')
            ->join('users', 'users.id', '=', 'cuti_perizinan.id_user')
            ->leftJoin('users as approvers', 'approvers.id', '=', 'cuti_perizinan.disetujui_oleh')
            ->select('cuti_perizinan.*', 'users.nama as user_nama', 'users.id_atasan as user_id_atasan', 'approvers.nama as approver_nama')
            ->when($onlyStatuses, fn ($q) => $q->whereIn('cuti_perizinan.status_pengajuan', $onlyStatuses))
            ->when(! $user->hasRole('admin'), fn ($q) => $q->whereIn('cuti_perizinan.id_user', $user->bawahan()->pluck('id')))
            ->when($request->filled('status'), fn ($q) => $q->where('cuti_perizinan.status_pengajuan', $request->input('status')))
            ->when($request->filled('jenis'), fn ($q) => $q->where('cuti_perizinan.jenis', $request->input('jenis')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('cuti_perizinan.tanggal_mulai', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('cuti_perizinan.tanggal_mulai', '<=', $request->input('to')));

        return DataTables::eloquent($query)
            ->filterColumn('user_nama', fn ($q, $keyword) => $q->where('users.nama', 'like', "%{$keyword}%"))
            ->orderColumn('user_nama', 'users.nama $1')
            ->editColumn('jenis', fn ($row) => __(ucfirst($row->jenis)))
            ->addColumn('status', fn ($row) => view('pages.cuti_perizinan._status_pill', ['row' => $row])->render())
            ->addColumn('approver', fn ($row) => $row->approver_nama ?? '-')
            ->addColumn('action', fn ($row) => view('pages.cuti_perizinan._row_actions', ['row' => $row, 'source' => $source])->render())
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function create()
    {
        $users = User::where('is_archived', false)->orderBy('nama')->get();

        return view('pages.cuti_perizinan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string|max:255',
            'jenis' => 'required|in:izin,alpa,sakit',
            'status_pengajuan' => 'nullable|in:diajukan,disetujui,ditolak',
            'disetujui_oleh' => 'nullable|exists:users,id',
            'surat_izin' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_izin')) {
            $data['surat_izin'] = $request->file('surat_izin')->store('surat_izin', 'public');
        }

        CutiPerizinan::create($data);

        return redirect()->route('cuti-perizinan.index')->with('success', __('Leave request submitted.'));
    }

    public function edit(CutiPerizinan $cutiPerizinan)
    {
        $users = User::where('is_archived', false)->orderBy('nama')->get();

        return view('pages.cuti_perizinan.edit', compact('cutiPerizinan', 'users'));
    }

    public function update(Request $request, CutiPerizinan $cutiPerizinan)
    {
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'jenis' => 'required|in:izin,alpa,sakit',
        ]);

        $cutiPerizinan->update($data);

        return redirect()->route('cuti-perizinan.index')->with('success', __('Leave request updated successfully.'));
    }

    public function approve(CutiPerizinan $cutiPerizinan)
    {
        $this->authorize('approve', $cutiPerizinan);

        $previousStatus = $cutiPerizinan->status_pengajuan;

        $cutiPerizinan->update([
            'status_pengajuan' => 'disetujui',
            'disetujui_oleh' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($cutiPerizinan)
            ->withProperties(['from' => $previousStatus, 'to' => 'disetujui', 'id_user' => $cutiPerizinan->id_user])
            ->log('leave.approved');

        if ($cutiPerizinan->user->email) {
            $cutiPerizinan->user->notify(new LeaveRequestDecided($cutiPerizinan, 'disetujui'));
        }

        return redirect()->route('cuti-perizinan.index')->with('success', __('Leave request approved.'));
    }

    public function reject(CutiPerizinan $cutiPerizinan)
    {
        $this->authorize('reject', $cutiPerizinan);

        $previousStatus = $cutiPerizinan->status_pengajuan;

        $cutiPerizinan->update([
            'status_pengajuan' => 'ditolak',
            'disetujui_oleh' => auth()->id(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($cutiPerizinan)
            ->withProperties(['from' => $previousStatus, 'to' => 'ditolak', 'id_user' => $cutiPerizinan->id_user])
            ->log('leave.rejected');

        if ($cutiPerizinan->user->email) {
            $cutiPerizinan->user->notify(new LeaveRequestDecided($cutiPerizinan, 'ditolak'));
        }

        return redirect()->route('cuti-perizinan.index')->with('success', __('Leave request rejected.'));
    }

    public function hasilPermohonan(Request $request)
    {
        $this->authorize('viewAny', CutiPerizinan::class);

        if ($request->ajax()) {
            return $this->datatable($request, route('cuti-perizinan.hasil'), ['disetujui', 'ditolak']);
        }

        return view('pages.cuti_perizinan.hasil');
    }

    public function undoApproval(CutiPerizinan $cutiPerizinan)
    {
        $this->authorize('undo', $cutiPerizinan);

        $previousStatus = $cutiPerizinan->status_pengajuan;

        $cutiPerizinan->update([
            'status_pengajuan' => 'diajukan',
            'disetujui_oleh' => null,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($cutiPerizinan)
            ->withProperties(['from' => $previousStatus, 'to' => 'diajukan', 'id_user' => $cutiPerizinan->id_user])
            ->log('leave.undone');

        return redirect()->route('cuti-perizinan.hasil')->with('success', __('Leave request status reverted.'));
    }

    public function destroy(CutiPerizinan $cutiPerizinan)
    {
        $cutiPerizinan->delete();

        return redirect()->route('cuti-perizinan.index')->with('success', __('Leave request deleted successfully.'));
    }

    public function show(CutiPerizinan $cutiPerizinan)
    {
        $this->authorize('view', $cutiPerizinan);

        return view('pages.cuti_perizinan.show', compact('cutiPerizinan'));
    }
}
