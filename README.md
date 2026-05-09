# CRM Gestionale

Applicazione web per la gestione di clienti, opportunità commerciali, attività e comunicazioni.
Realizzata con Laravel 13, MySQL e Tailwind CSS 4.

**IIS Benedetto Radice · Classe 5AINF · A.S. 2025/26**

---

## Requisiti

- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8+

---

## Installazione

### 1. Installa le dipendenze

```bash
composer install
npm install
```

### 2. Crea il database MySQL

In phpMyAdmin o da terminale MySQL:

```sql
CREATE DATABASE crm_gestionale CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Configura il file `.env`

Il file `.env` è già predisposto. Se necessario, modifica le credenziali MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crm_gestionale
DB_USERNAME=root
DB_PASSWORD=          ← inserisci la tua password se presente
```

### 4. Esegui le migrazioni e il seeder

```bash
php artisan migrate --seed
```

Questo crea tutte le tabelle e inserisce i dati di esempio (utenti, clienti, opportunità, ecc.).

### 5. Avvia l'applicazione

Apri **due terminali separati**:

```bash
# Terminale 1 — compila Tailwind CSS
npm run dev

# Terminale 2 — avvia il server PHP
php artisan serve
```

Apri il browser su **http://localhost:8000**

---

## Credenziali di accesso

| Ruolo | Email | Password |
|---|---|---|
| Amministratore | admin@crm.it | password |
| Agente | agente1@crm.it | password |
| Agente | agente2@crm.it | password |
| Agente | agente3@crm.it | password |

---

## Funzionalità principali

- **Gestione clienti** — CRUD completo con filtri per stato, tipo e settore
- **Pool clienti** — ogni agente può auto-assegnarsi clienti condivisi tra colleghi
- **Contatti** — referenti aziendali legati a un cliente
- **Opportunità** — trattative con fasi (nuovo / in trattativa / vinto / perso) e valore stimato
- **Attività** — task e promemoria (chiamata / email / incontro) collegati a clienti o opportunità
- **Interazioni** — timeline storico comunicazioni per ogni cliente
- **Dashboard admin** — statistiche aggregate, pipeline commerciale, classifica agenti
- **Gestione utenti** — solo admin può creare, modificare e eliminare utenti

---

## Struttura del progetto

```
app/
├── Http/
│   ├── Controllers/        # AuthController, DashboardController, ClienteController, ...
│   └── Middleware/         # AdminMiddleware
├── Models/                 # User, Cliente, Contatto, Opportunita, Attivita, Interazione
database/
├── migrations/             # 7 tabelle + pivot cliente_user
└── seeders/                # DatabaseSeeder con dati di esempio
resources/views/
├── layouts/app.blade.php   # Layout principale con sidebar
├── auth/                   # Login
├── dashboard/              # Dashboard admin e agente
├── clienti/                # CRUD clienti
├── contatti/               # CRUD contatti
├── opportunita/            # CRUD opportunità
├── attivita/               # CRUD attività
├── interazioni/            # CRUD interazioni
├── users/                  # Gestione utenti (solo admin)
└── pool/                   # Pool clienti condivisi
routes/web.php              # Tutte le route con middleware auth/admin
```

---

Per la documentazione tecnica vedere [RELAZIONE_TECNICA.md](RELAZIONE_TECNICA.md).
