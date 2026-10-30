# Analisi progetto Replit "Visual Impact" (Hotel Marketing Dashboard)

Letto il 20/10/2026 dallo ZIP esportato da Replit, senza modifiche. Riferimento per le specifiche dell'Hub Marketing.

## Cos'è

- Mockup React interattivo "Hotel Marketing Dashboard" con pannello "MVP SPEC v0.1" (annotazioni Owner/Fonte/Regola/Output) + bozza di backend Express che chiama 7 server MCP (ga4, meta, gads, gsc, rankscale, quoto, fido) via HTTP/SSE.
- Video di presentazione "Hub Hotel Intelligence" (metodo forecast).
- Tutta l'"intelligenza" (allerte, forecast, AI) è solo dato di esempio: nessun job, nessun DB usato, nessun calcolo reale.
- Non presenti: Simple Booking, Stape, idsito unico, ruoli implementati.

## Navigazione

MODULI: Overview · Centro Allerte · Cross-channel AI · Forecast & Occupancy · Piano Marketing — CANALI: Meta Ads · Google Ads + ISS · GA4 · Search Console · RankScale — CRM: Quoto · Fido — IMPOSTAZIONI: Connettori · Configurazione Hotel. Canali mono-hotel (selettore), Allerte e Diagnostica di portafoglio.

## Diagnostica tracciatura — 12 check per hotel

GA4: tracking attivo · evento prenota_ora tracciato · nessun hit duplicato — Google Ads: tag sito presente · conversioni importate da GA4 — Meta: pixel attivo · evento Purchase tracciato · match rate audience >80% — GSC: sito verificato · nessun coverage error critico — RankScale: dominio tracciato — Quoto: sync prenotazioni attivo. Regole: check fallito da 24h → attenzione, da 48h → priorità alta. Evento mancante/duplicato genera alert, non correzione manuale. Messaggi leggibili e azionabili (es. "Nessun evento Purchase nelle ultime 48h — Pixel ID … attivo ma trigger mancante su step 3 checkout") con pulsante "Risolvi".

## Centro allerte

- Severità: Critico / Allerta / Avviso / OK. Categorie: Tracciatura, Performance, Budget, SEO, CRM. Filtri per categoria e hotel; ordinamento per severità.
- Struttura allerta: severità, categoria, canale, hotel, titolo, Analisi, Dato trigger, Revenue a rischio €, età, link al modulo, azioni rapide.
- KPI: critici, allerte, avvisi, check tracciatura, "Revenue a rischio" (somma).
- Workflow annotato: Nuova → In carico → In verifica → Risolta, con presa in carico, assegnazione, nota, timestamp, storico, deduplica, filtro per owner (il mockup implementa solo Nuovo/In analisi/Risolto).

### Catalogo allerte di esempio

| Sev. | Canale | Condizione |
|---|---|---|
| Critico | Google Ads | IS persa per budget 40% (mercato non acquisito €/sett), budget Brand esaurito entro le 14 |
| Critico | Meta | Pixel carica ma evento Purchase assente da 4h |
| Allerta | Google Ads | Tag sito assente, 0 conversioni 24h, Smart Bidding "cieco" |
| Allerta | Meta | ROAS 7gg −45% vs media 30gg precedenti |
| Allerta | GSC | CTR −38%, keyword chiave perse 2–4 posizioni |
| Allerta | Quoto | Occupancy 61% vs target 80% in una settimana |
| Avviso | RankScale | Keyword in calo posizionale |
| Avviso | GA4 | Conv. rate sotto la media di portafoglio |
| Avviso | Google Ads | Budget esaurito ieri alle 14:00 |
| Avviso | Quoto | Gap camere settimana prossima |
| Avviso | Meta | Frequenza > 4.0 (saturazione audience) |
| OK | GA4 / Google Ads | Tracciatura completa / performance nella norma |

## Formule e soglie

- Mercato non acquisito € = IS persa × volume stimato × CPC medio, separando budget e ranking ("esporre campo originale e formula; non stimare senza volume e CPC validi").
- Google Ads: ROAS = Σ valore conv / Σ costo. Colori IS ≥60 verde, ≥40 arancio, <40 rosso; IS persa budget ≥40 rosso, ≥20 arancio.
- Meta: ROAS = Σ valore acquisti / Σ spesa; CPA = Σ spesa / Σ conversioni; ROAS ≥8 / ≥4 / <4. Regola: stessa finestra di attribuzione per tutte le metriche, mostrata sempre.
- Quoto: close rate = chiusi/ricevuti; conv. inviati→chiusi = chiusi/inviati; fatturato = somma preventivi chiusi; solo dati aggregati (privacy).
- Forecast: ≥80% ottimale, 70–79% attenzione, <70% intervento; gap = camere totali − prenotate; soglie configurabili per hotel e stagione; il sistema consiglia, l'operatore approva (nessuna modifica automatica di budget).
- RankScale: delta = posizione precedente − attuale; posizione ≤3 verde, ≤10 arancio.
- GSC: posizione media con un decimale; opportunità = impression alte + CTR basso o calo posizione.

## Cross-channel AI

- Segnali con urgenza 0–100, priorità (Critico/Allerta/Opportunità), fonti, impatto €, catena causale, diagnosi, confidenza %, prescrizione, pulsante "Attiva".
- Regola: solo insight con almeno due fonti concordanti; mostrare periodo e confidenza; ogni suggerimento cita le fonti, ha priorità, impatto stimato e azione verificabile; può diventare task.
- Brief operativi per ruolo (Google Ads, Meta, SEO/Content, CRO) con passi, percorso in piattaforma e KPI da monitorare.
- Attribuzione: modello dichiarato e coerente, mai presentato come certezza; percorso sorgente → sessione → preventivo → chiusura → fatturato.
- Matrice di correlazione e radar: presenti ma senza formula definita.

## Forecast (video)

Forecast 6 settimane, diagnosi "occupazione reale vs target", "Pace pick-up fermo", raccomandazione budget con ROI, riallocazione budget tra settimane forti e deboli. Ciclo: Forecast → Attivazione → Misurazione.

## Configurazione hotel e connettori

- Per hotel: GA4 property, Google Ads ID, Meta pixel/account, GSC dominio, Quoto ID, Fido ID; previsti valuta, timezone, target vendite dirette, soglie occupazione.
- Connettori: stato, ultimo sync, durata, n. tool, retry; storico sync con esito, timestamp, record importati, errore sintetico.
- Ruoli annotati: matrice persona × hotel × permesso con audit; cliente read-only sul proprio hotel.

## Incoerenze interne del mockup

Stati allerte (3 vs 4); Piano Marketing come elenco documenti Drive vs calendario attività; Fido descritto come ERP, come CRM DEM e come contatti; forecast 4 vs 6 settimane; 18 vs 12 check; formula mercato non acquisito in due versioni.
