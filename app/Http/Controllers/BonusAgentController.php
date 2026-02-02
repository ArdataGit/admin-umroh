<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\CustomerUmroh;
use App\Models\CustomerHaji;
use App\Models\BonusPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusAgentController extends Controller
{
    public function index()
    {
        $agents = Agent::with(['bonusPayouts'])->get()->map(function ($agent) {
            // Count Jamaah
            $umrohCount = CustomerUmroh::where('agent_id', $agent->id)->count();
            $hajiCount = CustomerHaji::where('agent_id', $agent->id)->count();

            // Calculate Total Bonus
            $bonusUmroh = $umrohCount * $agent->komisi_paket_umroh;
            $bonusHaji = $hajiCount * $agent->komisi_paket_haji;
            $totalBonus = $bonusUmroh + $bonusHaji;

            // Calculate Paid Amount
            $sudahDibayar = $agent->bonusPayouts->sum('jumlah_bayar');

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
            'agents' => $agents
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        try {
            BonusPayout::create($validated);
            return redirect()->back()->with('success', 'Pembayaran bonus berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }
}
