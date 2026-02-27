<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AgentService;
use Illuminate\Support\Facades\Storage;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function index()
    {
        $dataAgent = $this->agentService->getAll();
        return view('pages.data-agent.index', ['title' => 'Data Agent', 'dataAgent' => $dataAgent]);
    }

    public function create()
    {
        // Auto-generate kode_agent: A-001, A-002, etc.
        $lastAgent = \App\Models\Agent::orderBy('id', 'desc')->first();
        $lastNumber = $lastAgent ? intval(substr($lastAgent->kode_agent, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeAgent = 'A-' . $newNumber;

        return view('pages.data-agent.create', [
            'title' => 'Tambah Data Agent',
            'kodeAgent' => $kodeAgent
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_agent' => 'required|string|unique:agents,kode_agent',
            'nik_agent' => 'required|string|unique:agents,nik_agent|max:16',
            'nama_agent' => 'required|string|max:255',
            'kontak_agent' => 'required|string|max:20',
            'email_agent' => 'nullable|email|unique:agents,email_agent',
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'status_agent' => 'required|in:Active,Non Active',
            'komisi_paket_umroh' => 'required|numeric|min:0',
            'komisi_paket_haji' => 'required|numeric|min:0',
            'alamat_agent' => 'required|string',
            'catatan_agent' => 'nullable|string',
            'foto_agent' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('foto_agent')) {
            $file = $request->file('foto_agent');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('agents', $filename, 'public');
            $validated['foto_agent'] = $path;
        }

        $this->agentService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Agent',
            'action' => 'Create',
            'keterangan' => 'Menambah data agent baru: ' . $validated['nama_agent'] . ' (' . $validated['kode_agent'] . ')'
        ]);

        return redirect()->route('data-agent')->with('success', 'Data agent berhasil ditambahkan');
    }

    public function edit($id)
    {
        $agent = $this->agentService->getById($id);

        if (!$agent) {
            return redirect()->route('data-agent')->with('error', 'Data agent tidak ditemukan');
        }

        return view('pages.data-agent.edit', [
            'title' => 'Edit Data Agent',
            'agent' => $agent
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik_agent' => 'required|string|max:16|unique:agents,nik_agent,' . $id,
            'nama_agent' => 'required|string|max:255',
            'kontak_agent' => 'required|string|max:20',
            'email_agent' => 'nullable|email|unique:agents,email_agent,' . $id,
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'status_agent' => 'required|in:Active,Non Active',
            'komisi_paket_umroh' => 'required|numeric|min:0',
            'komisi_paket_haji' => 'required|numeric|min:0',
            'alamat_agent' => 'required|string',
            'catatan_agent' => 'nullable|string',
            'foto_agent' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('foto_agent')) {
            $agent = $this->agentService->getById($id);
            if ($agent && $agent->foto_agent) {
                Storage::disk('public')->delete($agent->foto_agent);
            }
            
            $file = $request->file('foto_agent');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('agents', $filename, 'public');
            $validated['foto_agent'] = $path;
        }

        $agent = $this->agentService->update($id, $validated);

        if (!$agent) {
            return redirect()->route('data-agent')->with('error', 'Data agent tidak ditemukan');
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Agent',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data agent: ' . $agent->nama_agent . ' (' . $agent->kode_agent . ')'
        ]);

        return redirect()->route('data-agent')->with('success', 'Data agent berhasil diperbarui');
    }

    public function destroy($id)
    {
        $agent = $this->agentService->getById($id);
        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Data agent tidak ditemukan'], 404);
        }

        $namaAgent = $agent->nama_agent;
        $kodeAgent = $agent->kode_agent;

        $deleted = $this->agentService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data agent tidak ditemukan'], 404);
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Agent',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data agent: ' . $namaAgent . ' (' . $kodeAgent . ')'
        ]);

        return response()->json(['success' => true, 'message' => 'Data agent berhasil dihapus']);
    }

    public function show($id)
    {
        $agent = $this->agentService->getById($id);

        if (!$agent) {
            return redirect()->route('data-agent')->with('error', 'Data agent tidak ditemukan');
        }

        return view('pages.data-agent.show', [
            'title' => 'Detail Data Agent',
            'agent' => $agent
        ]);
    }

    public function printData()
    {
        $agents = $this->agentService->getAll();
        return view('pages.data-agent.print', [
            'agents' => $agents,
            'title' => 'Laporan Data Agent'
        ]);
    }

    public function export()
    {
        $agents = $this->agentService->getAll();
        $filename = "data_agent_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Kode Agent', 
            'NIK', 
            'Nama Agent', 
            'Kontak', 
            'Email', 
            'Kota/Kabupaten', 
            'Jenis Kelamin', 
            'Tempat Lahir', 
            'Tanggal Lahir', 
            'Status', 
            'Komisi Umroh', 
            'Komisi Haji', 
            'Alamat', 
            'Catatan'
        ];

        $callback = function() use ($agents, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($agents as $agent) {
                fputcsv($file, [
                    $agent->kode_agent,
                    $agent->nik_agent,
                    $agent->nama_agent,
                    $agent->kontak_agent,
                    $agent->email_agent,
                    $agent->kabupaten_kota,
                    $agent->jenis_kelamin,
                    $agent->tempat_lahir,
                    $agent->tanggal_lahir,
                    $agent->status_agent,
                    $agent->komisi_paket_umroh,
                    $agent->komisi_paket_haji,
                    $agent->alamat_agent,
                    $agent->catatan_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
