<?php

/*
 * Configurazione generale dell'Hub Marketing.
 * Orari e caselle sono quelli di docs/decisioni-fase-0.md §5 e §8 e di docs/specifica-tecnica-mvp0.md §4.
 * Le soglie delle allerte stanno in config/alerts.php (passo 4 dello sviluppo).
 */

return [

    // Solo gli account Google di questo dominio possono accedere.
    'login_domain' => env('HUB_LOGIN_DOMAIN', 'network-service.it'),

    // Accesso senza Google per lo sviluppo locale. Ignorato fuori dagli ambienti local e testing.
    'dev_login' => (bool) env('HUB_DEV_LOGIN', false),

    'business_hours' => [
        'start' => '08:30',
        'end' => '17:30',
        'days' => [1, 2, 3, 4, 5], // lunedì–venerdì (ISO-8601)
    ],

    'schedule' => [
        'collect' => '05:30',
        'retry_failed' => '07:15',
        'evaluate_rules' => '07:45',
        'daily_digest' => '08:30',
    ],

    'notifications' => [
        'general_mailbox' => env('HUB_MAILBOX_GENERAL', 'webmarketing@network-service.it'),
        'weekend_mailbox' => env('HUB_MAILBOX_WEEKEND'),
        'sector_mailboxes' => [
            'adv_google' => env('HUB_MAILBOX_ADV'),
            'adv_meta' => env('HUB_MAILBOX_ADV'),
            'mail_marketing' => env('HUB_MAILBOX_MAIL_MARKETING'),
            'editor' => env('HUB_MAILBOX_EDITOR'),
            'account' => env('HUB_MAILBOX_ACCOUNT'),
        ],
    ],

];
