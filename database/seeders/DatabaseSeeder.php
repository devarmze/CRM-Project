<?php

namespace Database\Seeders;

use App\Models\Attivita;
use App\Models\Cliente;
use App\Models\Contatto;
use App\Models\Interazione;
use App\Models\Opportunita;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin CRM',
            'email' => 'admin@crm.it',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Agenti
        $agente1 = User::create([
            'name' => 'Marco Rossi',
            'email' => 'agente1@crm.it',
            'password' => Hash::make('password'),
            'role' => 'agente',
        ]);

        $agente2 = User::create([
            'name' => 'Laura Bianchi',
            'email' => 'agente2@crm.it',
            'password' => Hash::make('password'),
            'role' => 'agente',
        ]);

        $agente3 = User::create([
            'name' => 'Giovanni Verdi',
            'email' => 'agente3@crm.it',
            'password' => Hash::make('password'),
            'role' => 'agente',
        ]);

        // 10 Clienti
        $clientiData = [
            ['nome' => 'Tech Solutions Srl', 'tipo' => 'azienda', 'settore' => 'Tecnologia', 'stato' => 'attivo', 'citta' => 'Milano', 'email' => 'info@techsolutions.it', 'telefono' => '02-1234567'],
            ['nome' => 'Mario Conti', 'tipo' => 'privato', 'settore' => 'Consulenza', 'stato' => 'prospect', 'citta' => 'Roma', 'email' => 'mario.conti@email.it', 'telefono' => '06-9876543'],
            ['nome' => 'GreenBuild SpA', 'tipo' => 'azienda', 'settore' => 'Edilizia', 'stato' => 'attivo', 'citta' => 'Torino', 'email' => 'commerciale@greenbuild.it', 'telefono' => '011-2345678'],
            ['nome' => 'Farmacia Salute', 'tipo' => 'azienda', 'settore' => 'Farmaceutico', 'stato' => 'prospect', 'citta' => 'Napoli', 'email' => 'farmacia@salute.it', 'telefono' => '081-3456789'],
            ['nome' => 'Anna Ferretti', 'tipo' => 'privato', 'settore' => 'Legale', 'stato' => 'attivo', 'citta' => 'Bologna', 'email' => 'a.ferretti@studio.it', 'telefono' => '051-4567890'],
            ['nome' => 'Logistica Express Srl', 'tipo' => 'azienda', 'settore' => 'Logistica', 'stato' => 'attivo', 'citta' => 'Genova', 'email' => 'ops@logexpress.it', 'telefono' => '010-5678901'],
            ['nome' => 'Ristorante Da Luigi', 'tipo' => 'azienda', 'settore' => 'Ristorazione', 'stato' => 'inattivo', 'citta' => 'Firenze', 'email' => 'luigi@ristorante.it', 'telefono' => '055-6789012'],
            ['nome' => 'Studio Medico Associato', 'tipo' => 'azienda', 'settore' => 'Sanità', 'stato' => 'prospect', 'citta' => 'Venezia', 'email' => 'segreteria@studmed.it', 'telefono' => '041-7890123'],
            ['nome' => 'Roberto Manzoni', 'tipo' => 'privato', 'settore' => 'Finanza', 'stato' => 'attivo', 'citta' => 'Milano', 'email' => 'r.manzoni@finanza.it', 'telefono' => '02-8901234'],
            ['nome' => 'Moda Italiana Srl', 'tipo' => 'azienda', 'settore' => 'Moda', 'stato' => 'prospect', 'citta' => 'Milano', 'email' => 'commerciale@modaitaliana.it', 'telefono' => '02-9012345'],
        ];

        $clienti = [];
        foreach ($clientiData as $data) {
            $creator = collect([$agente1, $agente2, $agente3])->random();
            $data['created_by'] = $creator->id;
            $clienti[] = Cliente::create($data);
        }

        // Assign clients to agents via pivot
        $clienti[0]->users()->attach([$agente1->id, $agente2->id]);
        $clienti[1]->users()->attach([$agente1->id]);
        $clienti[2]->users()->attach([$agente2->id, $agente3->id]);
        $clienti[3]->users()->attach([$agente2->id]);
        $clienti[4]->users()->attach([$agente1->id, $agente3->id]);
        $clienti[5]->users()->attach([$agente3->id]);
        $clienti[6]->users()->attach([$agente1->id]);
        $clienti[7]->users()->attach([$agente2->id]);
        $clienti[8]->users()->attach([$agente3->id]);
        $clienti[9]->users()->attach([$agente1->id, $agente2->id, $agente3->id]);

        // 3 Contatti
        Contatto::create(['cliente_id' => $clienti[0]->id, 'nome' => 'Luca Martini', 'ruolo' => 'CTO', 'email' => 'l.martini@techsolutions.it', 'telefono' => '333-1234567']);
        Contatto::create(['cliente_id' => $clienti[0]->id, 'nome' => 'Sara Romano', 'ruolo' => 'CEO', 'email' => 's.romano@techsolutions.it', 'telefono' => '333-7654321']);
        Contatto::create(['cliente_id' => $clienti[2]->id, 'nome' => 'Paolo Galli', 'ruolo' => 'Responsabile Acquisti', 'email' => 'p.galli@greenbuild.it', 'telefono' => '347-9876543']);

        // 5 Opportunità
        $opportunita1 = Opportunita::create(['cliente_id' => $clienti[0]->id, 'user_id' => $agente1->id, 'titolo' => 'Licenza software gestionale', 'valore_stimato' => 15000.00, 'fase' => 'in_trattativa', 'data_chiusura_prevista' => now()->addDays(30), 'note' => 'Cliente interessato alla suite completa']);
        $opportunita2 = Opportunita::create(['cliente_id' => $clienti[2]->id, 'user_id' => $agente2->id, 'titolo' => 'Progetto costruzione capannone', 'valore_stimato' => 250000.00, 'fase' => 'nuovo', 'data_chiusura_prevista' => now()->addMonths(3)]);
        $opportunita3 = Opportunita::create(['cliente_id' => $clienti[4]->id, 'user_id' => $agente1->id, 'titolo' => 'Consulenza legale annuale', 'valore_stimato' => 8000.00, 'fase' => 'vinto', 'data_chiusura_prevista' => now()->subDays(5)]);
        $opportunita4 = Opportunita::create(['cliente_id' => $clienti[5]->id, 'user_id' => $agente3->id, 'titolo' => 'Contratto logistica 12 mesi', 'valore_stimato' => 45000.00, 'fase' => 'in_trattativa', 'data_chiusura_prevista' => now()->addDays(15)]);
        $opportunita5 = Opportunita::create(['cliente_id' => $clienti[8]->id, 'user_id' => $agente3->id, 'titolo' => 'Gestione portafoglio investimenti', 'valore_stimato' => 5000.00, 'fase' => 'perso', 'note' => 'Cliente ha scelto un competitor']);

        // 5 Attività
        Attivita::create(['cliente_id' => $clienti[0]->id, 'opportunita_id' => $opportunita1->id, 'user_id' => $agente1->id, 'tipo' => 'chiamata', 'data' => now()->addHours(2), 'stato' => 'da_fare', 'note' => 'Chiamata di follow-up sulla proposta commerciale']);
        Attivita::create(['cliente_id' => $clienti[2]->id, 'user_id' => $agente2->id, 'tipo' => 'incontro', 'data' => now()->subDay(), 'stato' => 'completata', 'note' => 'Sopralluogo presso il cantiere']);
        Attivita::create(['cliente_id' => $clienti[4]->id, 'opportunita_id' => $opportunita3->id, 'user_id' => $agente1->id, 'tipo' => 'email', 'data' => now()->subDays(3), 'stato' => 'completata', 'note' => 'Invio contratto firmato']);
        Attivita::create(['cliente_id' => $clienti[5]->id, 'opportunita_id' => $opportunita4->id, 'user_id' => $agente3->id, 'tipo' => 'incontro', 'data' => now()->addDays(2), 'stato' => 'da_fare', 'note' => 'Presentazione offerta logistica']);
        Attivita::create(['cliente_id' => $clienti[8]->id, 'user_id' => $agente3->id, 'tipo' => 'chiamata', 'data' => now()->subWeek(), 'stato' => 'completata', 'note' => 'Chiamata di cortesia post-perdita opportunità']);

        // 5 Interazioni
        Interazione::create(['cliente_id' => $clienti[0]->id, 'user_id' => $agente1->id, 'data' => now()->subDays(7), 'tipo' => 'Telefonata', 'descrizione' => 'Primo contatto commerciale. Il cliente ha mostrato interesse per la nostra soluzione gestionale.', 'esito' => 'Positivo - fissato appuntamento']);
        Interazione::create(['cliente_id' => $clienti[0]->id, 'user_id' => $agente2->id, 'data' => now()->subDays(3), 'tipo' => 'Visita', 'descrizione' => 'Presentazione della demo del prodotto presso la sede del cliente.', 'esito' => 'Molto positivo - richiesta preventivo']);
        Interazione::create(['cliente_id' => $clienti[2]->id, 'user_id' => $agente2->id, 'data' => now()->subDays(14), 'tipo' => 'Email', 'descrizione' => 'Invio brochure e materiale informativo sui nostri servizi di costruzione.', 'esito' => 'In attesa di risposta']);
        Interazione::create(['cliente_id' => $clienti[4]->id, 'user_id' => $agente1->id, 'data' => now()->subMonth(), 'tipo' => 'Incontro', 'descrizione' => 'Negoziazione termini del contratto di consulenza legale annuale.', 'esito' => 'Contratto firmato']);
        Interazione::create(['cliente_id' => $clienti[5]->id, 'user_id' => $agente3->id, 'data' => now()->subDays(5), 'tipo' => 'Telefonata', 'descrizione' => 'Discussione sui volumi di spedizione previsti per il prossimo anno.', 'esito' => 'Interesse confermato']);
    }
}
