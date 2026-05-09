# Guida al progetto — CRM Gestionale
> Leggi questo file per capire cosa fa il progetto, come è costruito e perché sono state fatte certe scelte.

---

## Cos'è un CRM?

Un **CRM (Customer Relationship Management)** è un software aziendale che centralizza tutte le informazioni sui clienti. Invece di avere rubrica, foglio Excel per le trattative e post-it per i promemoria, tutto è in un unico sistema. Le aziende lo usano per:
- Sapere chi sono i clienti e in che stato è il rapporto commerciale
- Tenere traccia delle trattative aperte (opportunità)
- Assegnare task agli agenti commerciali
- Vedere lo storico di ogni comunicazione avuta con un cliente

---

## Le tecnologie usate

### PHP e Laravel
**PHP** è il linguaggio di programmazione lato server (gira sul server, non nel browser). **Laravel** è un framework PHP — cioè una struttura preconfezionata che fornisce strumenti pronti per fare cose comuni (gestire URL, connettersi al database, gestire sessioni, validare form, ecc.) senza scriverle da zero.

Versione usata: **Laravel 13** con **PHP 8.3**.

### Il pattern MVC
Laravel usa il pattern **MVC (Model - View - Controller)**, che separa il codice in tre ruoli distinti:

```
Browser  →  Route  →  Controller  →  Model  →  Database
                          ↓
                        View  →  Browser
```

| Componente | Cartella | Cosa fa |
|---|---|---|
| **Model** | `app/Models/` | Rappresenta una tabella del database e le sue relazioni |
| **View** | `resources/views/` | Il template HTML che l'utente vede |
| **Controller** | `app/Http/Controllers/` | Riceve la richiesta, usa i Model, restituisce la View |

**Esempio pratico:** quando vai su `/clienti`:
1. Laravel legge `routes/web.php` → trova che `/clienti` chiama `ClienteController@index`
2. Il Controller fa una query con Eloquent per prendere i clienti dal DB
3. Passa i dati alla View `resources/views/clienti/index.blade.php`
4. Blade genera l'HTML e lo manda al browser

### Eloquent ORM
**Eloquent** è il sistema di Laravel per parlare con il database senza scrivere SQL a mano. Ogni tabella del database corrisponde a un Model PHP.

```php
// Invece di scrivere:
// SELECT * FROM clienti WHERE stato = 'attivo' ORDER BY nome

// Con Eloquent scrivi:
Cliente::where('stato', 'attivo')->orderBy('nome')->get();
```

Eloquent gestisce anche le **relazioni** tra tabelle:
```php
// Prende un cliente con tutti i suoi contatti e opportunità
$cliente = Cliente::with(['contatti', 'opportunita'])->find(1);

// Accede ai dati senza ulteriori query
$cliente->contatti;      // Collection di Contatto
$cliente->opportunita;   // Collection di Opportunita
```

### Blade (template engine)
I file `.blade.php` sono template HTML con sintassi speciale di Laravel. Il server li "compila" in PHP puro prima di mandarli al browser.

```blade
{{-- Variabile --}}
{{ $cliente->nome }}

{{-- Condizione --}}
@if($user->isAdmin())
    <a href="/admin/users">Gestione Utenti</a>
@endif

{{-- Ciclo --}}
@foreach($clienti as $cliente)
    <tr><td>{{ $cliente->nome }}</td></tr>
@endforeach

{{-- Eredità layout --}}
@extends('layouts.app')
@section('content')
    ...contenuto della pagina...
@endsection
```

Tutte le views del progetto estendono `layouts/app.blade.php` (il layout con sidebar e header), e inseriscono il loro contenuto nella sezione `content`.

### Tailwind CSS
**Tailwind** è un framework CSS che non ha classi predefinite come "btn-primary". Invece fornisce classi "atomiche" da combinare direttamente nell'HTML:

```html
<!-- Un bottone blu con testo bianco, padding, bordi arrotondati -->
<button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
    Salva
</button>
```

Tailwind viene compilato da **Vite** (il bundler): legge tutte le views Blade, trova le classi usate, e genera un unico file CSS ottimizzato.

### MySQL
Il database relazionale usato per salvare tutti i dati. La connessione è configurata nel file `.env`. Laravel usa **PDO** (PHP Data Objects) internamente, il che garantisce i **prepared statements** su tutte le query — protezione automatica da SQL Injection.

---

## La struttura del database

### Le 7 tabelle

```
users ─────────────────────────── utenti del CRM
clienti ───────────────────────── anagrafica clienti
contatti ──────── (FK: clienti)── referenti aziendali
opportunita ─┬─── (FK: clienti)── trattative commerciali
             └─── (FK: users)
attivita ────┬─── (FK: clienti)── task e promemoria
             ├─── (FK: opportunita)
             └─── (FK: users)
interazioni ─┬─── (FK: clienti)── storico comunicazioni
             └─── (FK: users)
cliente_user ─┬── (FK: clienti)── PIVOT: chi gestisce chi
              └── (FK: users)
```

### La tabella pivot `cliente_user`
Questa è la chiave della funzionalità di condivisione clienti. Una relazione **molti-a-molti** significa che:
- Un agente può gestire molti clienti
- Un cliente può essere gestito da molti agenti

Per realizzarla in SQL serve una terza tabella (pivot) che tiene le coppie:

```
cliente_user
─────────────────
cliente_id | user_id
─────────────────
1          | 2        ← il cliente 1 è gestito dall'agente 2
1          | 3        ← il cliente 1 è gestito anche dall'agente 3
2          | 2        ← il cliente 2 è gestito dall'agente 2
```

In Laravel si definisce così nel Model:
```php
// In Cliente.php
public function users() {
    return $this->belongsToMany(User::class, 'cliente_user');
}

// In User.php
public function clienti() {
    return $this->belongsToMany(Cliente::class, 'cliente_user');
}
```

Per aggiungere/rimuovere un agente da un cliente:
```php
$cliente->users()->attach($userId);   // aggiunge
$cliente->users()->detach($userId);   // rimuove
```

---

## Il sistema di autenticazione e ruoli

### Come funziona il login
1. L'utente invia email e password al form `/login`
2. `AuthController@login` chiama `Auth::attempt(['email' => ..., 'password' => ...])`
3. Laravel controlla il database: trova l'utente e confronta la password con `password_verify()` (bcrypt)
4. Se corretto, crea una sessione PHP e fa il redirect alla dashboard
5. Tutte le pagine protette hanno il middleware `auth` che controlla la sessione

```php
// Se non sei autenticato, Laravel ti rimanda al login automaticamente
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    // ...
});
```

### I due ruoli
Il campo `role` nella tabella `users` può essere `'admin'` o `'agente'`.

Il Model User ha un metodo helper:
```php
public function isAdmin(): bool {
    return $this->role === 'admin';
}
```

Le route admin usano un middleware personalizzato (`AdminMiddleware`) che blocca chi non è admin:
```php
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
});
```

### Scoping per agente
Ogni agente vede solo i suoi clienti. Questo avviene nel controller:
```php
if (!$user->isAdmin()) {
    $assignedIds = $user->clienti()->pluck('clienti.id');
    $query->whereIn('id', $assignedIds);
}
```

---

## Le migrazioni

Le **migrazioni** sono file PHP che descrivono come creare (o modificare) le tabelle del database. Sono come la "storia" del database — si eseguono in ordine e costruiscono la struttura.

```bash
php artisan migrate --seed
```

- `migrate` → esegue tutte le migrazioni in `database/migrations/`
- `--seed` → esegue anche il seeder (`DatabaseSeeder.php`) che inserisce i dati di esempio

Ogni migrazione ha un metodo `up()` (cosa creare) e `down()` (come tornare indietro):

```php
public function up(): void {
    Schema::create('clienti', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->enum('tipo', ['azienda', 'privato']);
        $table->string('email')->nullable();
        $table->timestamps();  // created_at e updated_at automatici
    });
}
```

---

## Le route (routes/web.php)

Il file `routes/web.php` definisce tutti gli URL dell'applicazione.

### Route resource
`Route::resource('clienti', ClienteController::class)` crea automaticamente 7 route:

| Metodo HTTP | URL | Metodo Controller | Scopo |
|---|---|---|---|
| GET | /clienti | index | Lista clienti |
| GET | /clienti/create | create | Mostra form creazione |
| POST | /clienti | store | Salva nuovo cliente |
| GET | /clienti/{id} | show | Dettaglio cliente |
| GET | /clienti/{id}/edit | edit | Mostra form modifica |
| PUT | /clienti/{id} | update | Aggiorna cliente |
| DELETE | /clienti/{id} | destroy | Elimina cliente |

### Perché PUT e DELETE nei form HTML?
I browser HTML supportano solo GET e POST. Laravel usa un campo nascosto `_method` per simulare PUT e DELETE:
```blade
<form method="POST" action="/clienti/1">
    @csrf
    @method('DELETE')
    <button type="submit">Elimina</button>
</form>
```

### @csrf
Il token CSRF (Cross-Site Request Forgery) è una protezione di sicurezza: Laravel genera un token unico per ogni sessione, lo include nel form, e lo verifica quando riceve la richiesta. Senza `@csrf`, Laravel rifiuta il form con errore 419.

---

## La Dashboard

### Admin
Usa query con `GROUP BY` per aggregare i dati:

```php
// Pipeline: quante opportunità e quanto valgono per ogni fase
Opportunita::select('fase', DB::raw('count(*) as totale'), DB::raw('sum(valore_stimato) as valore_totale'))
    ->groupBy('fase')
    ->get();

// Top 5 agenti per valore opportunità generate
User::where('role', 'agente')
    ->withSum('opportunita', 'valore_stimato')
    ->orderByDesc('opportunita_sum_valore_stimato')
    ->take(5)->get();
```

### Agente
Vede solo i propri dati filtrati per `user_id`:
```php
$attivita_oggi = Attivita::where('user_id', $user->id)
    ->whereDate('data', today())
    ->get();
```

---

## Il Pool Clienti

Il **Pool** mostra tutti i clienti a cui l'agente corrente NON è ancora assegnato. La query esclude i clienti già nel suo pivot:

```php
// In PoolController
$miei_ids = auth()->user()->clienti()->pluck('clienti.id');

$clienti = Cliente::with('users')
    ->whereNotIn('id', $miei_ids)
    ->paginate(12);
```

Quando l'agente clicca "Aggiungi ai miei clienti", viene chiamato `ClienteController@assign`:
```php
$cliente->users()->attach(auth()->id());
```

---

## Concetti chiave da saper spiegare

1. **Cos'è MVC e come si applica in questo progetto**
   → Model = Eloquent, View = Blade, Controller = logica tra i due

2. **Come funzionano le relazioni nel database**
   → Chiavi esterne, la tabella pivot per molti-a-molti

3. **Cos'è un prepared statement e perché previene SQL Injection**
   → Il testo dell'utente viene separato dal codice SQL, non concatenato

4. **Come funziona l'autenticazione in Laravel**
   → Auth::attempt, sessioni PHP, middleware auth

5. **Cosa fa `php artisan migrate --seed`**
   → Crea le tabelle (migrate) e inserisce dati di esempio (seed)

6. **Perché si usano le migrazioni invece di creare le tabelle a mano**
   → Versionamento del database, riproducibilità, lavoro in team

7. **Come funziona Tailwind CSS**
   → Classi atomiche nell'HTML, compilato da Vite in un unico CSS

8. **Cosa fa il middleware AdminMiddleware**
   → Controlla se l'utente ha role='admin', altrimenti blocca con 403

---

*Questo file è solo per lo studio — non va consegnato.*
