# Hub Marketing

Applicazione web interna di Network Service: l'hub unico delle attività di marketing sui clienti hotel. Riunisce per cliente piani marketing, Fido, Quoto, Google Ads, Meta Ads, GA4, Search Console, RankScale, Simple Booking e Stape, per:

- verificare se il lavoro pianificato viene svolto nei tempi;
- intercettare subito i problemi sulle campagne e sui dati;
- monitorare l'andamento di ogni cliente.

## Stato

Fase di specifica completata (catalogo allerte v0.5). Il codice non è ancora iniziato: il primo rilascio è l'**MVP-0** (anagrafica e collegamento account, raccolta giornaliera, Centro allerte di blocco, tracciamento e contratti, notifiche, overview di portafoglio essenziale).

## Documenti

| File | Contenuto |
|---|---|
| [docs/descrizione-progetto.md](docs/descrizione-progetto.md) | Obiettivo, tre livelli, funzioni, fonti, architettura, roadmap, rischi |
| [docs/catalogo-allerte.md](docs/catalogo-allerte.md) | Regole delle allerte e della diagnostica tracciamento, soglie, fasi |
| [docs/mappa-applicazione.md](docs/mappa-applicazione.md) | Pagine, navigazione, configurazione, specifica della pagina Google Ads |
| [docs/decisioni-fase-0.md](docs/decisioni-fase-0.md) | Decisioni prese, clienti pilota, ruoli e permessi, stack, punti aperti |
| [docs/specifica-tecnica-mvp0.md](docs/specifica-tecnica-mvp0.md) | Architettura, modello dati, raccolta, motore delle regole, test e ordine di sviluppo dell'MVP-0 |
| [docs/registro-revisioni.md](docs/registro-revisioni.md) | Modifiche v0.4 e v0.5 con motivazione e proposte da confermare |
| [docs/analisi-replit-visual-impact.md](docs/analisi-replit-visual-impact.md) | Analisi del mockup Visual Impact a cui il progetto si ispira |
| [docs/report/prova-a-secco-mvp0.html](docs/report/prova-a-secco-mvp0.html) | Prova a secco delle allerte MVP-0 su dati reali (30/10/2026) |

## Stack previsto

PHP 8.3+ con Laravel, MariaDB dedicato con snapshot giornalieri, frontend JavaScript, raccolta dati una volta al giorno prima delle 08:30. Fido e Quoto in sola lettura. Il codice è generato con Claude, senza il programmatore di Fido e Quoto; i rilasci sono approvati da Mariano.
