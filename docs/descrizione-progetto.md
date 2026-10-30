# Dashboard Hub Marketing — Descrizione del progetto

Aggiornato al 30/10/2026 (v0.4; precedente 22/10/2026). Le decisioni di dettaglio sono in decisioni-fase-0.md, mappa-applicazione.md e catalogo-allerte.md; le modifiche della v0.4 in registro-revisioni.md.

## Obiettivo

Realizzare un'applicazione web che sia l'hub unico delle attività di marketing di Network Service sui clienti hotel. Un solo punto per:

- verificare se il lavoro pianificato viene svolto nei tempi;
- intercettare subito i problemi sulle campagne e sui dati;
- monitorare l'andamento di ogni cliente.

Oggi queste informazioni sono sparse tra piani marketing in Word/PDF, ERP Fido, CRM Quoto, Google Ads, Meta Ads, GA4, Search Console, RankScale, Simple Booking, Stape e gli strumenti di invio newsletter. L'hub le riunisce e le collega per cliente.

## Come è organizzata: tre livelli

| Livello | Contenuto | Per chi soprattutto |
|---|---|---|
| 1 · Overview di portafoglio | Tutte le strutture: priorità di oggi, allerte di tutti i progetti, diagnostica, indicatori, attività di piano | Account / project manager |
| 2 · Overview cliente | Un cliente con tutte le sue strutture: indicatori, allerte, stato dei canali, piano, account collegati | Tutti i ruoli |
| 3 · Sezione del cliente | Google Ads, Meta Ads, Newsletter, Allerte, Stato attività, SEO, Forecast di quel cliente | Specialista dell'area |

Il livello 2 è il cliente (idcliente), con un filtro per struttura. Gli account condivisi tra strutture dello stesso cliente compaiono una volta sola.

A parte esiste una vista operatore per canale (es. Google Ads di tutte le strutture assegnate), secondaria rispetto ai tre livelli.

## Le tre funzioni, presenti a ogni livello

### Stato attività (Fase 2)

Confronta le attività previste dal piano marketing (newsletter, campagne, landing, contenuti, SEO) con le evidenze di esecuzione. Le attività approvate del piano diventano task in Fido, da cui l'Hub legge stato e scadenze; le evidenze vengono dalle piattaforme (campagne attive su Meta/Google, invii newsletter registrati) e dai task Fido chiusi.

- Stati: in tempo · a rischio · scaduta non eseguita · eseguita in ritardo · eseguita fuori piano.
- KPI principale: % di scadenze rispettate.

### Allerte (MVP-0)

Segnalano ciò che richiede intervento, con gravità (Critica, Alta, Media, Info), assegnatario e stato: Nuova → In carico → Risolta, più Silenziata per le anomalie note. L'escalation si conta in orario lavorativo; i controlli si sospendono quando una fonte non risponde o la struttura è chiusa per stagione.

- Tracciamento e blocchi: campagne rifiutate o a spesa zero, budget esaurito, pagamenti, pixel/Stape senza eventi, conversioni a zero o duplicate.
- Anomalie (Fase 3): variazioni oltre soglia rispetto alla baseline, con volume minimo.
- Scadenze: contratti, rinnovi, ore del contratto, attività di piano scadute.

### Statistiche (MVP-1)

Non è una sezione a parte: i dati compaiono nelle overview e nelle sezioni.

- Livello 1: indicatori di portafoglio con stato per cliente.
- Livelli 2 e 3: funnel traffico → richieste → preventivi inviati → confermate → fatturato, costi per canale, confronti col periodo e con l'anno precedente. Periodo predefinito: 30 giorni.
- Preventivi inviati, confermate e fatturato solo per le strutture con Quoto (oggi 2 su 19); per le altre il funnel si ferma alle richieste.
- Richieste, dalla prima fonte disponibile: Quoto → CSV dei form WordPress/Elementor → valore più alto tra GA4 e la piattaforma del canale (solo sull'azione di richiesta). La fonte e il modello di attribuzione sono sempre indicati.

## Fonti dati

| Fonte | Utilizzo principale |
|---|---|
| Piani marketing | Attività previste e scadenze (estratte e approvate, poi task Fido) |
| ERP Fido | Anagrafica, progetti e task, ore, contratti, rinnovi |
| Quoto CRM | Funnel commerciale (2 strutture), campagne via UTM |
| Meta Ads | Stato campagne, errori, performance |
| Google Ads (MCC) | Stato campagne, quota impressioni, performance |
| GA4 / Stape | Traffico, conversioni, salute del tracking |
| Strumenti newsletter | Invii, aperture e clic (diversi per struttura: import o API) |
| Search Console / RankScale | Visibilità SEO e nelle risposte AI (GEO) |
| Simple Booking | Prenotazioni e disponibilità |
| Materiali del cliente | Documento di budget; CSV delle richieste dai form WordPress/Elementor (caricamento manuale o lettura automatica) |

**Modello dati**: idcliente → siti (idsito, la stessa chiave di Fido e Quoto) → account sulle piattaforme. Un account può servire più siti dello stesso cliente. Per ogni sito: tipo struttura, calendario di apertura, termini brand, fuso orario; per ogni account: l'azione o l'evento di richiesta.

## Architettura

- Raccolta dati schedulata una volta al giorno, prima delle 08:30, con storico delle raccolte.
- Database dedicato (MariaDB) con snapshot giornalieri, necessari per storico e anomalie. Dati di Quoto solo aggregati.
- Applicazione web PHP (Laravel consigliato) con frontend JavaScript; lettura di Fido e Quoto in sola lettura, con l'eccezione della creazione dei task di piano in Fido (Fase 2).
- Claude come supporto all'estrazione dei piani e, dalla Fase 3, all'analisi delle allerte.

## Roadmap

| Fase | Contenuto |
|---|---|
| MVP-0 | Anagrafica e collegamento account, raccolta giornaliera con storico, Centro allerte (blocchi, tracciamento, contratti) con diagnostica, notifiche, overview di portafoglio essenziale, Impostazioni |
| MVP-1 | Overview cliente, Google Ads e Meta Ads del cliente, Account e fonti, vista operatore Google/Meta, CSV delle richieste |
| 2 | Stato attività (piani → task Fido, scadenze), Newsletter, budget atteso |
| 3 | Anomalie statistiche, allerte quota impressioni, Analisi AI |
| 4 | Forecast, SEO/GEO (Search Console, RankScale), Simple Booking |

MVP-0 serve a misurare subito su dati reali quante allerte sono utili e quante false, e a correggere le soglie prima di costruire le pagine di dettaglio.

## Decisioni già prese

- Destinatari: team interno; accesso clienti in futuro (dati già separati per cliente).
- Piani marketing: soluzione mista, upload Word/PDF → estrazione Claude → approvazione dell'account → task in Fido.
- Notifiche: Microsoft Teams + email, solo su creazione ed escalation; escalation contata in orario lavorativo.
- Google Ads: API tramite il connettore MCC esistente.
- Budget atteso: documento di budget tra i materiali del cliente → (proposta) importo indicato nel piano → investimento dell'anno precedente → altrimenti solo allerta di mancanza.
- Quota impressioni: soglie per tipo di campagna (brand, Hotel Ads, ricerca generica, PMax).
- Newsletter: strumenti di invio diversi per struttura, indicati in anagrafica.
- Primo rilascio ridotto (MVP-0).

## Fuori perimetro

Matrice di correlazione, radar di performance, attribuzione multi-touch, revenue OTA, ROAS che somma valori convenzionali di più piattaforme.

## Rischi principali

- Piani marketing non strutturati → verifica scadenze difficile da automatizzare. Mitigazione: approvazione dell'account e task in Fido.
- Mapping account incompleto → dati non attribuibili al cliente.
- Limiti e quote delle API → raccolta schedulata e cache.
- Troppe allerte false (soglie mal tarate, stagionalità, dati non ancora consolidati, fonti che non rispondono) → perdita di fiducia nello strumento. Mitigazione: MVP-0, soglie legate allo storico, calendario di apertura, sospensione dei controlli.
- Conversioni configurate male nelle piattaforme (duplicate, valori convenzionali) → KPI e offerte automatiche falsati.
- Dati commerciali completi solo dove c'è Quoto (2 strutture su 19) → per le altre il funnel si ferma alle richieste.
