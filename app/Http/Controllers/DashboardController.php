<?php

namespace App\Http\Controllers;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Interazione;
use App\Models\Opportunita;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'totale_clienti' => Cliente::count(),
                'totale_opportunita' => Opportunita::count(),
                'totale_attivita' => Attivita::count(),
                'totale_interazioni' => Interazione::count(),
            ];

            $pipeline = Opportunita::select('fase', DB::raw('count(*) as totale'), DB::raw('sum(valore_stimato) as valore_totale'))
                ->groupBy('fase')
                ->get();

            $top_agenti = User::where('role', 'agente')
                ->withCount('opportunita')
                ->withSum('opportunita', 'valore_stimato')
                ->orderByDesc('opportunita_sum_valore_stimato')
                ->take(5)
                ->get();

            $attivita_recenti = Attivita::with(['user', 'cliente'])
                ->orderByDesc('data')
                ->take(10)
                ->get();

            $clienti_mensili = DB::table('clienti')
                ->select(DB::raw("strftime('%Y-%m', created_at) as mese"), DB::raw('count(*) as totale'))
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('mese')
                ->orderBy('mese')
                ->get();

            return view('dashboard.index', compact(
                'stats',
                'pipeline',
                'top_agenti',
                'attivita_recenti',
                'clienti_mensili'
            ));
        }

        // Agente view
        $miei_clienti_count = $user->clienti()->count();

        $opportunita_aperte = Opportunita::where('user_id', $user->id)
            ->whereNotIn('fase', ['vinto', 'perso'])
            ->count();

        $attivita_oggi = Attivita::where('user_id', $user->id)
            ->whereDate('data', today())
            ->get();

        $pipeline_value = Opportunita::where('user_id', $user->id)
            ->whereNotIn('fase', ['vinto', 'perso'])
            ->sum('valore_stimato');

        $opportunita_recenti = Opportunita::where('user_id', $user->id)
            ->with('cliente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'miei_clienti_count',
            'opportunita_aperte',
            'attivita_oggi',
            'pipeline_value',
            'opportunita_recenti'
        ));
    }
}
