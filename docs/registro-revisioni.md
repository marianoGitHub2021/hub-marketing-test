# Dashboard Hub Marketing — Registro revisioni v0.4

30/10/2026. Revisione dei documenti v0.3 (22/10/2026) dopo l'analisi incrociata di descrizione-progetto.md, catalogo-allerte.md, mappa-applicazione.md, decisioni-fase-0.md, analisi-replit-visual-impact.md e dei 15 screenshot di Visual Impact.

File aggiornati: descrizione-progetto.md (D) · catalogo-allerte.md (C) · mappa-applicazione.md (M) · decisioni-fase-0.md (F). analisi-replit-visual-impact.md non cambia. anagrafica-strutture-pilota.xlsx non è stato esaminato: i campi da aggiungere sono in F §2.

## Decisioni prese il 30/10/2026

| # | Decisione | File |
|---|---|---|
| 1 | Escalation unica (catalogo): 24h lavorative +1 livello; 48h Alta, tracciamento → Critica; ore contate solo 08:30–17:30 dei giorni lavorativi; allerte già Critiche notificate subito anche nel weekend; Info non salgono; nessuna escalation propria per singola allerta | C §1, M, F §5 |
| 2 | Newsletter: strumenti di invio diversi per struttura; strumento in anagrafica; invio registrato a mano o con CSV, clic da GA4; API solo per gli strumenti più usati | F §4, C (PRF-08, PLN-04), D |
| 3 | Stato attività: le attività approvate del piano diventano task in Fido; l'Hub legge stato e scadenze da Fido; l'Hub scrive su Fido solo per creare questi task | F §3 e §7, C (PLN), D, M |
| 4 | Rilascio: MVP-0 ridotto, poi MVP-1 | D, M, C (colonna Fase), F (punti aperti) |

## Proposte da confermare

Inserite nei documenti con l'etichetta **Proposta — da confermare**.

| # | Proposta | Rilievo | File |
|---|---|---|---|
| P1 | Budget atteso: documento → importo del piano → anno precedente → BUD-04 | 1 | C (BUD), M, F §3, D |
| P2 | Permessi allerte: prendere in carico/riassegnare per tutti; risolvere e silenziare per la propria area; riga "Silenziare" aggiunta | 3 | F §6 |
| P3 | Totale richieste: "valore più alto" per singolo canale; totale struttura = Quoto/CSV oppure totale GA4 dell'evento di richiesta; modello di attribuzione sempre indicato; giorni non coperti dal CSV mostrati come tali | 13 | C §1 |
| P4 | Più persone sullo stesso ruolo: titolare (primo nome) + co-assegnatari + sostituto in caso di assenza | 19 | C §1, F §2 |
| P5 | Account condivisi: "struttura di riferimento" per l'assegnazione | 20 | C §1, F §2 |
| P6 | Admin nominativi con login Google; webmarketing@ solo casella | 21 | F §6 |
| P7 | BUD-01 anticipata in MVP-1 | 28 | C (BUD), M |
| P8 | Nuova BLK-08: budget Brand esaurito prima delle 18 in 3 giorni su 7 | 17 | C (BLK) |
| P9 | Nuova SEO-04: visibilità AI (RankScale) in calo | 38 | C (SEO) |

## Rilievi e modifiche

### A. Incoerenze tra documenti

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 1 | Budget atteso: il piano ("Attiva (10€/gg)") non era nella cascata | Proposta P1 | C, M, F, D |
| 2 | Escalation diversa tra catalogo, mappa e TRK-02 | Decisione 1; mappa rimanda al catalogo; TRK-02 e PLN-02 senza escalation propria | C, M |
| 3 | Permessi allerte in conflitto; manca "Silenziare" | Proposta P2 | F §6 |
| 4 | Pagina Google Ads MVP con blocchi di Fase 2/3 | Tabella "Blocchi della pagina e fase" | M |
| 5 | Fatturato nel funnel per tutti vs solo Quoto | Funnel con "solo Quoto" esplicito | D, F §4 |

### B. Regole che generano falsi allarmi

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 6 | Escalation nel weekend | Decisione 1 (ore lavorative) | C §1, F §5 |
| 7 | Connettore fallito apre allerte a cascata | Sezione "Sospensione dei controlli", punto 1; TRK-12 sospende i dipendenti | C §1, F §8 |
| 8 | GA4 incompleto alle 08:30 | D01 su D-1 confermato col tempo reale; D02/D03 sul giorno consolidato D-2; sezione "Giorni valutati" | C §1, §2 |
| 9 | Soglie basse D04, D06, D07 | "Click minimi" = max(30; 3 ÷ tasso di conversione 90gg); D06 solo con traffico ≥ 20 sessioni | C §2 |
| 10 | D03 senza storico | Rodaggio 28 giorni o recupero storico al primo caricamento | C §1, §2 |
| 11 | D05 dentro TRK-04 (Critica) | Nuova TRK-15, Media | C §2, §3 |

### C. Lacune dati

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 12 | Quoto solo su 2/19 strutture | Funnel limitato; newsletter con decisione 2; rischio aggiunto | D, F §4 |
| 13 | Totale richieste tra canali e attribuzione | Proposta P3 | C §1, M |
| 14 | Date campagna Google Ads non lette | Nota `start_date_time`/`end_date_time`; BLK-06 Google "da verificare"; punto aperto | C, M, F |
| 15 | Metasearch Trivago/Tripadvisor | Indicato "solo conferma manuale (non automatizzabile)" | F §3 |
| 16 | Fuso orario e valuta | Campi in anagrafica e configurazione account; giorno valutato nel fuso dell'account | F §2, M, C §1 |
| 17 | Budget esaurito in giornata senza codice | Proposta P8 (BLK-08) | C |
| 18 | Privacy Quoto | Solo dati aggregati negli snapshot | F §1, D |

### D. Modello dati e assegnazioni

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 19 | Ruoli multipli e sostituto | Proposta P4 | C §1, F §2 |
| 20 | Account condivisi con assegnatari diversi | Proposta P5 | C §1, F §2 |
| 21 | Admin generico annulla l'audit | Proposta P6 | F §6 |
| 22 | idcliente = id anagrafica Fido | Primo punto aperto, "Inizio MVP-0" | F |
| 23 | Strutture non hotel | Campo "tipo struttura"; controlli sospesi | F §2, C §1, M |
| 24 | Storico raccolte connettori | Sezione Connettori | M, F §8 |

### E. Quota impressioni e screenshot Visual Impact

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 25 | Demand Gen e Video assenti | Aggiunti come "non applicabile" | C (BUD), M |
| 26 | Hotel Ads nel mercato non acquisito € | Escluso dal calcolo, insieme a Demand Gen e Video | C, M |
| 29 | Strutture stagionali | Calendario di apertura; sospensione dei controlli; baseline dopo la riapertura | C §1, F §2, M, D |
| 30 | Camere totali per il forecast | Campo in anagrafica; nota in FCS | F §2, C |
| 31 | Elementi senza fonte in Visual Impact | Sezione "Fuori perimetro" | M, C §4, D |
| 32 | Brief per specialista | Percorso nella piattaforma + "Da controllare dopo l'intervento" nel contenuto dell'allerta | C §1 |
| 33 | Materiali del cliente | Versione, autore, data, "Solo NS"/condiviso | M, F §1 |
| 34 | Stato Meta solo sulla campagna | Stato su campagna, gruppo e inserzione; BLK-03/04 riformulate | C (BLK), M |
| 35 | Frequenza Meta unica | PRF-05 per tipo (prospezione 4,0 · retargeting 8,0) | C (PRF) |
| 36 | UTM e Google Ads | gclid conforme in D09/TRK-09 | C, F §4 |
| 37 | Brand riconosciuto solo dal nome campagna | Termini brand per struttura | F §2, C (BUD), M |
| 38 | RankScale non dà posizioni SERP | SEO-02 su Search Console; proposta P9 (SEO-04) | C (SEO) |
| 39 | Funnel Quoto con "inviati" | Funnel richieste → preventivi inviati → confermate → fatturato | D, F §4 |
| 40 | Fonte newsletter | Decisione 2 | F §4 |
| 41 | Fido come fonte dello Stato attività | Decisione 3 | F §3, D |
| 42 | Ore del contratto | Nuova CTR-03 (MVP-0) | C (CTR), F §1 |
| 43 | Configurazione per account | Sezione "Per account" (azione/evento di richiesta, finestra di attribuzione) | M, F §2 |
| 44 | Sync per connettore | Sync globale e per connettore, solo admin | M, C (TRK-12) |
| 45 | Stile | ns-hub.css con i colori del restyling NS | M |

### F. Ampiezza MVP

| # | Rilievo | Modifica | File |
|---|---|---|---|
| 27 | MVP troppo ampio | Decisione 4 (MVP-0 / MVP-1) | D, M, C, F |
| 28 | BUD-01 utile subito | Proposta P7 | C, M |

## Altre correzioni

- Esempio Galli's del mercato non acquisito ricalcolato: 6.646 ÷ 10,3% ≈ 64.500 potenziali (era 64.700) → ≈ 630 € (era 640 €).
- Quota Brand pesata: escluse le campagne con quota "< 10%" (impressioni potenziali non calcolabili).
- PRF-07: confronto solo tra strutture dello stesso tipo e con la stessa fonte delle richieste.
- Centro allerte: due schede, Allerte e Diagnostica tracciamento.
- Connettori: stato come esito dell'ultima raccolta giornaliera, non "minuti fa".
- Punti aperti riallineati alle fasi MVP-0 / MVP-1 e integrati (date campagna, lettura Fido, scrittura task, campi anagrafica, censimento newsletter).

---

# Revisione v0.5 del catalogo allerte

30/10/2026. Correzioni nate dalla prova a secco su dati reali (Google Ads di 8 strutture, GA4 di 6): con le regole v0.4 scattavano 23 allerte, 4 delle quali false. Report: pagina "Prova a secco MVP-0".

| # | Allerta | Problema emerso | Correzione v0.5 |
|---|---|---|---|
| C1 | BLK-04 | Scattava su campagne quasi ferme (1,49 € e 0,57 € in 7 giorni) | Solo con spesa ≥ 20 € nei 7 giorni precedenti |
| C2 | TRK-04 (D04) | Clic di remarketing Display a pochi centesimi; tasso gonfiato dalle micro-conversioni | Solo clic Search/PMax/Hotel; tasso calcolato sull'azione di richiesta |
| C3 | TRK-01 (D01) | Sessioni a zero a fine stagione, con calo graduale | Non valutato sotto 5 sessioni al giorno di media su 28 giorni |
| C4 | TRK-03 (D03) | "Picco" di 12 volte su una media di 0,25 | Picco solo con media ≥ 3; test separato eventi per sessione > 1,3 |
| C5 | BLK-05 | 9 allerte su 5 account, molte su tetti di budget voluti | Alta solo Brand; altre Media, una per account con l'elenco |
| C6 | BLK-03 | Motivo di campagna "annunci rifiutati" anche con annunci in pausa | Approvazione dei singoli annunci attivi; per PMax gruppi di asset limitati |
| C7 | TRK-02 (D02) | Con l'evento sbagliato (2 al mese) scatterebbe ogni giorno | Evento di richiesta configurato; valutato solo con media ≥ 1 al giorno |
| C8 | TRK-09 (D09) | Percentuali instabili su 19 sessioni; google/cpc "(not set)" contato come errore | Minimo 100 sessioni da campagne nel mese; google/cpc "(not set)" conforme |

Altre modifiche:

- BLK-06 Google passa in MVP-0: i campi `campaign.start_date_time` ed `campaign.end_date_time` sono leggibili. Punto aperto chiuso (decisioni-fase-0.md, mappa-applicazione.md).
- Nuova sospensione "volume troppo basso" nelle regole generali (catalogo §1).
- Deduplica: BLK-05 non Brand e TRK-13 una allerta per account.
- Note tecniche per lo sviluppatore sotto la sezione BLK; nuova sezione §4 "Prova a secco".
- Nuovi punti aperti in decisioni-fase-0.md: pulizia delle conversioni Google Ads e verifica del tracciamento GA4 di Villa Eos, Stella Marina e Il Corallo, entrambi prima del rilascio MVP-0.
