# Dashboard Hub Marketing — Catalogo allerte e diagnostica

Bozza v0.5 del 30/10/2026 (v0.4 del 30/10/2026, v0.3 del 22/10/2026, v0.2 del 21/10/2026, v0.1 del 20/10/2026) per lo sviluppatore. Basata sulle decisioni della Fase 0, sul materiale di Visual Impact e sulla prova a secco del 30/10/2026 su dati reali. Le soglie indicate sono valori di partenza: stanno nel file di configurazione e sono modificabili per struttura.

Novità v0.5, dalla prova a secco su 8 strutture pilota (dettaglio in registro-revisioni.md, sezione v0.5):

- BLK-04 solo con spesa di almeno 20 € nei 7 giorni precedenti;
- TRK-04: contano solo i clic di Search, PMax e Hotel; tasso di conversione solo sull'azione di richiesta;
- TRK-01 sospeso sotto 5 sessioni al giorno di media;
- TRK-03: test del picco solo con media ≥ 3, test separato sul rapporto eventi per sessione;
- BLK-05: Alta solo sulle campagne Brand, una sola allerta per account per le altre;
- BLK-03 letto sui singoli annunci attivi e, per PMax, sui gruppi di asset;
- D02 solo con evento di richiesta configurato e almeno 1 evento al giorno di media;
- D09 con volume minimo di 100 sessioni e google/cpc "(not set)" conforme;
- BLK-06 Google in MVP-0: i campi data delle campagne sono leggibili.

Novità v0.4 (dettaglio in registro-revisioni.md):

- escalation unica, contata in orario lavorativo (deciso il 30/10/2026);
- sospensione dei controlli: connettore fallito, struttura chiusa per stagione, rodaggio, tipo struttura;
- fasi riallineate al rilascio MVP-0 / MVP-1 (deciso il 30/10/2026);
- D05 separato da TRK-04 (nuova TRK-15); soglie di D04, D06, D07 legate al tasso di conversione;
- D02/D03 valutati sul giorno consolidato; D01 confermato con il tempo reale di GA4;
- D09: Google Ads con tag automatico (gclid) è conforme;
- controlli Meta su gruppi di inserzioni e inserzioni, non solo sulla campagna;
- quota impressioni: Demand Gen e Video "non applicabile"; Hotel Ads escluso dal mercato non acquisito €;
- PRF-05 per tipo di campagna Meta; SEO-02 su Search Console; nuova SEO-04 per RankScale;
- nuova CTR-03 (ore del contratto); PLN letti dai task Fido; newsletter multi-strumento.

Le parti marcate **Proposta — da confermare** non sono ancora decise.

## 1. Regole generali

### Gravità

| Livello | Significato | Notifica |
|---|---|---|
| Critica | Qualcosa è fermo o i dati non sono affidabili: si perdono soldi o conversioni adesso | Dashboard + notifica (anche nel weekend, alla casella presidiata) |
| Alta | Problema serio da gestire entro la giornata | Dashboard + riepilogo giornaliero 08:30 |
| Media | Da controllare nei prossimi giorni | Dashboard |
| Info | Promemoria o segnalazione senza urgenza | Dashboard |

### Escalation nel tempo (decisa il 30/10/2026)

Unica regola valida per tutto l'Hub; la mappa dell'applicazione rimanda qui.

- Le ore si contano **solo in orario lavorativo**: 08:30–17:30 dei giorni lavorativi, con il calendario festivi e chiusure del file di configurazione.
- Un'allerta non presa in carico da 24 ore lavorative sale di un livello (Media → Alta).
- Da 48 ore lavorative diventa Alta se non lo era già; le allerte di tracciamento (TRK) diventano Critiche.
- Le allerte Info non salgono di livello: sono promemoria.
- Le allerte che nascono già Critiche notificano subito, anche nel weekend, alla casella presidiata.
- L'escalation si ferma quando l'allerta è "In carico" o "Silenziata".
- Nessuna allerta ha un'escalation propria (in v0.3 TRK-02 ne aveva una: eliminata).

Esempio: un'allerta TRK Alta aperta venerdì alle 18:00 diventa Critica martedì verso metà giornata, se nessuno la prende in carico.

### Sospensione dei controlli

Un controllo non si valuta, e quindi non apre allerte, quando:

1. **Connettore fallito**: la raccolta del connettore da cui dipende è fallita (TRK-12). Si apre solo TRK-12; i controlli dipendenti mostrano "dato non disponibile".
2. **Struttura chiusa per stagione**: il giorno ricade in un periodo di chiusura del calendario di apertura della struttura (anagrafica). Sospesi: BLK-04, TRK-01, TRK-02, TRK-04, TRK-05, TRK-06, TRK-08, TRK-10, PRF-01, PRF-02, PRF-07, FCS. Restano attivi BLK-01, BLK-02, BLK-03, CTR. Nei 14 giorni dopo la riapertura la baseline delle anomalie usa lo stesso periodo dell'anno precedente.
3. **Rodaggio**: i controlli che usano una media storica (D03, PRF) partono dopo 28 giorni di snapshot, oppure subito se al primo caricamento si recupera lo storico dalle API (preferibile).
4. **Tipo struttura**: le strutture che non sono hotel (ristorante, bar, parcheggio) non hanno i controlli su richieste, prenotazioni e forecast (TRK-10, PRF-02, PRF-07, FCS, D16).
5. **Volume troppo basso** (v0.5): i controlli GA4 non si valutano quando la media degli ultimi 28 giorni è sotto 5 sessioni al giorno (D01, D02, D03) o le sessioni da campagne del mese sono meno di 100 (D09). L'esito mostrato è "volume insufficiente", non OK. Copre anche la fine stagione graduale quando il calendario di apertura non è aggiornato.
6. **Silenziata**: l'allerta resta visibile con stile attenuato, senza notifiche.

### Ciclo di vita (deciso il 21/10/2026)

Nuova → In carico → Risolta, più lo stato Silenziata (tre stati operativi, "In verifica" eliminato).

- Assegnazione automatica all'operatore della struttura per quel ruolo (es. Rossella per Meta su Bucaneve). Chiunque del team può prendere in carico o riassegnare.
- **Più persone sullo stesso ruolo** (**Proposta — da confermare**): il primo nominativo dell'anagrafica per quel ruolo è il titolare e riceve la notifica; gli altri sono co-assegnatari e vedono l'allerta in "Assegnate a me". Il sostituto (es. Mariano per Giorgia su Primula) subentra quando il titolare è segnato assente in Impostazioni.
- **Account condivisi con assegnatari diversi** (**Proposta — da confermare**): ogni account ha una "struttura di riferimento" in anagrafica; l'allerta va all'operatore del ruolo su quella struttura. Se non è indicata, va a tutti gli operatori del ruolo sulle strutture servite.
- In carico: nota facoltativa.
- Risolta: manuale con nota obbligatoria, oppure automatica se la condizione è assente in 2 raccolte consecutive; in quel caso chi l'aveva in carico riceve un avviso nell'hub.
- Silenziata: anomalia nota e accettata (es. pagamento Meta di Vista Mare). Può silenziare lo specialista dell'area o l'account. Motivo obbligatorio, scadenza predefinita 30 giorni, rinnovabile, oppure "senza scadenza". Non genera notifiche né conta nei totali, ma resta visibile con stile attenuato.
- Notifiche solo su creazione ed escalation; presa in carico e risoluzione restano visibili nell'hub senza messaggi.
- Ogni cambio di stato salva chi, quando e nota. La risoluzione non cancella lo storico. Conservazione: 24 mesi.
- Revenue a rischio: non compare nell'MVP, rimandata alla Fase 3.

### Viste del Centro allerte

- Due schede principali: **Allerte** e **Diagnostica tracciamento** (stato dei controlli D per struttura).
- Nelle allerte: Assegnate a me (predefinita), Tutte (aperte), Silenziate e risolte.
- Filtri combinabili: gravità, categoria, canale, cliente, stato.
- Ordine: gravità, poi stato (Nuova prima di In carico), poi età.

### Deduplica

- Chiave: codice allerta + oggetto (struttura, account o campagna).
- BLK-05 (v0.5): sulle campagne Brand la chiave è la campagna; sulle altre è l'account, con l'elenco delle campagne limitate nel dato trigger.
- TRK-13: una sola allerta per account, con l'elenco delle azioni da correggere.
- Se la condizione si ripresenta mentre l'allerta è aperta, si aggiorna il "dato trigger" e il contatore di giorni, senza crearne una nuova.
- Account condivisi (es. un account Meta per tutto il gruppo Eden Roc): l'allerta è una sola, legata all'account, e compare in tutte le strutture collegate senza essere contata più volte.
- BLK-05 e BUD-01 sulla stessa campagna: da quando BUD-01 è attiva restano un'unica allerta (BUD-01, che contiene anche il dato "limitata dal budget").

### Contenuto di ogni allerta

- Gravità, categoria, canale, cliente, struttura (o account), codice.
- Titolo nel formato: canale · problema · conseguenza (es. "Meta · Nessun evento Lead da 48 ore · le campagne ottimizzano senza segnale").
- Dato trigger: i valori che l'hanno fatta scattare (es. "Lead 0 · Sessioni da Meta 312 · ultimo evento 17/10 11:42").
- Revenue a rischio € (dalla Fase 3): mostrata solo se calcolabile da dati reali (Quoto o formula quota impressioni con dati validi); altrimenti il campo non compare. Mai valori stimati senza base.
- Età (da quando è aperta, in ore lavorative per l'escalation), stato, assegnatario.
- Azione suggerita con **percorso nella piattaforma** (es. "Google Ads › Obiettivi › Conversioni › Riepilogo") e link al punto della piattaforma o alla pagina dell'hub.
- **Da controllare dopo l'intervento**: il dato che conferma la soluzione (es. "Lead > 0 nella prossima raccolta"); è la stessa condizione usata per la risoluzione automatica.

### Giorni valutati

- Google Ads, Meta, Quoto, Stape, Fido: giorno precedente (D-1), nel fuso orario dell'account o della struttura indicato in anagrafica.
- GA4: D-1 solo per D01 (sessioni a zero, confermato con il rapporto in tempo reale); gli altri controlli GA4 sul giorno consolidato D-2.
- Search Console: D-3.

### Confronti e baseline (anomalie)

- Confronto con la media dello stesso giorno della settimana nelle 4 settimane precedenti.
- Si usano solo giorni consolidati: GA4 fino a D-2, Search Console fino a D-3.
- Esclusi dalla baseline i giorni di chiusura stagionale.
- Volume minimo per evitare falsi allarmi (indicato per ogni allerta).

### Fonte delle richieste (decisa il 22/10/2026)

Il numero di richieste di una struttura (KPI, costo per richiesta, PRF-02) si prende dalla prima fonte disponibile:

1. Quoto: richieste entrate.
2. CSV dei form WordPress/Elementor: caricamento manuale o lettura automatica.
3. Il valore più alto tra GA4 e la piattaforma del canale (Google Ads o Meta Ads), letto solo sull'azione di richiesta configurata per l'account, mai sul totale delle conversioni della piattaforma.

La pagina indica sempre quale fonte è stata usata e, per il CSV, la data dell'ultimo caricamento.

**Totali e attribuzione** (**Proposta — da confermare**):

- Il "valore più alto" vale **per singolo canale**. Il totale della struttura non è la somma dei canali: con Quoto o CSV è il loro totale; altrimenti è il totale GA4 dell'evento di richiesta su tutti i canali. Somma dei canali e totale possono differire e la pagina lo dice.
- Il gruppo (livello 2) somma i totali delle strutture.
- Se il CSV non copre tutto il periodo, i giorni scoperti si mostrano come "dati fino al gg/mm" e non si integrano con altre fonti.
- Accanto a ogni dato per canale si mostra il modello o la finestra di attribuzione della fonte (GA4: modello della proprietà; Google Ads: modello dell'azione; Meta: finestra dell'account, es. 7 giorni clic · 1 giorno visualizzazione).

### Destinatari

Operatore assegnato al ruolo sulla struttura → casella di settore → webmarketing@ (vedi Fase 0). Il ruolo destinatario è indicato in ogni riga.

### Fasi

MVP-0 · MVP-1 · Fase 2 · Fase 3 · Fase 4 (vedi descrizione-progetto.md).

## 2. Diagnostica tracciamento (controlli giornalieri per struttura)

Ogni controllo ha esito OK / Avviso / Errore. Un controllo in Errore genera l'allerta indicata. La Home mostra il riepilogo per struttura; il dettaglio è nella scheda Diagnostica del Centro allerte.

"Click minimi" per D04 e D07 = il maggiore tra 30 e 3 ÷ tasso di conversione medio della struttura sugli ultimi 90 giorni (es. tasso 2% → 150 click). Senza storico si usa 30.

Dalla v0.5:

- il tasso si calcola **solo sull'azione o sull'evento di richiesta** configurato per l'account, non sul totale delle conversioni (le micro-conversioni lo gonfiano);
- per D04 contano **solo i clic delle campagne Search, Performance Max e Hotel**; i clic di Display, Demand Gen e Video (remarketing a pochi centesimi) sono esclusi sia dal conteggio delle 48 ore sia dal tasso.

Evento di richiesta (D02, D07, richieste): è configurato per ogni struttura e account in anagrafica, mai scelto in automatico. I clic verso il booking engine (es. `booking_engine`) e i contatti (telefono, email, WhatsApp) non sono richieste.

| # | Fonte | Controllo | Errore se | Allerta | Fase |
|---|---|---|---|---|---|
| D01 | GA4 | Raccolta dati attiva | Sessioni = 0 in D-1 **e** nessun utente nel rapporto in tempo reale. Non valutato se la media degli ultimi 28 giorni è sotto 5 sessioni al giorno | TRK-01 | MVP-0 |
| D02 | GA4 | Evento di richiesta configurato presente | 0 eventi nel giorno consolidato (D-2) con sessioni ≥ 50. Valutato solo se l'evento ha in media almeno 1 occorrenza al giorno negli ultimi 28 giorni | TRK-02 | MVP-0 |
| D03 | GA4 | Nessuna duplicazione | Due test separati, dopo il rodaggio: **picco**, eventi di richiesta del giorno consolidato > 1,5 × media dello stesso giorno della settimana, solo se la media è ≥ 3; **duplicazione**, eventi di richiesta ÷ sessioni con l'evento > 1,3 | TRK-03 | MVP-0 |
| D04 | Google Ads | Conversioni registrate | 0 conversioni dell'azione di richiesta da 48h con clic Search/PMax/Hotel ≥ click minimi | TRK-04 | MVP-0 |
| D05 | Google Ads | Collegamento GA4 ↔ Ads attivo | Collegamento assente | TRK-15 | MVP-0 |
| D06 | Meta | Pixel/dataset riceve eventi | Nessun evento da 24h con sessioni da Meta o dal sito ≥ 20 nel giorno | TRK-05 | MVP-0 |
| D07 | Meta | Evento di richiesta configurato presente (Lead/Purchase o altro) | Assente da 48h con click da Meta ≥ click minimi | TRK-06 | MVP-0 |
| D08 | Stape | Eventi server-side in arrivo (solo strutture con Stape) | 0 eventi da 24h | TRK-08 | MVP-0 |
| D09 | GA4 | Traffico da campagne con UTM corretti | Sessioni da campagne attive senza UTM o fuori convenzione > 10%, con almeno 100 sessioni da campagne nel mese. Google Ads con tag automatico (gclid) è conforme, anche quando GA4 mostra google/cpc con campagna "(not set)"; il controllo riguarda Meta, newsletter e altri canali | TRK-09 | MVP-0 |
| D10 | Quoto | Richieste in arrivo (solo strutture con Quoto) | Nessuna richiesta da 72h con media ≥ 1/giorno | TRK-10 | MVP-0 |
| D11 | Search Console | Proprietà accessibile e verificata | Non verificata o accesso perso | TRK-11 | MVP-0 |
| D12 | Hub | Raccolta dati riuscita per ogni connettore | Errore di raccolta | TRK-12 | MVP-0 |
| D13 | Meta | Qualità abbinamento eventi | Sotto la soglia indicata da Meta | TRK-07 | Fase 3 |
| D14 | Search Console | Errori di indicizzazione critici / sitemap | Errori presenti | SEO-03 | Fase 4 |
| D15 | Google Ads | Azioni di conversione primarie non duplicate | Due o più azioni primarie incluse nelle conversioni della stessa categoria (es. "Generate Lead" del tag + "generate_lead" importata da GA4), oppure azioni secondarie (newsletter, visite) primarie con valore | TRK-13 | MVP-0 |
| D16 | Hub | CSV richieste aggiornato (solo strutture la cui fonte è il CSV) | Ultimo caricamento più vecchio di 35 giorni | TRK-14 | MVP-1 |

## 3. Catalogo allerte

### TRK — Tracciamento

| Codice | Canale | Condizione | Gravità | Destinatario | Azione suggerita | Fase |
|---|---|---|---|---|---|---|
| TRK-01 | GA4 | Sessioni = 0 in D-1 e nessun utente in tempo reale (media 28 gg ≥ 5 sessioni/giorno) | Critica | ADV Google + Account | Verificare tag GA4/GTM sul sito | MVP-0 |
| TRK-02 | GA4 | Evento di richiesta = 0 nel giorno consolidato con sessioni ≥ 50 (evento con media ≥ 1/giorno) | Alta | ADV Google | Verificare trigger GTM dell'evento | MVP-0 |
| TRK-03 | GA4 | Eventi di richiesta duplicati: picco > 1,5 × media (media ≥ 3) o più di 1,3 eventi per sessione | Alta | ADV Google | Controllare doppio tag, ricarica della pagina di ringraziamento, deduplica con ID transazione | MVP-0 |
| TRK-04 | Google Ads | 0 conversioni dell'azione di richiesta da 48h con clic Search/PMax/Hotel ≥ click minimi | Critica | ADV Google | Verificare tag/import conversioni; Smart Bidding senza segnale | MVP-0 |
| TRK-05 | Meta | Pixel/dataset senza eventi da 24h con traffico sul sito | Critica | ADV Meta | Verificare pixel e Stape | MVP-0 |
| TRK-06 | Meta | Evento di richiesta assente da 48h con click ≥ click minimi | Alta | ADV Meta | Verificare evento Lead/Purchase | MVP-0 |
| TRK-07 | Meta | Qualità abbinamento eventi sotto soglia | Media | ADV Meta | Arricchire parametri (Enricher) | Fase 3 |
| TRK-08 | Stape | Eventi server-side = 0 da 24h | Critica | ADV Meta + ADV Google | Verificare container sGTM | MVP-0 |
| TRK-09 | GA4 | Traffico da campagne senza UTM o fuori convenzione > 10% (gclid conforme) | Media | Ruolo del canale | Correggere URL finali/parametri | MVP-0 |
| TRK-10 | Quoto | Nessuna richiesta da 72h (media ≥ 1/giorno) | Alta | Account | Verificare form/integrazione Quoto | MVP-0 |
| TRK-11 | Search Console | Proprietà non verificata o accesso perso | Info | Editor | Ripristinare verifica/accesso utente connettore | MVP-0 |
| TRK-12 | Hub | Raccolta di un connettore fallita (sospende i controlli dipendenti) | Alta | Admin | Controllare connettore, Sync del singolo connettore | MVP-0 |
| TRK-13 | Google Ads | Conversioni primarie duplicate o primarie non di richiesta con valore (D15) | Alta | ADV Google | Lasciare primaria una sola azione di richiesta; le altre secondarie. Le offerte automatiche oggi ottimizzano su dati gonfiati | MVP-0 |
| TRK-14 | Hub | CSV richieste WordPress/Elementor non aggiornato da oltre 35 giorni (D16) | Info | Account | Caricare il CSV o attivare la lettura automatica | MVP-1 |
| TRK-15 | Google Ads | Collegamento GA4 ↔ Google Ads assente (D05) | Media | ADV Google | Ricollegare la proprietà GA4 in Google Ads › Strumenti › Account collegati | MVP-0 |

### BLK — Blocchi su account e campagne

Per Meta lo stato si legge a livello di campagna, gruppo di inserzioni e inserzione (stato effettivo e problemi segnalati): una campagna "attiva" con tutti i gruppi terminati o in pausa non è "attiva" per BLK-04 e BLK-06.

| Codice | Canale | Condizione | Gravità | Destinatario | Azione suggerita | Fase |
|---|---|---|---|---|---|---|
| BLK-01 | Meta | Account disabilitato o pagamento in sospeso | Critica | ADV Meta + Account | Verificare metodo di pagamento / ricorso | MVP-0 |
| BLK-02 | Google Ads | Account sospeso o problema di fatturazione | Critica | ADV Google + Account | Verificare fatturazione | MVP-0 |
| BLK-03 | Google / Meta | Annuncio o inserzione **attiva** rifiutata o limitata; per Performance Max, gruppi di asset limitati dalle norme | Alta | Ruolo del canale | Correggere annuncio e reinviare | MVP-0 |
| BLK-04 | Google / Meta | Campagna con almeno un gruppo o inserzione attiva e spesa 0 ieri, con spesa ≥ 20 € nei 7 giorni prima | Critica | Ruolo del canale | Verificare stato, budget, programmazione | MVP-0 |
| BLK-05 | Google Ads | Campagna "limitata dal budget": Brand → Alta, una allerta per campagna; altri tipi → Media, una allerta per account con l'elenco delle campagne (confluisce in BUD-01 quando questa è attiva) | Alta (Brand) · Media (altre) | ADV Google | Valutare aumento budget; per le non Brand verificare se il tetto è voluto | MVP-0 |
| BLK-06 | Google / Meta | Campagna con data di fine entro 3 giorni | Info | Ruolo del canale | Decidere proroga o sostituzione | MVP-0 |
| BLK-07 | Google / Meta | Attività "Attiva" nel piano ma campagna in pausa/chiusa | Alta | Ruolo del canale | Riattivare o aggiornare il piano | Fase 2 |
| BLK-08 | Google Ads | Campagna Brand con budget giornaliero esaurito prima delle 18:00 in almeno 3 degli ultimi 7 giorni (dati per ora) — **Proposta — da confermare** | Media | ADV Google | Aumentare il budget Brand o ridistribuirlo | MVP-1 |

Note tecniche dalla prova a secco (v0.5):

- BLK-03 non usa il motivo di campagna "annunci rifiutati" (`HAS_ADS_DISAPPROVED`): resta anche quando gli annunci rifiutati sono in pausa. Si legge l'approvazione dei singoli annunci attivi (`ad_group_ad.policy_summary.approval_status`); per PMax il motivo `HAS_ASSET_GROUPS_LIMITED_BY_POLICY`.
- BLK-04: le query segmentate per data non restituiscono le campagne a zero; una campagna assente nel giorno D-1 ha spesa 0. `campaign.primary_status_reasons` può valere solo "UNKNOWN": non usarlo da solo.
- BLK-06: le date si leggono con `campaign.start_date_time` ed `campaign.end_date_time` (fine vuota = nessuna data di fine).
- TRK-13: una azione conta se `conversion_action.include_in_conversions_metric` è vero; `primary_for_goal` da solo non basta.

### BUD — Budget e quota impressioni

#### Tipo di campagna

Le soglie della quota impressioni dipendono dal tipo di campagna (deciso il 22/10/2026). Il tipo si ricava in automatico:

- canale dall'API Google Ads (Search, Performance Max, Hotel, Display, Demand Gen, Video);
- brand se il nome contiene "brand" (convenzione "IT | RR | brand") **oppure** se le parole chiave della campagna sono tutte nei termini brand della struttura (anagrafica).

Il tipo si può correggere a mano per singola campagna in Configurazione.

#### Soglie per tipo

| Tipo | Colori quota ottenuta | BUD-01 · persa per budget | BUD-02 · persa per ranking |
|---|---|---|---|
| Brand (ricerca sul nome) | ≥ 85% verde · 70–84% arancio · < 70% rosso | ≥ 10% → Alta | ≥ 15% → Media (concorrenti o OTA sul nome) |
| Hotel Ads | ≥ 80% verde · 60–79% arancio · < 60% rosso | ≥ 10% → Media | ≥ 20% → Media |
| Ricerca generica (località, stagionali, mesi) | nessun colore, solo il valore | ≥ 20% → Media · ≥ 40% → Alta | nessuna soglia fissa; Fase 3: aumento di oltre 15 punti rispetto ai 30 giorni precedenti → Media |
| Performance Max | nessun colore, nota "solo parte ricerca" | ≥ 20% → Media · ≥ 40% → Alta | nessuna |
| Display / remarketing, Demand Gen, Video | non applicabile | — | — |

#### Regole comuni

- Finestra: la pagina mostra 30 giorni; le allerte si valutano sugli ultimi 7 giorni completi.
- Volume minimo: 500 impressioni nella finestra di valutazione; sotto questa soglia l'allerta non parte.
- Quota sotto il 10%: Google restituisce 0,0999. Si mostra "< 10%" e il mercato non acquisito € non si calcola.
- Quota Brand pesata (card della pagina Google Ads): le campagne con quota "< 10%" sono escluse dal peso.
- Valori configurabili per struttura.

#### Allerte

| Codice | Canale | Condizione | Gravità | Destinatario | Azione suggerita | Fase |
|---|---|---|---|---|---|---|
| BUD-01 | Google Ads | Quota impressioni persa per budget oltre la soglia del tipo di campagna | Vedi tabella | ADV Google + Account | Proposta aumento budget; mostra mercato non acquisito € | Fase 3 (**Proposta — da confermare**: MVP-1) |
| BUD-02 | Google Ads | Quota impressioni persa per ranking oltre la soglia del tipo di campagna | Media | ADV Google | Rivedere annunci, offerte, Quality Score | Fase 3 |
| BUD-03 | Google / Meta | Spesa del mese ±20% rispetto al budget atteso | Media | Ruolo del canale + Account | Riallineare budget | Fase 2 |
| BUD-04 | Google / Meta | Budget atteso non disponibile: nessun documento di budget, nessun importo nel piano e nessuno storico dell'anno precedente | Info | Account | Caricare il documento di budget tra i materiali del cliente | Fase 2 |

BUD-01 in MVP-1: usa gli stessi dati di BLK-05 e risolve subito la loro deduplica; il caso del Brand di Primula (persa per budget 21% con 4 €/giorno) mostra che serve dal primo rilascio della pagina Google Ads.

**Budget atteso**, dalla prima fonte disponibile (**Proposta — da confermare** il gradino 2):

1. Documento di budget inserito tra i materiali del cliente.
2. Importo indicato nel piano marketing per l'attività "Attiva" (es. "Attiva (10€/gg)"), riportato nel task Fido.
3. Investimento dell'anno precedente sullo stesso canale e periodo.
4. Nessuna delle tre: BUD-03 non si calcola e si apre BUD-04.

#### Mercato non acquisito €

Si calcola con i soli dati Google Ads della campagna, separando budget e ranking:

- impressioni potenziali = impressioni ÷ quota ottenuta;
- impressioni perse = impressioni potenziali × quota persa (per budget o per ranking);
- click persi = impressioni perse × CTR della campagna;
- valore € = click persi × CPC medio.

La pagina mostra i valori di partenza e la formula ed etichetta il risultato come stima. Il valore non si calcola se la quota ottenuta è < 10%, se le impressioni sono sotto il volume minimo, se CTR o CPC sono zero, e per Performance Max, Hotel Ads, Display, Demand Gen e Video (reti diverse dalla ricerca o asta con CTR e costi non confrontabili: la formula sovrastimerebbe).

Esempio (Galli's, "IT | RR | autunno", 22/09–21/10/2026): 6.646 impressioni ÷ 10,3% = circa 64.500 potenziali × 22,5% perse per budget = circa 14.500 impressioni × CTR 8,2% = circa 1.190 click × CPC 0,53 € = circa 630 € nei 30 giorni.

### PRF — Anomalie di performance

| Codice | Canale | Condizione | Volume minimo | Gravità | Destinatario | Fase |
|---|---|---|---|---|---|---|
| PRF-01 | GA4 | Sessioni −30% vs baseline | 100 sessioni/settimana | Alta | Account | Fase 3 |
| PRF-02 | Richieste (fonte a cascata) | Richieste −30% vs baseline | 20/settimana | Alta | Account | Fase 3 |
| PRF-03 | Google Ads | CPA o CPC +40% vs baseline | 30 click/settimana | Media | ADV Google | Fase 3 |
| PRF-04 | Meta | ROAS o costo per risultato 7gg peggiorato del 40% vs 30gg precedenti | 10 risultati | Alta | ADV Meta | Fase 3 |
| PRF-05 | Meta | Frequenza 7gg oltre la soglia del tipo: prospezione > 4,0 · retargeting > 8,0 | 1.000 impressioni | Media | ADV Meta | Fase 3 |
| PRF-06 | Meta | CTR −25% su 7gg | 1.000 impressioni | Media | ADV Meta | Fase 3 |
| PRF-07 | GA4 | Tasso di conversione sotto il 50% della media del portafoglio (solo strutture dello stesso tipo e con la stessa fonte delle richieste) | 500 sessioni/mese | Info | Account | Fase 3 |
| PRF-08 | Newsletter | Tasso di apertura o clic −30% vs media degli ultimi 5 invii (solo strumenti che forniscono aperture e clic) | 500 destinatari | Media | Mail marketing | Fase 2 |

Il tipo Meta (prospezione o retargeting) si ricava dal pubblico del gruppo di inserzioni (pubblico personalizzato del sito = retargeting) e si può correggere a mano.

### PLN — Piano marketing

Dal 30/10/2026 le attività approvate del piano diventano task in Fido; stato e scadenze si leggono da Fido (vedi decisioni-fase-0.md §3).

| Codice | Condizione | Gravità | Destinatario | Fase |
|---|---|---|---|---|
| PLN-01 | Task di piano in scadenza entro 7 giorni senza riscontro | Info | Ruolo dell'area | Fase 2 |
| PLN-02 | Task di piano scaduto non eseguito | Alta | Ruolo dell'area + Account | Fase 2 |
| PLN-03 | Abbinamento campagna/attività da confermare | Info | Ruolo dell'area | Fase 2 |
| PLN-04 | Newsletter prevista dal piano senza invio registrato entro la data | Alta | Mail marketing | Fase 2 |

PLN-02 non ha più il passaggio automatico a Critica dopo 7 giorni: vale l'escalation generale.

### CTR — Contratti e gestione (Fido)

| Codice | Condizione | Gravità | Destinatario | Fase |
|---|---|---|---|---|
| CTR-01 | Contratto o rinnovo marketing in scadenza entro 30 giorni | Info | Account | MVP-0 |
| CTR-02 | Dato anagrafico incoerente (es. sito con marketing attivo ma cessato in Fido) | Info | Admin | MVP-0 |
| CTR-03 | Ore del contratto consumate più velocemente del previsto: oltre l'80% prima del giorno 20 del mese, o oltre il 100% | Media | Account | MVP-0 |

### SEO — Visibilità (Fase 4)

| Codice | Condizione | Gravità | Destinatario |
|---|---|---|---|
| SEO-01 | CTR Search Console −30% su 28 giorni (≥ 1.000 impressioni) | Media | Editor |
| SEO-02 | Query monitorata in Search Console: posizione media peggiorata di 3 o più, o uscita dalla top 10 (28 giorni vs 28 precedenti) | Media | Editor |
| SEO-03 | Errori di indicizzazione critici o sitemap con errori | Alta | Editor |
| SEO-04 | Visibilità nelle risposte AI (RankScale) in calo di oltre 10 punti su 30 giorni, o concorrente che supera la struttura sulle domande monitorate — **Proposta — da confermare** | Media | Editor |

RankScale misura la visibilità nelle risposte delle AI (menzioni, citazioni, sentiment, concorrenti), non le posizioni su Google: per questo SEO-02 usa Search Console.

### FCS — Forecast (Fase 4)

| Codice | Condizione | Gravità | Destinatario |
|---|---|---|---|
| FCS-01 | Occupazione prevista < 70% in una delle prossime 6 settimane | Alta | Account + ADV |
| FCS-02 | Occupazione prevista 70–79% | Media | Account |
| FCS-03 | Pick-up fermo: nessuna nuova prenotazione da 4 giorni su una settimana futura | Media | Account |
| FCS-04 | Dati forecast del cliente non aggiornati da oltre 14 giorni | Info | Account |

Regola: il sistema consiglia canale e settimana; l'operatore approva e crea l'attività. Nessuna modifica automatica di budget. Soglie configurabili per struttura e stagione. L'occupazione usa le camere totali della struttura per periodo (anagrafica); sospeso nei periodi di chiusura.

## 4. Prova a secco del 30/10/2026

Controlli MVP-0 eseguiti a mano su Google Ads (8 strutture) e GA4 (6 strutture) con le regole v0.4: 23 allerte, 19 vere o probabilmente vere, 4 false. Le 4 false (BLK-04 su campagne quasi ferme, TRK-04 su remarketing Display, TRK-01 a fine stagione) sono eliminate dalle regole v0.5. Prima del rilascio vanno corrette le conversioni degli account pilota: TRK-13 scatta oggi su tutti gli 8 account.

## 5. Analisi AI (predisposta dall'MVP, attiva in Fase 3)

- Pulsante "Spiega questa allerta" su ogni allerta: Claude legge dato trigger, storico e fonti collegate e propone cause e verifiche.
- Suggerimenti proattivi ammessi solo con almeno due fonti concordanti; mostrano sempre periodo, fonti, confidenza, impatto stimato e un'azione verificabile; possono diventare task Fido.
- Nessuna azione automatica sulle piattaforme.
- Fuori perimetro (da Visual Impact): matrice di correlazione, radar di performance, attribuzione multi-touch, "confidenza del modello" senza base di calcolo.
