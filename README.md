# Gestionnaire de Parking

## Description

Projet de gestion d’un parking développé en PHP (architecture MVC) avec une interface JavaScript pour la gestion dynamique des tarifs horaires.  
Ce système permet la gestion des places, des réservations, ainsi que la personnalisation des tarifs horaires par jour et tranche horaire via un fichier PHP sans base de données.

---

## Fonctionnalités principales

- Gestion du nombre de places disponibles dans le parking.
- Réservation de places avec contrôle des plages horaires.
- Configuration des tarifs horaires par jour de la semaine.
- Tarification flexible :
    - Tarif fixe pour toute la journée.
    - Tarifs en tranches horaires (exemple : 00:00-15:00, 15:00-24:00).
- Interface d’administration simple pour modifier les tarifs via un formulaire dynamique (sans base de données).
- Contrôle des heures d’ouverture et de fermeture.
- Limitation du temps maximal de stationnement consécutif.

---

## Structure du projet

- **Model** : Manipulation des données (fichier `parkingInfos.php`).
- **View** : Interface utilisateur en HTML/CSS/JavaScript.
- **Controller** : Logique métier et interaction entre Model et View.

---

## Installation

1. Cloner ce dépôt :
   ```bash
   git clone https://github.com/tonpseudo/gestionnaire-parking.git
