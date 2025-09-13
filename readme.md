# didata-cc

Repository Git : [https://github.com/Tarik-Benali/didata-cc](https://github.com/Tarik-Benali/didata-cc)

---

# Table des matières

1. Introduction
2. Prérequis
3. Installation & Lancement
4. Structure du projet
5. Commandes utiles
6. Exemple rapide
7. Exécuter les tests

---

# Introduction

Ce projet "didata-cc" est une application full-stack composée d'un backend Laravel et d'un frontend Nuxt 3. Le tout est containerisé via Docker pour simplifier l'installation et l'exécution.

---

# Prérequis

- Docker & Docker Compose installés
- Git

---

# Installation & Lancement

```bash
# Cloner le repo
git clone https://github.com/Tarik-Benali/didata-cc.git
didata-cc

# Lancer tous les services en mode build
cd didata-cc
docker-compose up --build
```

Les services exposés par défaut :

- Backend Laravel : `http://localhost:8000`
- Frontend Nuxt 3 : `http://localhost:3000`
- MySQL : port 3307 sur l'hôte

Pour arrêter les services :

```bash
docker-compose down
```

---

# Structure du projet

```
didata-cc/
├── backend/          # Laravel backend
├── frontend/         # Nuxt 3 frontend
├── docker-compose.yml
├── README.md
└── .gitignore
```

---

# Commandes utiles

## Backend Laravel

```bash
# Accéder au conteneur backend
docker exec -it didata_laravel bash

# Lancer les migrations (si besoin)
php artisan migrate --force

# Lancer le serveur Laravel (si conteneur en mode shell)
php artisan serve --host=0.0.0.0 --port=8000
```

## Frontend Nuxt 3

```bash
# Accéder au conteneur frontend
docker exec -it didata_frontend sh

# Installer les dépendances (si besoin)
npm install

# Lancer le build de production
npm run generate

# Lancer le serveur de développement
npm run dev
```

---

# Exemple rapide

```bash
# Cloner
git clone https://github.com/Tarik-Benali/didata-cc.git
didata-cc

# Lancer les services
docker-compose up --build

# Accéder à :
# Backend : http://localhost:8000
# Frontend : http://localhost:3000
```

---

# Exécuter les tests

## Backend Laravel

```bash
# Accéder au conteneur backend
docker exec -it didata_laravel bash

# Lancer les tests PHPUnit
php artisan test
```

## Frontend Nuxt 3

```bash
# Accéder au conteneur frontend
docker exec -it didata_frontend sh

# Lancer les tests unitaires (Vitest ou Jest selon config)
npm run test
```

