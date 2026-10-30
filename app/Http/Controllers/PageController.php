<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Pagine dell'MVP-0 ancora vuote: mostrano cosa arriverà e in quale passo dello sviluppo
 * (docs/specifica-tecnica-mvp0.md §11).
 */
class PageController extends Controller
{
    public function overview(): View
    {
        return view('pages.placeholder', [
            'title' => 'Overview di portafoglio',
            'intro' => 'Le priorità di oggi su tutte le tue strutture.',
            'coming' => [
                ['Esito dell\'ultima raccolta dati', 'Passo 3 · Raccolta'],
                ['Allerte Critiche e Alte aperte', 'Passo 5 · Centro allerte e overview'],
                ['Diagnostica del tracciamento per struttura', 'Passo 5 · Centro allerte e overview'],
            ],
        ]);
    }

    public function clients(): View
    {
        return view('pages.placeholder', [
            'title' => 'Clienti',
            'intro' => 'Clienti e strutture con allerte aperte e stato dei canali.',
            'coming' => [
                ['Elenco dei clienti pilota importati da Fido', 'Passo 2 · Anagrafica'],
                ['Allerte aperte e stato dei canali per struttura', 'Passo 5 · Centro allerte e overview'],
            ],
        ]);
    }

    public function alerts(): View
    {
        return view('pages.placeholder', [
            'title' => 'Centro allerte',
            'intro' => 'Allerte di tutte le strutture e diagnostica del tracciamento.',
            'coming' => [
                ['Allerte di blocco, tracciamento e contratti', 'Passo 4 · Motore delle regole'],
                ['Presa in carico, risoluzione e silenziamento', 'Passo 5 · Centro allerte e overview'],
                ['Escalation e notifiche Teams ed email', 'Passo 6 · Escalation e notifiche'],
            ],
        ]);
    }

    public function settings(): View
    {
        return view('pages.placeholder', [
            'title' => 'Impostazioni',
            'intro' => 'Connettori, clienti e strutture, account, utenti e soglie.',
            'coming' => [
                ['Clienti, strutture, account e assegnazioni', 'Passo 2 · Anagrafica'],
                ['Stato dei connettori, storico raccolte e Sync', 'Passo 3 · Raccolta'],
                ['Soglie predefinite e per struttura', 'Passo 4 · Motore delle regole'],
            ],
        ]);
    }
}
