# Relazione Tecnica — CRM Gestionale
**IIS Benedetto Radice · Classe 5AINF · A.S. 2025/26**

---

## 1. Descrizione del progetto

Il progetto consiste in un'applicazione web di tipo **CRM (Customer Relationship Management)**, realizzata con il framework PHP **Laravel 13** e un database relazionale **MySQL**. L'obiettivo è offrire a un'azienda uno strumento per gestire clienti, opportunità commerciali, attività operative e lo storico delle comunicazioni, con un sistema di ruoli che distingue gli amministratori dagli agenti commerciali.

---

## 2. Modello dei dati

Il database è composto da **7 tabelle** con relazioni gestite tramite chiavi esterne:

| Tabella | Descrizione |
|---|---|
| `users` | Utenti del sistema (admin / agente) |
| `clienti` | Anagrafica clienti (aziende e privati) |
| `contatti` | Referenti di un cliente aziendale |
| `opportunita` | Trattative commerciali in corso |
| `attivita` | Task e promemoria legati a clienti o opportunità |
| `interazioni` | Storico delle comunicazioni con i clienti |
| `cliente_user` | Tabella pivot per l'assegnazione multipla di clienti agli agenti |

Le relazioni principali sono:
- Un **cliente** può avere molti contatti, opportunità, attività e interazioni.
- Un **utente (agente)** può essere assegnato a più clienti tramite la tabella pivot `cliente_user` (relazione molti-a-molti).
- Un'**opportunità** appartiene a un cliente e a un agente responsabile.
- Un'**attività** può essere collegata sia a un cliente che a un'opportunità (entrambe le chiavi esterne sono opzionali/nullable).

---

## 3. Architettura e scelte progettuali

### 3.1 Framework — Laravel 13 (MVC)
Si è scelto Laravel per la separazione netta tra logica applicativa e presentazione attraverso il pattern **MVC (Model-View-Controller)**:
- I **Model** (Eloquent ORM) gestiscono l'accesso al database e le relazioni tra entità.
- I **Controller** contengono la logica di business e la validazione degli input.
- Le **View** (template Blade) si occupano esclusivamente della presentazione.

### 3.2 Autenticazione e ruoli
Il sistema di autenticazione è gestito tramite il componente nativo `Auth` di Laravel con **sessioni PHP**. Sono stati definiti due ruoli:
- **Admin**: accesso completo a tutti i dati, dashboard statistica, gestione utenti.
- **Agente**: vede e gestisce solo i clienti a lui assegnati.

Il controllo degli accessi è implementato tramite un **middleware personalizzato** (`AdminMiddleware`) registrato come alias nelle route, e con verifiche inline nei controller per garantire che ogni agente operi solo sui propri dati.

### 3.3 Sicurezza — Prepared Statements
Tutta la comunicazione con il database avviene tramite **Eloquent ORM** e il **Query Builder** di Laravel, che internamente usano PDO con prepared statements su ogni query che accetta input utente. Questo previene efficacemente attacchi di tipo **SQL Injection**. Le password sono salvate con `bcrypt` tramite il casting automatico `'password' => 'hashed'` nel modello.

### 3.4 Condivisione clienti tra agenti
Una funzionalità centrale del sistema è la **tabella pivot `cliente_user`**, che implementa una relazione molti-a-molti tra utenti e clienti. Ogni agente può:
- Visualizzare il **Pool Clienti** (clienti non ancora assegnati a sé).
- Aggiungersi autonomamente come editor di un cliente con un click.
- Rimuoversi dall'assegnazione di un cliente dalla scheda del cliente stesso.

Questo permette la collaborazione tra colleghi senza intervento dell'amministratore.

---

## 4. Funzionalità implementate

- **CRUD completo** su tutte le entità (clienti, contatti, opportunità, attività, interazioni, utenti).
- **Dashboard amministratore** con statistiche aggregate: totale clienti, pipeline commerciale raggruppata per fase (`GROUP BY fase`), classifica agenti per valore opportunità (`withSum`), nuovi clienti mensili (`DATE_FORMAT + GROUP BY`).
- **Dashboard agente** con KPI personali: clienti assegnati, opportunità aperte, valore pipeline, attività del giorno.
- **Timeline interazioni** nella scheda cliente, ordinata cronologicamente.
- **Filtri e ricerca** nella lista clienti (per nome, settore, stato, tipo).
- **Gestione utenti** (solo admin): creazione, modifica ruolo, eliminazione.
- **Interfaccia navigabile** con sidebar fissa, badge colorati per stato/fase, messaggi flash per feedback.

### Query avanzate utilizzate
```php
// Pipeline commerciale con GROUP BY
Opportunita::select('fase', DB::raw('count(*) as totale'), DB::raw('sum(valore_stimato) as valore_totale'))
    ->groupBy('fase')->get();

// Top agenti per valore opportunità
User::where('role', 'agente')
    ->withCount('opportunita')
    ->withSum('opportunita', 'valore_stimato')
    ->orderByDesc('opportunita_sum_valore_stimato')
    ->take(5)->get();

// Clienti per mese (ultimi 6 mesi)
DB::table('clienti')
    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mese"), DB::raw('count(*) as totale'))
    ->where('created_at', '>=', now()->subMonths(6))
    ->groupBy('mese')->orderBy('mese')->get();
```

---

## 5. Tecnologie utilizzate

| Componente | Tecnologia |
|---|---|
| Backend | PHP 8.3, Laravel 13.7 |
| Database | MySQL 8 |
| ORM | Eloquent (Laravel) |
| Frontend | Blade (template engine Laravel) |
| CSS | Tailwind CSS 4 |
| Asset bundling | Vite 8 |
| Server locale | XAMPP / Laravel Artisan serve |

---

## 6. Istruzioni per l'avvio in locale

**Requisiti:** PHP 8.3, Composer, Node.js, MySQL

```bash
# 1. Installa le dipendenze
composer install
npm install

# 2. Crea il database MySQL
# Aprire phpMyAdmin o MySQL e creare il database:
# CREATE DATABASE crm_gestionale CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 3. Configura il file .env (già predisposto)
# Verificare DB_USERNAME e DB_PASSWORD nel file .env

# 4. Esegui le migrazioni e i dati di esempio
php artisan migrate --seed

# 5. Avvia i server (due terminali separati)
npm run dev          # Compila Tailwind CSS
php artisan serve    # Avvia su http://localhost:8000
```

**Credenziali di accesso:**

| Ruolo | Email | Password |
|---|---|---|
| Amministratore | admin@crm.it | password |
| Agente | agente1@crm.it | password |
| Agente | agente2@crm.it | password |
| Agente | agente3@crm.it | password |

---

*Progetto individuale — A.S. 2025/26*
