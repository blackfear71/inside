![INSIDE](http://77.153.236.140/inside/includes/icons/inside_readme.png)

# INSIDE - Plateforme de partage créée par les membres pour les membres.

## Lien
Site accessible depuis le lien suivant : [INSIDE](http://77.153.236.140/inside/)

## Fonctionnalités
- MOVIE HOUSE : base de données de films et organisation de soirées cinéma
- EXPENSE CENTER : outil de suivi des dépenses des membres
- LES PETITS PEDESTRES : organisation d'entrainements ou de courses à pied
- CALENDARS : calendriers de l'équipe
- COLLECTOR ROOM : collection de phrases cultes
- MISSIONS : INSIDER : évènements du site
- #THEBOX : boîte à idées
- NOTIFICATIONS : centre de notifications générales
- PROFIL : gestion paramètres et succès
- INSIDE ROOM : chat général

## Notes aux développeurs
Ne pas toucher aux fichiers suivants lors de vos développements :
- appel_bdd.php
- appel_mail.php
- export_bdd.php
- content_chat.xml

Si des différences sont constatées, veuillez les annuler.

## Variables utiles
### Les couleurs
Les couleurs RGB sont principalement utilisées pour la transparence. Dans les autres cas, utiliser les codes hexadécimaux. Voici les couleurs principalement représentées sur la plateforme :

| Nom             | Couleur                                                  | Code HEX | Code RGB           | Notes                                  |
| ----------------| :------------------------------------------------------: | -------- | ------------------ | -------------------------------------- |
| Rouge           | ![#ff1937](https://placehold.it/15/ff1937/000000?text=+) | #ff1937  | rgb(255, 25, 55)   | Rouge CGI, couleur principale          |
| Rouge           | ![#c81932](https://placehold.it/15/c81932/000000?text=+) | #c81932  | rgb(200, 25, 50)   | Pour contraste (rift)                  |
| Gris clair      | ![#f3f3f3](https://placehold.it/15/f3f3f3/000000?text=+) | #f3f3f3  |                    |                                        |
| Gris clair      | ![#e3e3e3](https://placehold.it/15/e3e3e3/000000?text=+) | #e3e3e3  | rgb(227, 227, 227) |                                        |
| Gris clair      | ![#d3d3d3](https://placehold.it/15/d3d3d3/000000?text=+) | #d3d3d3  |                    |                                        |
| Gris clair      | ![#c3c3c3](https://placehold.it/15/c3c3c3/000000?text=+) | #c3c3c3  |                    |                                        |
| Gris clair      | ![#b3b3b3](https://placehold.it/15/b3b3b3/000000?text=+) | #b3b3b3  |                    |                                        |
| Gris clair      | ![#a3a3a3](https://placehold.it/15/a3a3a3/000000?text=+) | #a3a3a3  |                    |                                        |
| Gris foncé      | ![#7b8084](https://placehold.it/15/7b8084/000000?text=+) | #7b8084  |                    | Pour ombres                            |
| Gris foncé      | ![#2c3840](https://placehold.it/15/2c3840/000000?text=+) | #2c3840  |                    | Pour ombres & contraste (rift switchs) |
| Gris/bleu foncé | ![#374650](https://placehold.it/15/374650/000000?text=+) | #374650  |                    | Lien portail & switchs                 |
| Bleu clair      | ![#74cefb](https://placehold.it/15/74cefb/000000?text=+) | #74cefb  |                    |                                        |
| Bleu clair      | ![#2eb2f4](https://placehold.it/15/2eb2f4/000000?text=+) | #2eb2f4  |                    |                                        |
| Vert clair      | ![#91d784](https://placehold.it/15/91d784/000000?text=+) | #91d784  |                    |                                        |
| Jaune clair     | ![#fffde8](https://placehold.it/15/fffde8/000000?text=+) | #fffde8  |                    |                                        |
| Jaune clair     | ![#fffd4c](https://placehold.it/15/fffd4c/000000?text=+) | #fffd4c  |                    |                                        |
| Jaune moyen     | ![#ffad01](https://placehold.it/15/ffad01/000000?text=+) | #ffad01  |                    |                                        |
| Blanc           | ![#ffffff](https://placehold.it/15/ffffff/000000?text=+) | #ffffff  | rgb(255, 255, 255) |                                        |
| Noir            | ![#000000](https://placehold.it/15/000000/000000?text=+) | #000000  | rgb(0, 0, 0)       |                                        |


### Les variables globales
Les variables globales ($_SESSION et $_COOKIE) sont généralement organisées sous forme de tableaux regroupant leur contenu en catégories. Ceci facilite la lecture des données pour les développeurs.

| SESSION  | Description                                              |
| -------- | -------------------------------------------------------- |
| index    | Contient les données de l'écran de connexion             |
| alerts   | Contient les tops de déclenchement des messages d'alerte |
| user     | Contient les données utilisateurs et préférences         |
| missions | Contient les données des missions générées               |
| theme    | Contient les données des thèmes                          |
| chat     | Contient les données du chat (données utilisateurs)      |

| COOKIE   | Description                                         |
| -------- | --------------------------------------------------- |
| showChat | Etat de la fenêtre de chat                          |
