
# MusicBoxApi

MusicBoxApi est une API RESTful développée avec Laravel, conçue pour gérer une bibliothèque musicale. Elle permet la gestion des artistes, albums, chansons et utilisateurs, avec une documentation Swagger intégrée.

## Fonctionnalités principales
- Gestion des artistes, albums et chansons (CRUD)
- Authentification et gestion des utilisateurs
- Documentation API Swagger (OpenAPI)
- Sécurité via Laravel Sanctum
- Tests unitaires et fonctionnels

## Prérequis
- PHP >= 8.1
- Composer
- MySQL ou autre base de données compatible
- Node.js & npm (pour assets front-end)

## Installation
1. Clonez le dépôt :
	```bash
	git clone <url-du-repo>
	cd MusicBoxApi
	```
2. Installez les dépendances PHP :
	```bash
	composer install
	```
3. Installez les dépendances front-end :
	```bash
	npm install
	```
4. Copiez le fichier d'environnement :
	```bash
	cp .env.example .env
	```
5. Générez la clé d'application :
	```bash
	php artisan key:generate
	```
6. Configurez la base de données dans `.env`.
7. Lancez les migrations :
	```bash
	php artisan migrate
	```
8. (Optionnel) Seed la base de données :
	```bash
	php artisan db:seed
	```
9. Compilez les assets front-end :
	```bash
	npm run build
	```
10. Démarrez le serveur :
	 ```bash
	 php artisan serve
	 ```

## Documentation API
La documentation Swagger est accessible à l'adresse `/api/documentation` une fois le serveur lancé.

## Tests
Lancez les tests avec :
```bash
php artisan test
```

## Structure du projet
- `app/Models` : Modèles Eloquent (Album, Artist, Song, User)
- `app/Http/Controllers` : Contrôleurs API
- `routes/api.php` : Routes de l'API
- `database/migrations` : Migrations de la base de données
- `resources/views` : Vues (si besoin)

## Contribution
Les contributions sont les bienvenues !

## Licence
Ce projet est sous licence MIT.
