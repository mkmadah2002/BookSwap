# BookSwap — API REST Laravel 13

BookSwap est une API pédagogique d’échange de livres informatiques. Elle fournit aux apprenants des données réalistes pour pratiquer `fetch`, les promesses et `async/await`. L’API ne possède volontairement aucune authentification : les écritures sont réservées à un environnement local de formation.

## Prérequis

- PHP 8.3 ou supérieur : `php -v`
- Composer 2 : `composer --version`
- Extensions SQLite : `php -m` doit afficher `pdo_sqlite` et `sqlite3`

Pour activer SQLite, localisez le fichier avec `php --ini`, puis retirez le `;` devant `extension=pdo_sqlite` et `extension=sqlite3`. Redémarrez ensuite le terminal. Sous Debian/Ubuntu : `sudo apt install php8.3-sqlite3`. Sous macOS avec Homebrew, SQLite est inclus dans `brew install php`.

## Installation

```bash
git clone <adresse-du-projet> bookswap
cd bookswap
composer install
```

Sous Windows :

```powershell
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File -Force
php artisan key:generate
php artisan migrate --seed
php artisan l5-swagger:generate
```

Sous macOS/Linux :

```bash
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan l5-swagger:generate
chmod +x START_BOOKSWAP.sh RESET_DATABASE.sh
```

Une base SQLite préremplie est fournie. Les migrations ci-dessus ne suppriment pas les données existantes. Pour recréer volontairement la démonstration, utilisez `RESET_DATABASE.bat` ou `./RESET_DATABASE.sh` et confirmez avec `OUI`.

## Démarrage

Sous Windows, double-cliquez sur `START_BOOKSWAP.bat`. Sous macOS/Linux, lancez `./START_BOOKSWAP.sh`. Le script vérifie PHP, SQLite, Composer, `.env`, la base, les migrations et le port 8000, puis ouvre Swagger.

Démarrage manuel :

```bash
php artisan serve
```

- API principale : http://127.0.0.1:8000/api/v1/exchange-requests
- Swagger UI avec **Try it out** : http://127.0.0.1:8000/api/documentation

Swagger UI et ses assets sont installés par Composer et fonctionnent hors ligne.

## Exemple JavaScript

```js
async function chargerDemandes() {
  const reponse = await fetch('http://127.0.0.1:8000/api/v1/exchange-requests?status=open');
  if (!reponse.ok) throw new Error(`Erreur HTTP ${reponse.status}`);
  const resultat = await reponse.json();
  console.log(resultat.data);
}

chargerDemandes();
```

## Endpoints

| Méthode | URL | Usage |
|---|---|---|
| GET | `/api/v1/categories` | Catégories |
| GET | `/api/v1/tags` | Tags |
| GET | `/api/v1/books` | Livres paginés |
| GET | `/api/v1/books/{id}` | Détail d’un livre |
| GET | `/api/v1/exchange-requests` | Demandes paginées et filtrables |
| GET | `/api/v1/exchange-requests/{id}` | Détail et offres |
| GET | `/api/v1/exchange-requests/{id}/offers` | Offres d’une demande |
| GET | `/api/v1/exchange-offers/{id}` | Détail d’une offre |
| POST/PUT/PATCH/DELETE | `/api/v1/exchange-requests...` | CRUD pédagogique des demandes |
| POST/PUT/PATCH/DELETE | `/api/v1/exchange-...offers...` | CRUD pédagogique des offres |

Filtres combinables sur les demandes : `search`, `exchange_type`, `category_id`, `status`, `sort=newest|oldest` et `page`.

## Tests

Les tests utilisent SQLite en mémoire et ne touchent jamais `database/database.sqlite` :

```bash
php artisan test
```

## Dépannage

- **PHP/Composer non reconnu** : installez-les, fermez et rouvrez le terminal, puis contrôlez le `PATH` avec `where php` sous Windows ou `which php` sous macOS/Linux.
- **SQLite absent** : activez `pdo_sqlite` et `sqlite3` dans le `php.ini` indiqué par `php --ini`.
- **Port 8000 occupé** : fermez le processus concerné ou utilisez `php artisan serve --port=8001` (Swagger restera configuré sur 8000 jusqu’à modification de son serveur).
- **Erreur 500** : lancez `php artisan optimize:clear`, vérifiez `.env`, `APP_KEY` et `storage/logs/laravel.log`. En production, `APP_DEBUG=false` masque les traces internes.
- **Erreur CORS** : utilisez une origine locale (`localhost` ou `127.0.0.1`) et vérifiez `config/cors.php`.
- **Base introuvable** : créez `database/database.sqlite`, puis lancez `php artisan migrate --seed`.
- **Swagger inaccessible** : lancez `php artisan l5-swagger:generate`, puis vérifiez que `composer install` a installé `darkaonline/l5-swagger`.

## Distribution Windows portable

`PACKAGE_PORTABLE_WINDOWS.ps1` construit une distribution avec le runtime PHP actuellement installé, `vendor`, la base et Swagger. Exécutez-le après `composer install`. Le résultat doit être testé sur une machine propre de même architecture (x64/x86) ; un runtime PHP Windows n’est pas universel et doit provenir d’une installation officielle fiable.
