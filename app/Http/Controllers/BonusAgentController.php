<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\CustomerUmroh;
use App\Models\CustomerHaji;
use App\Models\BonusPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BonusAgentController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/bonus-agent.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }

    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/bonus-agent.create', $permissions);
        $canEdit = $isAdmin || in_array('/bonus-agent.edit', $permissions);
        $canDelete = $isAdmin || in_array('/bonus-agent.delete', $permissions);

        $agents = Agent::with(['bonusPayouts'])->get()->map(function ($agent) {
            // Count Jamaah
            $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
            $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

            // Calculate Total Bonus
            $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
            $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
            $totalBonus = $bonusUmroh + $bonusHaji;

            // Calculate Paid Amount (only Confirmed payments)
            $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');

            // Remaining
            $sisaBonus = $totalBonus - $sudahDibayar;

            // Attach to agent object for view
            $agent->umroh_count = $umrohCount;
            $agent->haji_count = $hajiCount;
            $agent->total_bonus = $totalBonus;
            $agent->sudah_dibayar = $sudahDibayar;
            $agent->sisa_bonus = $sisaBonus;

            return $agent;
        });

        return view('pages.bonus-agent.index', [
            'title' => 'Bonus Agent',
            'agents' => $agents,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');

        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'kode_referensi_mutasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png'
        ]);

        try {
            DB::beginTransaction();

            $agent = Agent::findOrFail($validated['agent_id']);
            
            // Generate Transaction Code
            $lastPayout = BonusPayout::orderBy('id', 'desc')->first();
            $nextId = $lastPayout ? $lastPayout->id + 1 : 1;
            $kodeTransaksi = "INV/DB/{$agent->kode_agent}/{$nextId}";

            $data = $validated;
            $data['kode_transaksi'] = $kodeTransaksi;
            $data['status_pembayaran'] = 'Checked'; // Automatically set status to Checked

            // Handle File Upload
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/bukti_pembayaran_bonus', $filename);
                $data['bukti_pembayaran'] = 'bukti_pembayaran_bonus/' . $filename;
            }

            BonusPayout::create($data);

            DB::commit();
            return redirect()->route('payment-agent.show', $validated['agent_id'])->with('success', 'Pembayaran bonus berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->checkPermission('edit');

        $payout = BonusPayout::with('agent')->findOrFail($id);
        $agent = $payout->agent;

        return view('pages.bonus-agent.edit', [
            'title' => 'Edit Pembayaran Bonus',
            'payout' => $payout,
            'agent' => $agent
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');

        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'kode_referensi_mutasi' => 'nullable|string',
            'status_pembayaran' => 'required|string',
            'catatan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png'
        ]);

        try {
            DB::beginTransaction();

            $payout = BonusPayout::findOrFail($id);
            $data = $validated;

            // Handle File Upload
            if ($request->hasFile('bukti_pembayaran')) {
                // Delete old file if exists
                if ($payout->bukti_pembayaran) {
                    Storage::delete('public/' . $payout->bukti_pembayaran);
                }

                $file = $request->file('bukti_pembayaran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/bukti_pembayaran_bonus', $filename);
                $data['bukti_pembayaran'] = 'bukti_pembayaran_bonus/' . $filename;
            }

            $payout->update($data);

            DB::commit();
            return redirect()->route('payment-agent.show', $payout->agent_id)->with('success', 'Pembayaran bonus berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }


    public function show($id)
    {
        $agent = Agent::with(['bonusPayouts'])->findOrFail($id);

        // Count Jamaah
        $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
        $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

        // Calculate Total Bonus
        $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
        $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
        $totalBonus = $bonusUmroh + $bonusHaji;

        // Calculate Paid Amount (only Confirmed payments)
        $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');

        // Remaining
        $sisaBonus = $totalBonus - $sudahDibayar;

        // Attach to agent object for view
        $agent->umroh_count = $umrohCount;
        $agent->haji_count = $hajiCount;
        $agent->total_bonus = $totalBonus;
        $agent->sudah_dibayar = $sudahDibayar;
        $agent->sisa_bonus = $sisaBonus;

        // Prepare Transaction Code for Form
        $lastPayout = BonusPayout::orderBy('id', 'desc')->first();
        $nextId = $lastPayout ? $lastPayout->id + 1 : 1;
        $nextTransactionCode = "INV/DB/{$agent->kode_agent}/{$nextId}";

        return view('pages.bonus-agent.show', [
            'title' => 'Detail Bonus Agent',
            'agent' => $agent,
            'nextTransactionCode' => $nextTransactionCode
        ]);
    }
    public function export()
    {
        $agents = Agent::with(['bonusPayouts'])->get()->map(function ($agent) {
             // Count Jamaah
            $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
            $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

            // Calculate Total Bonus
            $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
            $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
            $totalBonus = $bonusUmroh + $bonusHaji;

            // Calculate Paid Amount (only Confirmed payments)
            $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');

            // Remaining
            $sisaBonus = $totalBonus - $sudahDibayar;

            return [
                'nama_agent' => $agent->nama_agent,
                'nik_agent' => $agent->nik_agent,
                'kontak_agent' => $agent->kontak_agent,
                'umroh_count' => $umrohCount,
                'haji_count' => $hajiCount,
                'total_bonus' => $totalBonus,
                'sudah_dibayar' => $sudahDibayar,
                'sisa_bonus' => $sisaBonus
            ];
        });

        $filename = "bonus_agent_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Nama Agent', 'NIK Agent', 'Kontak Agent', 'Jamaah Umroh', 'Jamaah Haji', 'Total Bonus', 'Sudah Dibayar', 'Sisa Bonus'];

        $callback = function () use ($agents, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($agents as $agent) {
                fputcsv($file, [
                    $agent['nama_agent'],
                    $agent['nik_agent'],
                    $agent['kontak_agent'],
                    $agent['umroh_count'],
                    $agent['haji_count'],
                    $agent['total_bonus'],
                    $agent['sudah_dibayar'],
                    $agent['sisa_bonus']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $agents = Agent::with(['bonusPayouts'])->get()->map(function ($agent) {
            // Count Jamaah
            $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
            $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

            // Calculate Total Bonus
            $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
            $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
            $totalBonus = $bonusUmroh + $bonusHaji;

            // Calculate Paid Amount (only Confirmed payments)
            $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');

            // Remaining
            $sisaBonus = $totalBonus - $sudahDibayar;

            // Attach to agent object for view
            $agent->umroh_count = $umrohCount;
            $agent->haji_count = $hajiCount;
            $agent->total_bonus = $totalBonus;
            $agent->sudah_dibayar = $sudahDibayar;
            $agent->sisa_bonus = $sisaBonus;
            
            return $agent;
        });

        return view('pages.bonus-agent.print', [
            'agents' => $agents,
            'title' => 'Laporan Bonus Agent'
        ]);
    }

    public function exportDetail($id)
    {
        $agent = Agent::with(['bonusPayouts'])->findOrFail($id);
        $payouts = $agent->bonusPayouts;

        $filename = "riwayat_pembayaran_bonus_{$agent->nama_agent}_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No', 'Tanggal Pembayaran', 'Kode Transaksi', 'Nama Agent', 'Jumlah Pembayaran', 'Metode Pembayaran', 'Status Pembayaran', 'Kode Referensi', 'Catatan'];

        $callback = function () use ($payouts, $agent, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payouts as $index => $payout) {
                fputcsv($file, [
                    $index + 1,
                    $payout->tanggal_bayar,
                    $payout->kode_transaksi,
                    $agent->nama_agent,
                    $payout->jumlah_bayar,
                    $payout->metode_pembayaran,
                    $payout->status_pembayaran,
                    $payout->kode_referensi_mutasi,
                    $payout->catatan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printDetail($id)
    {
        $agent = Agent::with(['bonusPayouts'])->findOrFail($id);
        
         // Recalculate totals for the print view header if needed, similar to show method
        $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
        $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();
        $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
        $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
        $totalBonus = $bonusUmroh + $bonusHaji;
        $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');
        $sisaBonus = $totalBonus - $sudahDibayar;
        
        $agent->total_bonus = $totalBonus;
        $agent->sudah_dibayar = $sudahDibayar;
        $agent->sisa_bonus = $sisaBonus;

        return view('pages.bonus-agent.print-detail', [
            'agent' => $agent,
            'title' => 'Riwayat Pembayaran Bonus - ' . $agent->nama_agent
        ]);
    }

    public function showPaymentHistory($id)
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/bonus-agent.create', $permissions);
        $canEdit = $isAdmin || in_array('/bonus-agent.edit', $permissions);
        $canDelete = $isAdmin || in_array('/bonus-agent.delete', $permissions);

        $agent = Agent::with(['bonusPayouts'])->findOrFail($id);

        // Count Jamaah
        $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
        $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

        // Calculate Total Bonus
        $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
        $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
        $totalBonus = $bonusUmroh + $bonusHaji;

        // Calculate Paid Amount (only Confirmed payments)
        $sudahDibayar = $agent->bonusPayouts->where('status_pembayaran', 'Confirmed')->sum('jumlah_bayar');

        // Remaining
        $sisaBonus = $totalBonus - $sudahDibayar;

        // Attach to agent object for view
        $agent->umroh_count = $umrohCount;
        $agent->haji_count = $hajiCount;
        $agent->total_bonus = $totalBonus;
        $agent->sudah_dibayar = $sudahDibayar;
        $agent->sisa_bonus = $sisaBonus;

        return view('pages.payment-agent.show', [
            'title' => 'Riwayat Pembayaran Bonus - ' . $agent->nama_agent,
            'agent' => $agent,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function showJamaahUmroh($id)
    {
        $agent = Agent::findOrFail($id);
        $jamaahList = CustomerUmroh::where('agent_id', $id)
            ->with(['keberangkatanUmroh.paketUmroh', 'jamaah'])
            ->get();

        // Calculate bonus for each jamaah
        $jamaahList->each(function ($jamaah) use ($agent) {
            $jamaah->bonus_agent = $agent->komisi_paket_umroh;
        });

        return view('pages.bonus-agent.jamaah-umroh', [
            'title' => 'Jamaah Umroh - ' . $agent->nama_agent,
            'agent' => $agent,
            'jamaahList' => $jamaahList
        ]);
    }

    public function showJamaahHaji($id)
    {
        $agent = Agent::findOrFail($id);
        $jamaahList = CustomerHaji::where('agent_id', $id)
            ->with(['keberangkatanHaji.paketHaji', 'jamaah'])
            ->get();

        // Calculate bonus for each jamaah
        $jamaahList->each(function ($jamaah) use ($agent) {
            $jamaah->bonus_agent = $agent->komisi_paket_haji;
        });

        return view('pages.bonus-agent.jamaah-haji', [
            'title' => 'Jamaah Haji - ' . $agent->nama_agent,
            'agent' => $agent,
            'jamaahList' => $jamaahList
        ]);
    }
}
