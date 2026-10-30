# Dashboard Hub Marketing — Mappa dell'applicazione e roadmap

Aggiornato al 30/10/2026 (v0.4; precedente 22/10/2026). Mappa visiva sul canvas di design "Hub Marketing – Vista portafoglio". Le modifiche sono elencate in registro-revisioni.md; le parti marcate **Proposta — da confermare** non sono ancora decise.

## Fasi di rilascio (riviste il 30/10/2026)

- **MVP-0**: primo rilascio ridotto, per misurare subito su dati reali quante allerte servono e quante sono false. Comprende: anagrafica e collegamento degli account, raccolta giornaliera con storico, Centro allerte con le allerte di blocco, tracciamento e contratti (BLK, TRK, CTR), notifiche, overview di portafoglio essenziale, Impostazioni.
- **MVP-1**: overview cliente, sezioni Google Ads e Meta Ads del cliente, Account e fonti, vista operatore Google/Meta, CSV delle richieste.
- **Fase 2**: Stato attività (piani → task Fido, verifica scadenze), Newsletter, budget atteso.
- **Fase 3**: anomalie statistiche, allerte quota impressioni (BUD-02; BUD-01 proposta in MVP-1), Analisi AI attiva.
- **Fase 4**: Forecast (dati forniti dai clienti), SEO/GEO, Simple Booking.

## Struttura a tre livelli (decisa il 22/10/2026)

La navigazione è organizzata per cliente, non per canale.

| Livello | Cosa mostra | Per chi soprattutto |
|---|---|---|
| 1 · Overview di portafoglio | Tutte le strutture nel perimetro: priorità di oggi, diagnostica tracciamento, indicatori, clienti da guardare, attività di piano, allerte di tutti i progetti | Account / project manager |
| 2 · Overview cliente | Un cliente (idcliente) con tutte le sue strutture: indicatori del gruppo, allerte del cliente, stato dei canali, strutture del gruppo, attività di piano, account e fonti collegati | Tutti i ruoli |
| 3 · Sezione del cliente | Il dettaglio di un'area per quel cliente: Google Ads, Meta Ads, Newsletter, Allerte, Stato attività, SEO, Forecast | Specialista dell'area |

- Il livello 2 è il cliente, non il singolo sito. Esempio: Eden Roc è una pagina sola con 5 strutture. All'interno, un filtro per struttura ("Tutto il gruppo" o una delle strutture) restringe indicatori e tabelle.
- Gli account condivisi (es. Meta di Eden Roc o di Primula + Galli's) compaiono una volta sola a livello cliente, con l'elenco delle strutture servite. Non vengono ripartiti tra le strutture.
- Un cliente con un solo sito ha la stessa pagina, senza il filtro per struttura.
- Le strutture chiuse per stagione mostrano l'indicazione "Chiusa fino al gg/mm"; i controlli sospesi compaiono come tali, non come OK.
- Percorso sempre visibile in alto: Portafoglio › Cliente › Sezione. Il nome della struttura o del cliente porta al livello 2 da qualunque pagina.

### Barra laterale a due contesti

- Contesto portafoglio (nessun cliente scelto): Overview · Clienti · Allerte · Stato attività (F2) · Vista operatore (Google Ads, Meta Ads, Newsletter F2) · Analisi AI (F3) · Impostazioni (admin).
- Contesto cliente (dopo la scelta di un cliente): ← Portafoglio · Overview cliente · Google Ads · Meta Ads · Newsletter (F2) · Allerte · Stato attività (F2) · SEO e GEO (F4) · Forecast (F4) · Account e fonti.

In MVP-0 il contesto cliente mostra solo Allerte; le altre voci compaiono con il loro rilascio.

### Vista operatore (secondaria)

- Elenchi per canale con tutte le strutture del perimetro, per esempio Google Ads di tutti i clienti assegnati a Mariano.
- Serve allo specialista per il lavoro quotidiano trasversale. Non è il punto di ingresso principale.
- Ogni riga porta al livello 3 del cliente per quel canale.

### Pagina di apertura

- Per tutti: Overview di portafoglio (livello 1), filtrata su "Le mie strutture".
- Ogni utente può impostare come apertura la propria vista operatore (es. ADV Google → Vista operatore › Google Ads).

## Mappa delle pagine

| Livello | Pagina | Contenuto | Fase |
|---|---|---|---|
| 1 | Overview di portafoglio | Priorità, diagnostica, allerte principali, stato raccolte | MVP-0 (essenziale) |
| 1 | Overview di portafoglio — completa | Indicatori, clienti da guardare, piano, Cross-channel AI | MVP-1 (piano F2, AI F3) |
| 1 | Clienti | Elenco clienti e strutture con stato | MVP-0 |
| 1 | Allerte | Centro allerte su tutte le strutture: schede Allerte e Diagnostica tracciamento | MVP-0 |
| 1 | Stato attività | Scadenze di piano di tutti i clienti (dai task Fido) | Fase 2 |
| 1 | Vista operatore › Google Ads, Meta Ads | Portafoglio per canale | MVP-1 |
| 1 | Vista operatore › Newsletter | Previste vs inviate | Fase 2 |
| 2 | Overview cliente | Indicatori del gruppo, allerte, stato canali, strutture, piano, account e fonti | MVP-1 |
| 3 | Google Ads | Campagne del cliente, quota impressioni, budget — specifica sotto | MVP-1 (fase per blocco sotto) |
| 3 | Meta Ads | Account, campagne attive, pixel e Stape | MVP-1 |
| 3 | Newsletter | Invii previsti vs fatti, risultati | Fase 2 |
| 3 | Allerte | Centro allerte filtrato sul cliente (stessa pagina del livello 1) | MVP-0 |
| 3 | Stato attività | Piano del cliente: import, approvazione, task Fido, scadenze | Fase 2 |
| 3 | SEO e GEO | Search Console, RankScale | Fase 4 |
| 3 | Forecast | Import dati cliente (CSV, Excel, PDF) | Fase 4 |
| 3 | Account e fonti | ID collegati e configurazione per account, referenti per ruolo, stato connettori del cliente, fonte delle richieste, materiali del cliente | MVP-1 (in MVP-0 la configurazione sta in Impostazioni) |
| — | Analisi AI | "Spiega questa allerta", domande cross-canale | Predisposta MVP, attiva F3 |
| — | Impostazioni (admin) | Connettori con storico raccolte e Sync, configurazione clienti, strutture e account, utenti e assegnazioni, calendari di apertura | MVP-0 |

Fonti dati (GA4, Quoto, Fido, Search Console, RankScale) non sono voci di menu: compaiono dentro le pagine.

## Configurazione (Impostazioni e Account e fonti)

### Per struttura

- idsito, idcliente, tipo struttura (hotel, ristorante/bar, parcheggio, altro), fuso orario, valuta.
- Calendario di apertura (periodi di chiusura stagionale).
- Camere totali per periodo (serve dal Forecast, Fase 4).
- Termini brand (nome della struttura e varianti): usati per riconoscere le campagne Brand, le query brand di Search Console e RankScale.
- Fonte delle richieste (Quoto · CSV · GA4/piattaforma) e strumento newsletter.
- Stape attivo sì/no.
- Assegnazioni per ruolo: titolare, co-assegnatari, sostituto.

### Per account

Un account può servire più strutture dello stesso cliente.

- Google Ads: ID cliente, azione di conversione "richiesta" (usata per le richieste e per D04), fuso e valuta dell'account.
- Meta: ID account pubblicitario, dataset/pixel, evento di richiesta (Lead, Purchase o personalizzato), finestra di attribuzione.
- GA4: proprietà, evento chiave di richiesta.
- Search Console: proprietà; RankScale: brand.
- Strutture servite e struttura di riferimento (per l'assegnazione delle allerte).

### Connettori

- Stato con esito dell'ultima raccolta (es. "oggi 06:12 · OK"), non "minuti fa": la raccolta è giornaliera.
- Storico raccolte: data e ora, esito, durata, record importati, errore sintetico, tentativi ripetuti.
- Sync globale e Sync del singolo connettore, solo admin.
- Credenziali e token solo lato server, mai nel frontend.

## Livello 3 · Google Ads (specifica del 22/10/2026, aggiornata il 30/10/2026)

Caso pilota: Primula (hotelprimula.it, account 632-763-6798) + Hotel Galli's (hotelgallis.com, account 442-176-4597). Soglie e allerte in catalogo-allerte.md v0.5.

### Domande a cui risponde la pagina

In ordine di urgenza. Ogni blocco della pagina esiste per rispondere ad almeno una di queste domande.

| # | Domanda | Domande di dettaglio | Dove si risponde |
|---|---|---|---|
| 1 | Le campagne stanno girando? | C'è qualcosa di fermo, rifiutato o con spesa a zero? · Fatturazione o account sospeso? · Quali campagne finiscono nei prossimi giorni? | Allerte · colonna stato della tabella |
| 2 | I dati sono affidabili? | Le conversioni arrivano o sono a zero? · Ce ne sono di contate due volte, o primarie che non sono richieste? · Da quale fonte vengono le richieste e quanto è aggiornata? | Allerte di tracciamento · indicazione della fonte delle richieste |
| 3 | Stiamo spendendo quanto previsto? | Spesa del periodo in linea con il budget atteso? · Dove arriviamo a fine mese con questo ritmo? · Il budget atteso da dove viene, o manca? | Card spesa · blocco Budget |
| 4 | Quanto mercato lasciamo sul tavolo e perché? | Quali campagne perdono per budget e quanto vale in €? · Quali perdono per ranking? · Il brand è coperto o qualcuno ci passa davanti sul nome? | Blocco Quota impressioni · colonne quota della tabella |
| 5 | Le campagne portano richieste a un costo sostenibile? | Quante richieste e a che costo, per struttura e per campagna? · Come va rispetto al periodo precedente e all'anno scorso? · Quali campagne spendono senza portare richieste? | Card · colonne richieste e costo per richiesta |
| 6 | Stiamo facendo quello che dice il piano? (Fase 2) | Le campagne previste esistono e sono attive alle date giuste? · Ci sono campagne attive fuori piano? | Colonna abbinamento al piano |

Esito atteso: la pagina porta a una di quattro azioni: correggere un blocco, sistemare il tracciamento, proporre più budget all'account, lavorare sulla qualità delle campagne.

### Impostazione

- Percorso: Portafoglio › Cliente › Google Ads. Filtro struttura: "Tutto il gruppo" o una struttura.
- Periodo predefinito: 30 giorni.
- Gli account Google Ads di solito sono uno per struttura (Primula e Galli's hanno due account separati). Con "Tutto il gruppo" la pagina raggruppa per account/struttura.

### Blocchi della pagina e fase

| Blocco | Contenuto | Fase |
|---|---|---|
| Indicatori (card) | Spesa · click · richieste · costo per richiesta · quota Brand (pesata sulle impressioni potenziali delle sole campagne Brand, escluse quelle "< 10%") · € persi per budget (somma delle campagne calcolabili, etichettata come stima) | MVP-1 |
| Allerte Google Ads del cliente | BLK, TRK-04, TRK-13, TRK-15 | MVP-1 (le allerte esistono già in MVP-0 nel Centro allerte) |
| Allerte quota impressioni | BUD-01, BUD-02 | BUD-01 Fase 3 (proposta MVP-1); BUD-02 Fase 3 |
| Tabella campagne | Raggruppata per struttura: nome · tipo (brand, generica, PMax, Hotel Ads, display, Demand Gen, video) · stato e motivi · strategia di offerta · budget/giorno · spesa · andamento spesa (spesa media giornaliera ÷ budget) · click · richieste · costo per richiesta · quota ottenuta · persa budget · persa ranking. Le campagne in pausa o terminate stanno in un gruppo chiuso | MVP-1 |
| Colonna abbinamento al piano | Task Fido collegato | Fase 2 |
| Quota impressioni | Barre per campagna (ottenuta / persa budget / persa ranking), andamento a 90 giorni, mercato non acquisito € con valori di partenza e formula visibile | MVP-1 |
| Budget | Budget giornaliero totale per struttura, spesa del mese a oggi, proiezione a fine mese | MVP-1 |
| Budget atteso | Valore e sua fonte; BUD-03, BUD-04 | Fase 2 |

La media della quota su tutte le campagne è stata scartata perché le PMax la falsano. Mockup: tavola "Livello 3 · Google Ads del cliente (Primula)" sul canvas.

### Regole di visualizzazione

- Richieste: fonte a cascata Quoto → CSV WordPress/Elementor → valore più alto tra GA4 e Google Ads, letto solo sull'azione di richiesta configurata (mai il totale "Conversioni" di Google Ads). La fonte usata è sempre indicata, con il modello di attribuzione.
- Valore conversioni: dove i valori sono fissi (es. 100 € per richiesta) si etichetta "valore convenzionale", non fatturato. ROAS sul fatturato solo con Quoto.
- Quota sotto il 10%: si mostra "< 10%" (Google restituisce 0,0999).
- PMax: la quota impressioni riguarda solo la parte su ricerca; nota accanto al dato, nessun colore.
- Colori solo per brand e Hotel Ads (soglie nel catalogo).
- Mercato non acquisito €: non calcolato per PMax, Hotel Ads, Display, Demand Gen e Video.
- Budget atteso: documento di budget tra i materiali del cliente → importo del piano (proposta) → investimento dell'anno precedente → altrimenti allerta BUD-04.

### Evidenze dal caso Primula (22/09–21/10/2026)

- Conversioni doppie su Primula: "Generate Lead" (tag) e "generate_lead" (GA4) sono entrambe primarie; sono primarie con valore anche newsletter e visite. La PMax risulta avere 1.301 conversioni e 127.000 € di valore su 1.063 € di spesa. Serve TRK-13.
- Brand di Primula con 4 €/giorno: quota 51%, persa per budget 21% → con le nuove soglie BUD-01 Alta.
- Galli's: PMax, Autunno e PMax ristorante perdono il 67–76% per ranking. Con le soglie per tipo non generano allerte fisse (anomalia solo in Fase 3).
- Piano 2026 di Primula: PMax e campagna località autunno/inverno previste entro il 15/09/2026. Nell'account c'è solo una PMax sempre attiva, senza stagione nel nome; "livigno" e "autunno" sono terminate. Senza codice campagna l'abbinamento resta da confermare (PLN-03). Il piano non indica importi: il budget atteso parte dall'anno precedente. Trivago (attivo nel piano) non passa da Google Ads.
- Nota tecnica: con il connettore attuale campaign.start_date ed end_date non sono riconosciuti; lo stato "terminata" si ricava da primary_status_reasons (CAMPAIGN_ENDED). Verificato il 30/10/2026: le date si leggono con `campaign.start_date_time` ed `campaign.end_date_time`.

## Livello 3 · Meta Ads (note per la specifica, 30/10/2026)

- Stato letto su campagna, gruppo di inserzioni e inserzione; budget a livello di campagna o di gruppo secondo l'impostazione dell'account.
- Richieste: solo sull'evento di richiesta configurato per l'account, mai sul totale "risultati"; le campagne di notorietà non hanno colonna richieste.
- Finestra di attribuzione dell'account sempre indicata, uguale per tutte le metriche della pagina.
- Tipo di campagna (prospezione o retargeting) per le soglie di frequenza.

## Elementi trasversali

- Ricerca globale "Vai a un cliente o a una struttura" in ogni pagina. Porta al livello 2 del cliente; se si cerca una struttura, apre il livello 2 con il filtro già impostato su di essa. Massimo 6 suggerimenti.
- Filtro nella pagina per cliente (livello 1) o per struttura (livelli 2 e 3). Restringe solo la pagina corrente.
- Interruttore "Le mie strutture / Tutte", selettore periodo (predefinito 30 giorni), campanella allerte.
- Barra laterale con indicatori (numero allerte, pallino di stato per canale).
- Tema scuro di default, tema chiaro predisposto (foglio di stile condiviso ns-hub.css, con i colori del restyling del sito NS: blu notte scuro, accento magenta/viola).
- Note di specifica su ogni mockup: Owner, Fonte, Regola, Output (+ "Nascondi note").
- Dati aggiornati una volta al giorno; Sync manuale solo admin (globale o per connettore).

## Elementi integrati dalla landing "MVP Spec annotata"

- Card KPI con valore, variazione e confronto testuale.
- Overview di portafoglio: diagnostica tracciamento per struttura come riepilogo del Centro allerte.
- Escalation delle allerte: regola unica nel catalogo allerte (§1), contata in orario lavorativo.
- KPI Revenue/ROAS solo per strutture con Quoto; per le altre richieste dalla fonte a cascata.
- Grafico andamento per canale (Google, Meta, Newsletter, Organico) con 7/30/90 giorni ed export, nell'overview cliente.
- Google Ads: quota impressioni ottenuta, persa per budget, persa per ranking; soglie per tipo di campagna, configurabili per struttura.
- Meta: controllo pixel/eventi (es. evento di richiesta assente da 48 h).
- Testi allerte nel formato: canale · problema · conseguenza, con percorso nella piattaforma e dato da controllare dopo l'intervento.
- Overview cliente: mix di canale con obiettivo (solo Quoto, obiettivo in Configurazione).
- Impostazioni: stato connettori con storico raccolte; configurazione obiettivi, soglie, anomalie silenziate.
- Materiali del cliente come documenti con versione, autore, data e indicazione "Solo NS" o condiviso (dato "solo interno" per il futuro accesso clienti).

## Fuori perimetro (deciso il 30/10/2026)

Elementi di Visual Impact che l'Hub non riprende, perché senza fonte o formula: matrice di correlazione, radar di performance, attribuzione multi-touch, "confidenza del modello", revenue OTA, ROAS totale che somma valori convenzionali di più piattaforme, organico brand ricavato da GA4.

## Decisioni

30/10/2026

- Escalation unica nel catalogo allerte, contata in orario lavorativo; le allerte già Critiche notificano subito.
- Primo rilascio MVP-0 ridotto, poi MVP-1.
- Stato attività: le attività approvate del piano diventano task in Fido; l'Hub legge stato e scadenze da Fido.
- Newsletter: strumenti di invio diversi per struttura; strumento indicato in anagrafica.

22/10/2026 (livello 3 Google Ads)

- Richieste: Quoto → CSV dei form WordPress/Elementor (caricamento manuale o lettura automatica) → valore più alto tra GA4 e la piattaforma del canale (Google Ads o Meta Ads), solo sull'azione di richiesta.
- Periodo predefinito: 30 giorni.
- Budget atteso: documento tra i materiali del cliente → investimento dell'anno precedente → solo allerta di mancanza di storico e piano (BUD-04). (v0.4: proposto il gradino "importo del piano".)
- Soglie quota impressioni per tipo di campagna (brand, Hotel Ads, ricerca generica, PMax, display) al posto delle soglie uniche di Visual Impact; allerte valutate sugli ultimi 7 giorni completi con almeno 500 impressioni.
- Mercato non acquisito € calcolato dai soli dati Google Ads (impressioni ÷ quota ottenuta × quota persa × CTR × CPC), non calcolato sotto il 10%.
- Nuovo controllo conversioni primarie duplicate (D15 / TRK-13).

22/10/2026

- Navigazione a tre livelli: portafoglio → cliente → sezione. La vista operatore per canale è secondaria.
- Livello 2 = cliente (idcliente) con filtro per struttura; account condivisi mostrati una volta.
- Pagina di apertura uguale per tutti (overview di portafoglio), personalizzabile.
- La "Scheda struttura" viene sostituita dall'overview cliente.

20/10/2026

- Analisi AI: da prevedere fin dall'MVP (voce di menu, modello dati e punti di aggancio), funzioni attive in Fase 3.
- Forecast: dati forniti manualmente dai clienti (CSV, PDF, Excel) tramite import; ora è una sezione di livello 3.
- Sync: pulsante disponibile solo all'admin, forza la raccolta dei dati del giorno precedente.
- Revenue OTA: esclusa per ora (nessuna fonte); eventuale aggiunta futura.
- Statistiche come voce autonoma eliminata: assorbita dalle overview e dalle sezioni.
- Home di portafoglio prima della selezione di un cliente, con le allerte a priorità più alta.
