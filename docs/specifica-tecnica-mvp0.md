# Hub Marketing — Specifica tecnica MVP-0

Versione 0.1 del 30/10/2026. Traduce in struttura tecnica le specifiche funzionali: `catalogo-allerte.md` v0.5, `mappa-applicazione.md`, `decisioni-fase-0.md`. In caso di conflitto valgono quei documenti; questa specifica dice **come** costruirli.

Le parti marcate **Proposta — da confermare** non sono decise.

## 1. Perimetro MVP-0

Incluso:

- anagrafica: clienti, strutture, account sulle piattaforme, assegnazioni per ruolo, calendari di apertura;
- raccolta giornaliera da Google Ads, GA4, Fido, Quoto e Meta, con storico delle raccolte;
- snapshot giornalieri necessari alle regole MVP-0;
- motore delle regole per le allerte e i controlli di diagnostica con fase MVP-0 nel catalogo (BLK-01…06, TRK-01…13, TRK-15, CTR-01…03);
- ciclo di vita delle allerte, deduplica, escalation in orario lavorativo;
- notifiche Teams ed email, riepilogo giornaliero delle 08:30;
- interfaccia: login, overview di portafoglio essenziale, elenco clienti, Centro allerte (Allerte e Diagnostica), Impostazioni.

Escluso (MVP-1 e fasi successive): overview cliente, pagine Google Ads e Meta del cliente, vista operatore, CSV delle richieste e TRK-14, BUD, PRF, PLN, SEO, FCS, Analisi AI. Il modello dati però li predispone dove indicato.

## 2. Architettura

```
            ┌──────────────── Server Hub (Laravel) ────────────────┐
 Connettori │  Scheduler ─► Raccolta ─► Snapshot ─► Motore regole   │
 MCP NS  ◄──┤      │            │                      │           │
 (Ads, GA4, │      │       collection_runs         alerts ─► Notifiche ─► Teams / email
 Fido,      │      │                                   │           │
 Quoto)     │  Escalation (ogni 30 min, orario lav.)   ▼           │
 API Meta ◄─┤                                   Interfaccia web    │
            └──────────────────────── MariaDB ──────────────────────┘
```

| Componente | Scelta |
|---|---|
| Linguaggio e framework | PHP 8.3+, Laravel 13 (Laravel 11 non riceve più correzioni di sicurezza) |
| Database | MariaDB 11.4 (stessa versione di Fido); SQLite in memoria per i test |
| Interfaccia | Blade + Alpine.js + Tailwind CSS 4 (compilati con Vite), font di sistema senza servizi esterni. Nessuna single page app nell'MVP-0: le pagine sono elenchi e tabelle filtrabili. Grafici (MVP-1) con Chart.js |
| Code e job | Laravel Queue su database (nessun Redis richiesto) |
| Schedulazione | Laravel Scheduler, un solo cron di sistema `* * * * * php artisan schedule:run` |
| Login | Google OAuth (Laravel Socialite) limitato al dominio `network-service.it` |
| Notifiche | Teams tramite webhook dei canali (Workflows), email SMTP |
| Fuso orario applicazione | Europe/Rome; ogni account e struttura ha il proprio fuso per definire "il giorno" |

### Accesso ai dati tramite connettori

Google Ads, GA4, Fido e Quoto si leggono tramite i connettori MCP di Network Service (decisione del 30/10/2026). L'Hub è un client MCP: invia richieste JSON-RPC `tools/call` al connettore via HTTP (trasporto "streamable HTTP") e riceve il risultato del tool.

| Fonte | Connettore | Tool usati nell'MVP-0 |
|---|---|---|
| Google Ads | Google Ads NS | `search` (query GAQL), `list_accessible_customers` |
| GA4 | Google Analytics NS | `run_report`, `run_realtime_report`, `list_google_ads_links`, `get_account_summaries` |
| Fido | ERP Fido | `info_sito`, `run_select` (siti, anagrafica, rel_anagra_siti, pms_progetti, pms_attivita, pms_timer), `rinnovi_marketing`, `scadenze_contratti`, `timeline_progetto` |
| Quoto | Quoto Campagne ADS | `lista-strutture`, `serie-storica`, `run-select` (conteggi giornalieri delle richieste, solo aggregati) |
| Meta | API Marketing diretta (Graph API), token "utente di sistema" | stato account, campagne/gruppi/inserzioni, statistiche, eventi del dataset |

Regole del client dei connettori:

- indirizzo e credenziale di ogni connettore in `.env` (`MCP_GOOGLE_ADS_URL`, `MCP_GOOGLE_ADS_TOKEN`, …), mai nel repository;
- timeout 60 s per chiamata, 3 tentativi con attesa crescente (5 s, 20 s, 60 s) solo su errori di rete o 5xx;
- massimo 2 chiamate contemporanee per connettore;
- ogni chiamata registra durata ed esito in `connector_calls` (solo metadati, non il contenuto);
- un'interfaccia `ConnectorClient` con un'implementazione reale e una finta che legge file di esempio (`tests/fixtures/connectors/…`), così tutto lo sviluppo e i test girano senza credenziali.

**Punto aperto**: i connettori sono oggi usati da Claude; va verificato come l'Hub li raggiunge da server (indirizzo, tipo di credenziale, eventuali limiti di chiamate). Finché non è chiarito si sviluppa con il client finto.

Stima delle chiamate giornaliere per 19 strutture: Google Ads circa 12 query per account (≈ 200), GA4 circa 8 report per proprietà (≈ 150), Fido ≈ 30, Quoto ≈ 10, Meta ≈ 60. Totale sotto le 500 chiamate al giorno.

## 3. Modello dati

Nomi in inglese, come da `CLAUDE.md`. Tutte le tabelle hanno `id` e `created_at`/`updated_at` salvo dove indicato. Le migrazioni sono reversibili.

### 3.1 Anagrafica e configurazione

| Tabella | Campi principali | Note |
|---|---|---|
| `clients` | name, notes | Cliente dell'Hub (livello 2) |
| `client_fido_records` | client_id, fido_anagrafica_id | Una o più anagrafiche Fido per cliente (unione decisa dall'admin) |
| `sites` | client_id, fido_site_id (idsito), name, main_domain, site_type (hotel, restaurant_bar, parking, other), timezone, currency, quoto_enabled, stape_enabled, request_source (quoto, csv, platform), newsletter_tool, brand_terms (json), status | Struttura. `fido_site_id` = idsito, la stessa chiave di Quoto |
| `site_aliases` | site_id, fido_site_id, domain | Altri siti Fido della stessa struttura (siti in lingua, landing "offerte.") |
| `platform_accounts` | platform (google_ads, ga4, meta, search_console), external_id, name, timezone, currency, reference_site_id, request_conversion (json), attribution_window, status | `request_conversion`: per Google Ads gli ID delle azioni di richiesta, per GA4 il nome dell'evento, per Meta l'evento del dataset |
| `account_site` | platform_account_id, site_id | Un account può servire più strutture dello stesso cliente |
| `opening_periods` | site_id, closed_from, closed_to, note | Periodi di chiusura stagionale |
| `users` | name, email, google_id, is_admin, is_active, absent_until | Utenti nominativi (proposta P6) |
| `role_assignments` | site_id, user_id, role (account, adv_google, adv_meta, mail_marketing, editor), position (holder, co, substitute) | Titolare, co-assegnatari, sostituto (proposta P4) |
| `threshold_overrides` | site_id, key, value | Soglie diverse da quelle di `config/alerts.php` per una struttura |
| `holidays` | date, name | Festivi e chiusure aziendali; anche da `config/hub.php` |

Le soglie predefinite stanno in `config/alerts.php` (una voce per regola, con i valori del catalogo v0.5). Lettura sempre tramite `Thresholds::for($site, 'blk04.min_prior_spend')`, che applica l'eventuale override della struttura.

### 3.2 Raccolta

| Tabella | Campi principali |
|---|---|
| `collection_runs` | run_date, trigger (schedule, manual, retry), started_at, finished_at, status (running, ok, partial, failed) |
| `collection_run_sources` | collection_run_id, source (google_ads, ga4, fido, quoto, meta), status, duration_ms, records, error_summary, attempts |
| `connector_calls` | source, tool, started_at, duration_ms, ok, error_code |

### 3.3 Snapshot giornalieri

Una riga per giorno e oggetto; si riscrive se la raccolta dello stesso giorno viene ripetuta.

| Tabella | Chiave | Campi |
|---|---|---|
| `gads_account_daily` | account, date | status, cost, clicks, impressions, conversions_request, conversions_total |
| `gads_campaign_daily` | account, campaign_id, date | name, channel_type, campaign_type (brand, generic, pmax, hotel, display, demand_gen, video), status, primary_status, primary_status_reasons (json), start_at, end_at, budget_daily, bidding_strategy, cost, clicks, impressions, conversions_request, search_is, search_budget_lost_is, search_rank_lost_is |
| `gads_ad_issues_daily` | account, date | campagna, gruppo, annuncio, approval_status, motivo (solo annunci attivi rifiutati o limitati; per PMax i gruppi di asset limitati) |
| `gads_conversion_actions` | account, action_id, snapshot_date | name, type, category, origin, primary_for_goal, include_in_conversions_metric, default_value, always_use_default_value, status |
| `ga4_daily` | property, date | sessions, request_events, sessions_with_request_event, key_events_total, campaign_sessions, noncompliant_campaign_sessions |
| `ga4_links` | property, snapshot_date | linked_google_ads_ids (json) |
| `meta_account_daily` | account, date | account_status, disable_reason, cost, clicks, impressions |
| `meta_campaign_daily` | account, campaign_id, date | name, effective_status, active_adsets, active_ads, issues (json), budget_level, end_at, cost, clicks, request_results |
| `meta_dataset_daily` | dataset, date | events_total, request_events, last_event_at |
| `quoto_daily` | site, date | requests_received (solo conteggi) |
| `fido_site_status` | site, snapshot_date | fido_status, marketing_active, contract_end |
| `fido_marketing_projects` | project_id, snapshot_date | site, start_date, end_date, hours_budget_min, hours_worked_min, stato_commerciale |
| `stape_daily` | site, date | server_events (fonte da definire: vedi §10) |

Conservazione: snapshot 25 mesi (servono i confronti con l'anno precedente), allerte 24 mesi, `connector_calls` 90 giorni.

### 3.4 Diagnostica e allerte

| Tabella | Campi principali |
|---|---|
| `check_results` | run_date, check_code (D01…D16), site_id, platform_account_id, outcome (ok, warning, error, suspended, not_applicable), suspended_reason (connector_failed, closed_season, low_volume, warming_up, site_type), data (json) |
| `alerts` | code, severity, category, channel, client_id, site_id, platform_account_id, subject_key (chiave di deduplica), title, trigger_data (json), suggested_action, platform_path, status (new, in_progress, resolved, silenced), assignee_id, opened_at, last_seen_run_date, absent_runs, escalation_level, escalated_at, silenced_until, silence_reason, resolved_at, resolution_note, internal_only (sempre vero nell'MVP-0) |
| `alert_events` | alert_id, user_id (null = sistema), type (created, updated_trigger, assigned, taken, escalated, resolved, auto_resolved, silenced, reopened), from_status, to_status, note, created_at |
| `alert_sites` | alert_id, site_id | Per le allerte su account condivisi: strutture in cui l'allerta compare, contata una volta sola |
| `notifications` | alert_id, channel (teams, email), recipient, reason (created, escalated, digest), sent_at, ok, error |

`subject_key` segue le regole di deduplica del catalogo: per esempio `BLK-05:campaign:123` per una campagna Brand, `BLK-05:account:456` per le altre, `TRK-13:account:456`.

## 4. Raccolta giornaliera

Orari (configurabili in `config/hub.php`):

| Ora | Job |
|---|---|
| 05:30 | `CollectDailyData` per tutte le fonti, in parallelo per fonte |
| 07:15 | `RetryFailedSources`: ripete solo le fonti fallite |
| 07:45 | `EvaluateRules` (anche subito dopo una raccolta manuale) |
| 08:30 | `SendDailyDigest` nei giorni lavorativi |
| ogni 30 min, 08:30–17:30 giorni lavorativi | `EscalateAlerts` |
| 03:00 domenica | `PruneOldData` |

Passi di `CollectDailyData`:

1. crea `collection_runs` e una riga per fonte;
2. per ogni fonte esegue i collector (uno per tabella di snapshot); ogni collector riceve l'elenco degli account attivi e il giorno da raccogliere nel fuso dell'account;
3. Google Ads: D-1 e le finestre 48 h, 7 giorni, 90 giorni chieste con filtro di date senza segmento giornaliero dove servono solo totali (le query segmentate per data omettono le righe a zero: un'assenza vale 0);
4. GA4: D-1 solo per le sessioni (D01), D-2 consolidato per gli eventi; il rapporto in tempo reale solo per le proprietà con 0 sessioni in D-1;
5. una fonte fallita dopo i tentativi diventa `failed` con `error_summary`; le altre proseguono;
6. al termine lo stato del run è `ok`, `partial` o `failed`.

Sync manuale (solo admin): globale o per singola fonte; usa gli stessi job con `trigger = manual` e rilancia la valutazione delle regole.

Storico per il primo avvio: un comando `hub:backfill --days=90` riempie gli snapshot dei 90 giorni precedenti, così D03 e i tassi di conversione non devono aspettare il rodaggio di 28 giorni.

## 5. Motore delle regole

Ogni regola del catalogo è una classe:

```php
interface Rule
{
    public function code(): string;          // 'BLK-04'
    public function phase(): string;         // 'mvp0'
    public function sources(): array;        // ['google_ads'] per la sospensione su fonte fallita
    public function scope(): Scope;          // Site, Account o Campaign
    /** @return Finding[] */
    public function evaluate(EvaluationContext $ctx): array;
}
```

`Finding` contiene: soggetto (per `subject_key`), gravità, titolo, dato trigger, azione suggerita con percorso nella piattaforma, dato da controllare dopo l'intervento.

Sequenza di `EvaluateRules` per ogni regola attiva e ogni oggetto:

1. **sospensioni** (catalogo §1), nell'ordine: fonte fallita nel run, periodo di chiusura, tipo struttura non pertinente, volume troppo basso, rodaggio. Una regola sospesa registra `check_results.outcome = suspended` con il motivo e **non tocca le allerte aperte** (non le risolve);
2. **valutazione** con le soglie da `Thresholds`;
3. **allerte**: per ogni `Finding` cerca un'allerta aperta con la stessa `subject_key`; se esiste aggiorna dato trigger e `last_seen_run_date`, altrimenti la crea (stato `new`, assegnatario da §6) e programma la notifica;
4. **risoluzione automatica**: un'allerta aperta senza `Finding` corrispondente incrementa `absent_runs`; a 2 diventa `resolved` con evento `auto_resolved` e avviso nell'hub a chi l'aveva in carico. Le allerte silenziate restano silenziate fino alla scadenza.

Regole MVP-0 e dati usati:

| Regola | Snapshot | Note di implementazione |
|---|---|---|
| BLK-01 | `meta_account_daily` | account_status ≠ attivo o disable_reason di pagamento |
| BLK-02 | `gads_account_daily` | stato account; fatturazione da verificare (§10) |
| BLK-03 | `gads_ad_issues_daily`, `meta_campaign_daily` | solo annunci e inserzioni attivi; PMax tramite gruppi di asset |
| BLK-04 | `gads_campaign_daily`, `meta_campaign_daily` | spesa D-1 = 0, spesa 7 giorni precedenti ≥ 20 €, almeno un gruppo/inserzione attivi |
| BLK-05 | `gads_campaign_daily` | `BUDGET_CONSTRAINED`; Brand per campagna (Alta), altri per account (Media) |
| BLK-06 | `gads_campaign_daily`, `meta_campaign_daily` | `end_at` entro 3 giorni |
| TRK-01 | `ga4_daily` | D-1 = 0 e tempo reale = 0; sospesa sotto 5 sessioni/giorno di media |
| TRK-02 | `ga4_daily` | D-2, evento configurato con media ≥ 1/giorno |
| TRK-03 | `ga4_daily` | test picco (media ≥ 3) e test eventi per sessione (> 1,3) |
| TRK-04 | `gads_campaign_daily` | clic Search/PMax/Hotel nelle 48 h ≥ click minimi; conversioni dell'azione di richiesta |
| TRK-05, TRK-06 | `meta_dataset_daily`, `ga4_daily` | traffico minimo come da catalogo |
| TRK-08 | `stape_daily` | solo strutture con Stape |
| TRK-09 | `ga4_daily` | ≥ 100 sessioni da campagne nel mese; gclid e google/cpc "(not set)" conformi |
| TRK-10 | `quoto_daily` | solo strutture con Quoto |
| TRK-11 | — | Search Console non è raccolta nell'MVP-0: rinviata a MVP-1 (**Proposta — da confermare**) |
| TRK-12 | `collection_run_sources` | una allerta per fonte fallita |
| TRK-13 | `gads_conversion_actions` | `include_in_conversions_metric`; una allerta per account con l'elenco |
| TRK-15 | `ga4_links` e azioni GA4 in `gads_conversion_actions` | collegamento assente da entrambi i lati |
| CTR-01 | `fido_marketing_projects` | rinnovo o fine progetto marketing entro 30 giorni |
| CTR-02 | `fido_site_status` | sito cessato in Fido con marketing attivo nell'Hub |
| CTR-03 | `fido_marketing_projects` | vedi sotto |

**CTR-03 — Proposta — da confermare.** Il catalogo parla di "ore del mese", ma Fido gestisce il monte ore per progetto (`pms_attivita.monte_ore` contro `pms_timer.tempo_impiegato`, tool `timeline_progetto`), non per mese. Proposta: l'allerta scatta quando la percentuale di ore lavorate supera di oltre 20 punti la percentuale di durata del progetto già trascorsa (es. 70% delle ore usate al 45% del periodo), oppure quando le ore lavorate superano il monte ore. Da confermare e riportare nel catalogo prima di implementarla.

Ogni regola ha un test con i casi della prova a secco del 30/10/2026 come regressione (§9).

## 6. Assegnazione, escalation e notifiche

**Assegnazione** (proposte P4 e P5): ruolo destinatario dal catalogo → struttura dell'allerta (per gli account condivisi la struttura di riferimento) → titolare in `role_assignments`; se il titolare ha `absent_until` futuro, il sostituto. I co-assegnatari vedono l'allerta in "Assegnate a me" ma non ricevono la notifica.

**Orologio lavorativo.** Un servizio `BusinessClock` calcola le ore lavorative tra due istanti: 08:30–17:30, lunedì–venerdì, esclusi `holidays`. Esempio di test: allerta aperta venerdì alle 18:00 → 24 ore lavorative raggiunte mercoledì alle 14:30 (lunedì 9 h + martedì 9 h + mercoledì 6 h). L'escalation usa l'età in ore lavorative, non l'età di calendario.

**Escalation** (`EscalateAlerts`): per ogni allerta `new`:

- Info: nessuna escalation;
- 24 ore lavorative: +1 livello (Media → Alta; Alta → Critica solo per la categoria TRK, le altre restano al massimo Alta);
- 48 ore lavorative: almeno Alta; categoria TRK → Critica;
- ogni passaggio crea `alert_events.escalated` e una notifica.

**Notifiche**, solo su creazione ed escalation:

| Gravità | Giorno lavorativo | Weekend e festivi |
|---|---|---|
| Critica | Teams + email all'assegnatario subito | email alla casella presidiata subito |
| Alta | riepilogo delle 08:30 | riepilogo del primo giorno lavorativo |
| Media, Info | solo nell'hub | solo nell'hub |

Destinatario a cascata: assegnatario → casella di settore → casella generale (`config/hub.php`). I canali Teams sono uno per area. Il riepilogo delle 08:30 è un messaggio per persona con le sue allerte nuove o salite di livello.

## 7. Interfaccia MVP-0

| Pagina | Contenuto | Chi |
|---|---|---|
| Login | Accesso con Google aziendale | Tutti |
| Overview di portafoglio | Esito dell'ultima raccolta; allerte Critiche e Alte aperte; diagnostica per struttura (griglia struttura × controllo con OK/errore/sospeso); filtro "Le mie strutture / Tutte" | Tutti |
| Clienti | Elenco clienti e strutture con numero di allerte aperte e stato dei canali | Tutti |
| Centro allerte | Schede Allerte (Assegnate a me, Tutte, Silenziate e risolte) e Diagnostica; filtri gravità, categoria, canale, cliente, stato; dettaglio con dato trigger, storico, azioni: prendi in carico, riassegna, risolvi (nota obbligatoria), silenzia (motivo, scadenza) | Tutti, con i permessi della tabella §6 di decisioni-fase-0 |
| Impostazioni › Connettori | Esito dell'ultima raccolta per fonte, storico raccolte, Sync globale e per fonte | Admin |
| Impostazioni › Clienti e strutture | Clienti, unione anagrafiche Fido, strutture, sito principale, tipo, fuso, periodi di chiusura, termini brand, fonte richieste | Admin |
| Impostazioni › Account | Account per piattaforma, strutture servite, struttura di riferimento, azione o evento di richiesta | Admin |
| Impostazioni › Utenti e assegnazioni | Utenti, admin, assenze, assegnazioni per ruolo | Admin |
| Impostazioni › Soglie | Valori predefiniti (sola lettura) e override per struttura | Admin, Account per i propri clienti |

Stile: tema scuro predefinito e chiaro predisposto, colori del restyling NS (blu notte, accento magenta), foglio di stile condiviso `ns-hub.css`. Testi delle allerte nel formato canale · problema · conseguenza.

## 8. Sicurezza

- Login solo Google con dominio `network-service.it`; nessuna password locale.
- Autorizzazioni con Laravel Policies secondo la tabella permessi di `decisioni-fase-0.md` §6.
- Registro accessi e modifiche: `alert_events` per le allerte; `audit_log` (utente, azione, oggetto, prima/dopo) per anagrafica, assegnazioni e soglie.
- Credenziali solo in `.env` sul server; `.env.example` nel repository senza valori.
- Nessuna scrittura verso Fido, Quoto o le piattaforme pubblicitarie: i client dei connettori espongono solo metodi di lettura.
- Dati di Quoto solo aggregati: nessun campo personale negli snapshot.
- HTTPS obbligatorio, cookie di sessione sicuri, protezione CSRF standard di Laravel.

## 9. Test

- **Unit test di ogni regola** con snapshot di esempio. Casi minimi per ogni regola: scatta, non scatta, sospesa.
- **Regressione dalla prova a secco** (dati anonimizzati negli ID di campagna, in `tests/fixtures/dry-run-2026-10-30/`):
  - BLK-04 non scatta su "cruise travelers" di Airport (1,49 € in 7 giorni) né sul Brand EN di Stella Marina;
  - TRK-04 scatta su Tramonto d'Oro (182 clic, 0 conversioni) e non su Il Corallo (clic Display) né su Stella Marina;
  - TRK-01 sospesa su Stella Marina (media sotto 5 sessioni al giorno);
  - TRK-03 scatta su Tramonto d'Oro per 3 eventi in una sessione, non per il picco su media 0,25;
  - BLK-05: una allerta Alta per i Brand di Primula e Galli's, una Media per account per le altre;
  - TRK-13 scatta su tutti gli 8 account.
- **BusinessClock**: weekend, festivi, allerta aperta fuori orario.
- **Ciclo di vita**: deduplica, risoluzione dopo 2 raccolte, silenziata che non notifica, allerta su account condiviso contata una volta.
- **Client finti dei connettori** per la raccolta; nessun test chiama servizi reali.
- I test girano a ogni push (GitHub Actions) su PHP 8.3 con SQLite e una volta con MariaDB.

## 10. Punti aperti tecnici

| Punto | Serve per |
|---|---|
| Accesso dei connettori MCP da server: indirizzo, credenziale, limiti | Raccolta reale |
| Token Meta "utente di sistema" e ID dei dataset per struttura | BLK-01, TRK-05, TRK-06, BLK-03/04/06 Meta |
| Fonte dei dati Stape (API Stape o eventi server-side in GA4) per le 3 strutture con Stape | TRK-08 |
| Fatturazione Google Ads leggibile dal connettore (`billing_setup`, `account_budget`) | BLK-02 |
| Webhook Teams per area, server SMTP, casella presidiata nel weekend | Notifiche |
| Client OAuth Google per il login | Login |
| CTR-03 riformulata (§5) | CTR-03 |
| TRK-11 in MVP-0 o MVP-1 | TRK-11 |
| Server di produzione | Rilascio |

## 11. Ordine di sviluppo

Ogni passo è un branch con test, rivisto da Mariano prima dell'unione.

1. **Base**: progetto Laravel, login Google, layout e stile, CI con i test.
2. **Anagrafica**: migrazioni §3.1, pagine Impostazioni, comando `hub:import-pilot` che crea clienti, strutture e account delle 19 strutture pilota da Fido (connettore) e dagli ID trovati nella prova a secco.
3. **Raccolta**: client dei connettori (reale e finto), collector, `collection_runs`, Sync, backfill.
4. **Motore delle regole**: sospensioni, regole MVP-0, `check_results`, allerte, test di regressione.
5. **Centro allerte e overview**: pagine §7 con azioni sul ciclo di vita.
6. **Escalation e notifiche**: `BusinessClock`, Teams, email, riepilogo.
7. **Prova in parallelo**: due settimane di raccolta reale con notifiche verso la sola casella di Mariano, per misurare allerte vere e false prima di attivarle al team.
