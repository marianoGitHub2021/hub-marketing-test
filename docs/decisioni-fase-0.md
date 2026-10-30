# Dashboard Hub Marketing — Decisioni Fase 0

Documento di riferimento delle scelte progettuali. Aggiornato al 30/10/2026 (v0.4; precedente 22/10/2026). Le modifiche sono elencate in registro-revisioni.md; le parti marcate **Proposta — da confermare** non sono ancora decise.

Stato Fase 0: completata. Tutte le decisioni sono prese e l'anagrafica dei clienti pilota è compilata (file anagrafica-strutture-pilota.xlsx). Restano punti aperti non bloccanti, elencati in fondo con la fase in cui servono. La v0.4 aggiunge all'anagrafica alcuni campi (§2, "Campi da aggiungere").

## 1. Destinatari

- Hub interno per il team; l'apertura ai clienti è uno sviluppo futuro.
- Navigazione a tre livelli (22/10/2026): overview di portafoglio → overview cliente → sezione del cliente. Dettaglio in mappa-applicazione.md.
- Ogni specialista personalizza la propria vista (sezioni/widget visibili e pagina di apertura). La vista per ruolo qui sotto è la vista operatore, secondaria rispetto ai tre livelli.

### Ruoli e vista operatore di default

| Ruolo | Vista operatore di default |
|---|---|
| Account manager (project manager) | Overview di portafoglio dei propri clienti · Stato attività (tutte le aree) · Riepilogo allerte · Scadenze contratti/rinnovi · Ore contratto |
| Advertising Google | Allerte Google Ads · Statistiche Google Ads · Attività di piano Google |
| Advertising Meta | Allerte Meta e tracking (pixel/Stape) · Statistiche Meta Ads · Attività di piano Meta |
| Mail marketing | Newsletter previste vs inviate · Statistiche newsletter · Allerte invii mancati |
| Editor | Attività contenuti (offerte, landing, SEO) · Search Console e RankScale · Avanzamento Fido |

### Predisposizioni per il futuro accesso clienti

- Dati separati per cliente (ogni dato legato alla struttura).
- Dati marcati "solo interni" (allerte, operatori, tempi, note, materiali "Solo NS") fin dall'inizio.
- Permessi a due livelli: ruolo (cosa) + assegnazione (su quali strutture).
- Autenticazione che supporti anche utenti esterni.
- Interfaccia a widget configurabili (riusabile per la vista cliente: il livello 2 è la base naturale).
- Registro accessi e modifiche, con utenti nominativi (vedi §6).
- Dati di Quoto solo aggregati (per struttura, campagna, periodo): nessun nome, email o dato personale negli snapshot dell'Hub.

## 2. Clienti pilota

19 strutture in 8 gruppi. Dettaglio degli identificativi in anagrafica-strutture-pilota.xlsx.

| Cliente | Strutture collegate | ADV Google | ADV Meta | Account | Mail marketing | Editor |
|---|---|---|---|---|---|---|
| Airport Hotel Bologna | Easy Parking Bologna | Mariano | Rossella | Nicola | Valentina | Letizia |
| Tramonto d'Oro | Villa Eos, GH Stella Marina, GH Il Corallo | Mariano | Rossella | Nicola + Veronica | Valentina | Massimo |
| Bucaneve (Livigno) | — | Mariano | Rossella | Veronica + Nicola | Valentina | Letizia, Massimo |
| Coast | Vista Mare Cesenatico | Mariano | Rossella | Nicola + Veronica | Valentina | Massimo, Letizia |
| Eden Roc (Positano) | Villa degli Dei, Adamo ed Eva, Yummy, Eden Sky Bar | Giorgia | Rossella | Nicola + Veronica | Valentina | Massimo |
| Parco dei Castagni | — | Mariano | Rossella | Nicola + Veronica | — | Letizia, Massimo |
| Ca' Bianca (Riccione) | Hotel Brig | Mariano (Ca' Bianca), Giorgia (Brig) | Rossella, Giada | Nicola + Veronica | Valentina | Letizia, Massimo |
| Primula | Hotel Galli's | Giorgia (sostituto: Mariano) | Rossella | Nicola + Veronica | Valentina | Letizia, Massimo |

- L'account è quasi sempre doppio (Nicola + Veronica).
- Mail marketing: referente unica Valentina.
- Escluso: Palazzo Tasso (non più cliente marketing).

**Più persone sullo stesso ruolo** (**Proposta — da confermare**): l'ordine nella tabella indica il titolare (primo nome), che riceve le notifiche; gli altri sono co-assegnatari e vedono le allerte in "Assegnate a me". Il sostituto subentra quando il titolare è segnato assente in Impostazioni.

**Modello dati** (aggiornato 20/10/2026): idcliente unico → siti (idsito) → account piattaforme. Un cliente con un solo sito ha un idcliente e un solo sito collegato. Ogni account può essere collegato a uno o più siti dello stesso cliente (es. un account Meta per tutto il gruppo). Le assegnazioni specialista × ruolo sono per sito. In Fido il cliente corrisponde all'anagrafica collegata ai siti (tabelle anagrafica e rel_anagra_siti): da verificare con il programmatore se usarne l'id come idcliente. È il primo punto da chiudere: è la chiave del livello 2.

**Account condivisi con assegnatari diversi** (**Proposta — da confermare**): ogni account ha una "struttura di riferimento"; le allerte dell'account vanno all'operatore del ruolo su quella struttura (es. Meta di Ca' Bianca + Brig → Rossella o Giada secondo la struttura di riferimento).

**Livello 2 = cliente** (22/10/2026): l'overview di secondo livello è per idcliente, con filtro per struttura; gli account condivisi si mostrano una volta sola, con l'elenco delle strutture servite.

### Campi da aggiungere all'anagrafica (30/10/2026)

| Campo | Livello | Perché |
|---|---|---|
| Tipo struttura (hotel, ristorante/bar, parcheggio, altro) | Struttura | Yummy, Eden Sky Bar ed Easy Parking non hanno richieste di soggiorno né forecast: si disattivano i controlli non pertinenti |
| Calendario di apertura (periodi di chiusura) | Struttura | Strutture stagionali (Livigno, Riviera): a stagione chiusa spesa, sessioni e richieste a zero sono normali |
| Camere totali per periodo | Struttura | Occupazione e gap del Forecast (Fase 4) |
| Termini brand | Struttura | Riconoscere campagne Brand, query brand di Search Console e RankScale |
| Fuso orario e valuta | Struttura e account | Definire "il giorno" in modo coerente tra le piattaforme |
| Strumento newsletter | Struttura | Le strutture usano strumenti di invio diversi |
| Fonte delle richieste | Struttura | Quoto · CSV · GA4/piattaforma |
| Azione/evento di richiesta | Account (Google Ads, Meta, GA4) | Richieste e controlli D04/D07 solo sull'azione di richiesta |
| Struttura di riferimento | Account condiviso | Assegnazione delle allerte |
| Titolare, co-assegnatari, sostituto | Struttura × ruolo | Assegnazione e notifiche |

### Evidenze dall'anagrafica

- L'idsito coincide tra Fido e Quoto: è la chiave unica.
- Usano Quoto solo Bucaneve e Parco dei Castagni; per le altre il funnel si legge da GA4 e si ferma alle richieste.
- Gli account Meta sono per gruppo (Tramonto d'Oro, Eden Roc, Ca' Bianca, Primula + Galli's, Airport + Easy Parking).
- Serve un modo per silenziare le anomalie note: es. l'account Meta di Vista Mare Cesenatico è in pagamento sospeso non risolvibile da noi.
- Utenti tecnici dei connettori: GA4 → google-analytics-mcp-2@network-service-ads-mcp.iam.gserviceaccount.com (Visualizzatore) per le nuove proprietà; Search Console → utente del connettore visibile in Impostazioni → Utenti delle proprietà già collegate.

### Impostazione (confermata 20/10/2026, precisata 22/10/2026)

- Tre livelli: overview di portafoglio (allerte principali, scelte tra quelle a priorità più alta) → overview cliente → sezione del cliente. La vista operatore per canale è secondaria.
- Pagina di apertura uguale per tutti (overview di portafoglio), personalizzabile.
- Confermate rispetto a Visual Impact: Fido = gestionale interno; Piano marketing = attività con scadenze; Sync giornaliero (manuale solo admin); fonti incluse Stape e Simple Booking.

## 3. Piani marketing

- Redatti annualmente da Nicola e/o Veronica su un modello Word aggiornato (non c'è un modello unico, ma sono simili).
- Sezioni fisse: Offerte sito · Google Ads e Metasearch · Social Ads · Mail Marketing · SEO e Local · Landing page · Check point.
- Date "entro il" con giorno preciso, spesso il 15 o fine mese.

### Soluzione mista, con task in Fido (aggiornata il 30/10/2026)

1. Upload piano (Word/PDF) da parte dell'account.
2. Estrazione con Claude nel modello strutturato.
3. Revisione e approvazione dell'account.
4. Creazione delle attività di piano come **task in Fido** (progetto del cliente, area, responsabile, scadenza, importo se presente).
5. L'Hub legge stato e scadenze dai task Fido e cerca le evidenze sulle piattaforme.

- Il formato libero resta sempre accettato; quello strutturato è un miglioramento opzionale.
- Un solo posto in cui il team aggiorna lo stato delle attività: Fido. Lo storico delle modifiche (chi, quando, prima/dopo) è quello di Fido.
- L'abbinamento tra attività e campagne resta nell'Hub, collegato all'id del task Fido.
- Conseguenza tecnica: l'Hub scrive su Fido, limitatamente alla creazione dei task dal piano approvato. È un'eccezione alla lettura in sola lettura (§7) da concordare col programmatore prima della Fase 2.

### Tipi di scadenza

| Valore nel piano | Trattamento |
|---|---|
| Data "entro il" | Scadenza verificata |
| Data spostata ("Anticipiamo al…") | Data originale + data aggiornata, con storico |
| Attiva (es. "Attiva (12%)", "Attiva (10€/gg)") | Attività continuativa: si verifica che resti attiva; l'importo diventa budget atteso per le allerte (gradino 2 della cascata, proposta) |
| Su necessità / su richiesta | Facoltativa: non genera ritardi |
| Da valutare | Promemoria per l'account, nessuna scadenza |
| Ricorrente (settimanale/mensile) | Nessuna verifica puntuale; eventuale controllo periodico su ore Fido |

- Landing page (senza data nel piano): scadenza derivata dalla campagna collegata, qualche giorno prima.
- SEO ("realizzate dal"): data di inizio.

### Verifica per area

| Area | Ruolo | Riscontro |
|---|---|---|
| Offerte sito | Editor | Pagina online/aggiornata o task Fido chiuso |
| Google Ads | ADV Google | Campagna attiva su Google Ads |
| Metasearch (Trivago, Tripadvisor, Hotel Ads) | ADV Google | Hotel Ads da Google Ads; Trivago/Tripadvisor solo conferma manuale (non automatizzabile) o UTM |
| Social Ads | ADV Meta | Campagna attiva su Meta |
| Newsletter | Mail marketing | Invio registrato (vedi §4, Newsletter) |
| SEO e Local | Editor | Task Fido chiuso o conferma manuale |
| Check point | Account | Calendario/task Fido o conferma manuale |

## 4. Fonti dati e abbinamento campagne

- Google Ads: connettore MCC esistente (codice proprietario).
- Funnel campagne: Quoto in prima battuta; GA4 in alternativa per le strutture senza Quoto. L'anagrafica indica per ogni struttura se usa Quoto.
- Funnel dell'Hub: richieste → preventivi inviati → confermate → fatturato. Le ultime tre fasi solo con Quoto; per le altre strutture il funnel si ferma alle richieste.
- UTM obbligatori su tutte le attività di marketing → allerta automatica su traffico da campagne senza UTM o fuori convenzione. Google Ads con tag automatico (gclid) è conforme.

### Newsletter (decisa il 30/10/2026)

Le strutture usano strumenti di invio diversi.

- In anagrafica si indica lo strumento newsletter di ogni struttura.
- Fase 2: l'invio si registra nell'Hub (a mano o con import CSV dallo strumento); i clic sul sito si leggono da GA4 tramite UTM.
- Connettore API solo per gli strumenti più usati, dopo il censimento.
- PRF-08 (calo di aperture o clic) solo dove lo strumento fornisce aperture e clic; PLN-04 si verifica sull'invio registrato.

### Codice campagna (facoltativo)

Generato dall'hub (es. COR27-META-01), da inserire nel nome campagna / utm_campaign. Non è condizione necessaria.

### Verifica a cascata

1. Codice campagna → abbinamento certo.
2. URL di atterraggio coincidente con quello del piano → alta affidabilità.
3. Parole chiave del titolo + data di attivazione vicina alla scadenza → media affidabilità.
4. Nuova campagna sull'account giusto nel periodo → abbinamento proposto.
5. Nessun riscontro → conferma/collegamento manuale.

Gli abbinamenti medi/bassi richiedono una conferma una tantum, poi vengono memorizzati.

## 5. Notifiche

- Canali: Microsoft Teams + email.
- Destinatario a cascata: operatore assegnato (se identificabile) → casella di settore (es. advertising@) → casella generale (webmarketing@).
- Weekend e festivi: notifiche sospese, accumulate nel riepilogo del primo giorno lavorativo; le critiche partono comunque verso una casella presidiata nel weekend (da definire).
- Escalation (30/10/2026): contata solo in orario lavorativo (08:30–17:30 dei giorni lavorativi). Un'allerta non diventa Critica nel weekend solo perché nessuno l'ha presa in carico. Regola completa nel catalogo allerte §1.

| Gravità | Esempi | Canale |
|---|---|---|
| Critica | Campagna rifiutata/ferma, account bloccato, pagamento rifiutato, tracking a zero | Dashboard + notifica |
| Alta | Anomalia forte, scadenza di piano superata | Dashboard + riepilogo giornaliero |
| Media/Info | Scadenza imminente, UTM mancanti, "da valutare" | Dashboard (+ eventuale riepilogo settimanale all'account) |

## 6. Ruoli e permessi

- Ogni specialista vede di default le strutture assegnate, con switch su tutte le strutture (nessuna riassegnazione necessaria).
- Admin: **Proposta — da confermare**: ruolo admin assegnato a utenti nominativi (login Google aziendale), così il registro accessi e modifiche sa chi ha fatto cosa. webmarketing@ resta solo come casella di notifica. Oggi: utente generico webmarketing@, usato anche dalla direzione.

| Azione | Account | Specialisti | Admin |
|---|---|---|---|
| Caricare/approvare piani | ✅ | ❌ | ✅ |
| Modificare attività di piano (scadenze, stato) | ✅ tutte le aree (in Fido) | ✅ propria area (in Fido) | ✅ |
| Confermare attività / abbinare campagne | ✅ | ✅ propria area | ✅ |
| Prendere in carico / riassegnare allerte | ✅ | ✅ tutte (*) | ✅ |
| Risolvere allerte | ✅ | ✅ propria area (*) | ✅ |
| Silenziare allerte | ✅ | ✅ propria area | ✅ |
| Personalizzare la propria vista | ✅ | ✅ | ✅ |
| Configurare soglie allerte | ✅ propri clienti | ❌ | ✅ |
| Anagrafica, assegnazioni, utenti | ❌ | ❌ | ✅ |

(*) **Proposta — da confermare**: la v0.3 del catalogo diceva "chiunque del team può prendere in carico o riassegnare", la tabella "propria area". Proposta: prendere in carico e riassegnare per tutti, risolvere e silenziare per la propria area.

Ogni modifica alle attività di piano è registrata nello storico (chi, quando, prima/dopo).

## 7. Stack tecnico

- Nuova applicazione separata con database dedicato, sullo stesso stack di Fido/Quoto: PHP + MariaDB (Fido su MariaDB 11.4).
- Impostazione moderna consigliata: PHP 8.3+ con framework diffuso (es. Laravel), frontend JavaScript per la dashboard, lettura da Fido/Quoto in sola lettura. Eccezione (Fase 2): creazione dei task di piano in Fido.
- Sviluppo assistito da Claude: dall'utente (prototipi, regole, configurazioni) e/o dal programmatore di Fido e Quoto (architettura, integrazioni, sicurezza, rilasci).
- Regole di progetto: repository Git, ambiente di prova separato, file di istruzioni per Claude nel repository, revisione del programmatore prima dei rilasci.
- Accesso utenti: proposto login con account Google aziendale.
- Il backend Express e i server MCP di Visual Impact non si riusano; si riprendono solo regole e formule.

### Integrazioni

| Fonte | Connettore attuale | Uso nell'hub |
|---|---|---|
| Google Ads (MCC) | Codice proprietario | Riuso della stessa logica/credenziali nel backend |
| GA4 | Codice proprietario | Come sopra; rapporto in tempo reale per confermare D01 |
| Fido / Quoto | Codice proprietario (sola lettura) | Lettura diretta dai DB o tramite lo stesso codice; Fido: progetti, task, ore, contratti; scrittura dei task di piano dalla Fase 2 |
| Meta Ads | MCP fornito da Meta | API Marketing di Meta diretta, con token "utente di sistema" del Business Manager |
| Strumenti newsletter | — | Import CSV; API solo per gli strumenti più usati (Fase 2) |

## 8. Frequenza di aggiornamento

- Tutte le fonti: una volta al giorno, anche nel weekend e anche per le allerte critiche.
- Orario lavorativo: 08:30–17:30.
- Esecuzione notturna/mattutina prima delle 8:30, così il riepilogo giornaliero usa dati aggiornati.
- Ritardi intrinseci da considerare: GA4 24–48h, Search Console 2–3 giorni → i controlli GA4 (tranne D01) e le anomalie si valutano sui giorni consolidati.
- Ogni raccolta registra esito, durata, record importati ed errore; se un connettore fallisce, i controlli che ne dipendono sono sospesi.

### File di configurazione

Valori modificabili senza intervenire sul codice:

- Caselle di notifica (settore, generale, presidiata weekend)
- Canali Teams per area
- Orario riepilogo giornaliero: 08:30
- Orario lavorativo: 08:30–17:30 (usato anche per l'escalation)
- Orario esecuzione raccolta dati giornaliera
- Calendario festivi e chiusure aziendali

## Punti aperti (non bloccanti)

| Punto | Da chi | Serve entro |
|---|---|---|
| Uso dell'id anagrafica di Fido come idcliente (base del livello 2) | Programmatore | Inizio MVP-0 (primo punto) |
| Versione PHP e framework di Fido e Quoto | Programmatore | Inizio MVP-0 |
| Credenziali/token per uso backend (Google Ads, GA4, Search Console, Meta "utente di sistema") | Programmatore | Inizio MVP-0 |
| Lettura di progetti, task, ore e contratti da Fido | Programmatore | MVP-0 (CTR) |
| Scrittura dei task di piano su Fido dall'Hub | Programmatore | Prima della Fase 2 |
| Campi aggiuntivi dell'anagrafica (§2) per le strutture pilota | Account + Admin | MVP-0 |
| Caselle email di notifica per settore (mail marketing, editor) | Team | MVP-0 (notifiche) |
| Casella presidiata nel weekend per le critiche | Team | MVP-0 (notifiche) |
| Canali Teams per area | Team | MVP-0 (notifiche) |
| Conferma strutture con Stape attivo (da Fido: Airport, Parco dei Castagni, Primula) | ADV | MVP-0 (allerte tracking) |
| Censimento degli strumenti newsletter per struttura | Mail marketing | Fase 2 |
| Pulizia delle azioni di conversione Google Ads sugli account pilota (doppioni e micro-conversioni primarie con valore, emersi nella prova a secco) | ADV Google | Prima del rilascio MVP-0 |
| Verifica tracciamento GA4 di Villa Eos, Stella Marina e Il Corallo (traffico quasi assente anche in alta stagione) | ADV Google | Prima del rilascio MVP-0 |
| Accessi Search Console mancanti (Villa Eos, Stella Marina, Il Corallo, Vista Mare Cesenatico, Villa degli Dei, Eden Sky Bar, Yummy) e verifica brighotel.com | Editor | Fase 4 (SEO) |
| Dismissione account Meta "Hotel Ca Bianca 2" | ADV Meta | — |
| Stato del sito Easy Parking in Fido (oggi cessato) | Admin | — |

### Proposte da confermare (v0.4)

Dettaglio in registro-revisioni.md: budget atteso dal piano, permessi sulle allerte, admin nominativi, titolare e co-assegnatari, struttura di riferimento degli account condivisi, totale delle richieste tra canali, BUD-01 in MVP-1, BLK-08, SEO-04.
