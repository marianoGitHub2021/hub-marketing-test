# Istruzioni per Claude — Hub Marketing

## Progetto

Hub interno di Network Service per le attività di marketing sui clienti hotel. Le specifiche sono in `docs/`; sono la fonte di verità per regole, soglie e fasi. Leggi il documento pertinente prima di implementare o modificare una funzione.

- Regole delle allerte: `docs/catalogo-allerte.md` (versione corrente v0.5).
- Pagine e configurazione: `docs/mappa-applicazione.md`.
- Decisioni, ruoli, permessi, stack, punti aperti: `docs/decisioni-fase-0.md`.
- Storico delle modifiche alle specifiche: `docs/registro-revisioni.md`.

## Regole di lavoro

- Lingua: documenti, interfaccia e messaggi delle allerte in italiano. Codice, nomi di tabelle e variabili in inglese.
- Una modifica alle regole di business va prima nelle specifiche (`docs/`) e nel registro revisioni, poi nel codice.
- Le soglie non si scrivono nel codice: stanno nel file di configurazione e sono modificabili per struttura.
- Le parti marcate "Proposta — da confermare" non vanno implementate come definitive senza conferma.
- Fido e Quoto si leggono in sola lettura. Unica eccezione prevista (Fase 2): creazione dei task di piano in Fido.
- Nessuna azione automatica sulle piattaforme pubblicitarie (budget, campagne, annunci).
- Dati di Quoto solo aggregati: nessun dato personale negli snapshot.
- Credenziali e token solo lato server, mai nel repository né nel frontend.

## Rilasci

- Repository Git, ambiente di prova separato.
- Il codice lo genera Claude, senza passare dal programmatore di Fido e Quoto (decisione del 30/10/2026). Mariano rivede e approva ogni rilascio.
- Sicurezza, test e migrazioni del database sono quindi responsabilità del codice stesso: test automatici per il motore delle regole, migrazioni reversibili, nessun accesso in scrittura a Fido o Quoto.
- Si lavora su branch; nessun push diretto sul branch principale per il codice.

## Stack

PHP 8.3+ con Laravel 13, MariaDB (SQLite in sviluppo e nei test), Blade + Alpine.js + Tailwind CSS 4 con Vite, raccolta dati schedulata una volta al giorno prima delle 08:30.

Comandi utili:

- `php artisan test` — tutti i test (devono passare prima di ogni commit);
- `vendor/bin/pint` — formattazione del codice (la CI esegue `pint --test`);
- `npm run build` — compila CSS e JS;
- `php artisan hub:make-admin email@network-service.it` — assegna il ruolo di admin.

In locale si accede senza Google impostando `HUB_DEV_LOGIN=true` nel `.env`; l'accesso di sviluppo è ignorato fuori dagli ambienti `local` e `testing`.

Nell'ambiente cloud di Claude GitHub blocca il download degli zip dei pacchetti: Composer ripiega sui clone git. Per contenerne il peso usare un wrapper di `git` che aggiunge `--filter=blob:none` ai clone.

I dati di Google Ads, GA4, Fido e Quoto si leggono tramite i connettori MCP di Network Service (nessun accesso diretto ai database). Meta tramite API Marketing diretta. Dettagli in `docs/specifica-tecnica-mvp0.md`.

## Note sulle API (dalla prova a secco del 30/10/2026)

- Google Ads: date campagna con `campaign.start_date_time` / `campaign.end_date_time`; conversioni contate con `conversion_action.include_in_conversions_metric`; le query segmentate per data non restituiscono righe a zero.
- GA4: i dati di D-1 alle 08:30 sono incompleti; i controlli (tranne D01) usano il giorno consolidato D-2.
