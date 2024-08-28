# Travel Ease API

Questo progetto è il backend di Travel Ease, un'applicazione web progressiva (PWA) per la pianificazione e gestione dei viaggi. Il backend è sviluppato con Laravel e fornisce API REST per supportare le funzionalità del frontend.

## Funzionalità

Gestione Utenti: Autenticazione tramite Laravel Sanctum.
Gestione Viaggi: CRUD completo per i viaggi e le tappe.

## Requisiti

-   PHP: ^8.1
-   Composer: ^2.0

## Installazione

#### Clona il repository:

-   git clone https://github.com/Alex-Sibiriu/travel-app-BE.git
-   cd travel-app-BE

#### Installa le dipendenze:

-   composer install

#### Configura il file .env:

-   cp .env.example .env
-   php artisan key:generate

#### Esegui le migrazioni:

-   php artisan migrate

#### Avvia il server di sviluppo:

-   php artisan serve

## Tecnologie Utilizzate

-   Laravel Framework: ^10.10
-   Laravel Sanctum: Per l'autenticazione API
-   GuzzleHTTP: Per le richieste HTTP
