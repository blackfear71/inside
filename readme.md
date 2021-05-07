![INSIDE](http://77.150.63.94/inside/includes/icons/common/inside_readme.png)

# INSIDE - Plateforme de partage créée par les membres pour les membres.

## Lien
Site accessible depuis le lien suivant : [INSIDE](http://77.150.63.94/inside/index.php?action=goConsulter)

## Fonctionnalités
- PORTAIL : liens vers les sections et news
- MOVIE HOUSE : base de données de films et organisation de soirées cinéma
- LES ENFANTS ! À TABLE ! : outil de détermination du repas du jour
- COOKING BOX : recettes des gâteaux de la semaine
- EXPENSE CENTER : outil de suivi des dépenses des membres
- COLLECTOR ROOM : collection de phrases cultes
- CALENDARS : calendriers de l'équipe
- LES PETITS PÉDESTRES : organisation d'entrainements ou de courses à pied
- MISSIONS : INSIDER : évènements du site
- JOURNAL DES MODIFICATIONS : description des nouveautés du site
- #THEBOX : boîte à idées
- DEMANDES D'ÉVOLUTION : soumission de bugs et améliorations
- NOTIFICATIONS : centre de notifications générales
- PROFIL : gestion paramètres et succès
- INSIDE ROOM : chat général

## Notes aux développeurs
Ne pas toucher aux fichiers suivants lors de vos développements :
- appel_bdd.php
- appel_mail.php

Si des différences sont constatées, veuillez les annuler.

## Les langages utilisés
Au travers de l'architecture MVC (Modèle-Vue-Contrôleur) utilisée, plusieurs langages sont appliqués afin de correspondre aux différents besoins du site.
### HTML
> Utilisé pour la **structure des pages**. Il est conseillé de recopier la structure d'une page lors des développements afin de partir d'une base propre et ensuite apporter des modifications.
### CSS
> Utilisé pour la **mise en forme** et les **animations** basiques. Chaque section du site possède sa propre feuille de style.
### PHP
> Utilisé pour les **interactions côté serveur**. Dans l'architecture MVC, le Contrôleur et le Modèle sont codés en PHP. Il est conseillé de recopier un Contrôleur lors des développements afin de partir d'une base propre et ensuite apporter des modifications.
### MySQL
> Utilisé pour toutes les **requêtes** aux différentes tables de la base de données. Ces requêtes sont généralement décrites dans le Modèle et encapsulées dans du code PHP.
### Javascript
> Utilisé pour les **interactions côté client**. Chaque section du site possède généralement sa propre feuille de scripts. Des animations plus poussées sont codées en Javascript et permettent de modifier visuellement ce qui s'affiche à l'écran de l'utilisateur.
### jQuery
> Utilisé pour les **interactions côté client**. Le jQuery est une bibliothèque Javascript permettant de gérer également des animations et autres modifications sur l'écran de l'utilisateur. Il repose sur le même fonctionnement que l'Ajax en simplifiant toutefois les instructions à taper.
### XML
> Utilisé pour la **structure de données**. Actuellement utilisé uniquement afin de stocker les conversations du Chat, le formatage des données entre balises permet une extraction simple de chaque propriété d'un noeud.

## Variables utiles
### Les couleurs
Les couleurs RGB sont principalement utilisées pour la transparence. Dans les autres cas, utiliser les codes hexadécimaux. Voici les couleurs principalement représentées sur la plateforme :

| Nom             | Couleur                                                   | Code HEX | Code RGB           | Notes                                  |
| ----------------| :-------------------------------------------------------: | -------- | ------------------ | -------------------------------------- |
| Jaune clair     | ![#fffd4c](https://place-hold.it/15/fffd4c/000000?text=+) | #fffd4c  |                    |                                        |
| Jaune moyen     | ![#ffad01](https://place-hold.it/15/ffad01/000000?text=+) | #ffad01  |                    |                                        |
| Jaune foncé     | ![#c48500](https://place-hold.it/15/c48500/000000?text=+) | #c48500  |                    |                                        |
| Rouge           | ![#ff1937](https://place-hold.it/15/ff1937/000000?text=+) | #ff1937  | rgb(255, 25, 55)   | Rouge CGI, couleur principale          |
| Rouge           | ![#c81932](https://place-hold.it/15/c81932/000000?text=+) | #c81932  | rgb(200, 25, 50)   | Pour contraste (rift)                  |
| Bleu clair      | ![#2eb2f4](https://place-hold.it/15/2eb2f4/000000?text=+) | #2eb2f4  |                    |                                        |
| Bleu clair      | ![#13a2e9](https://place-hold.it/15/13a2e9/000000?text=+) | #13a2e9  |                    |                                        |
| Vert clair      | ![#96e687](https://place-hold.it/15/96e687/000000?text=+) | #96e687  |                    | Label saisie mobile                    |
| Vert moyen      | ![#70d55d](https://place-hold.it/15/70d55d/000000?text=+) | #70d55d  |                    | Icône utilisateur connecté (chat)      |
| Blanc           | ![#ffffff](https://place-hold.it/15/ffffff/000000?text=+) | #ffffff  | rgb(255, 255, 255) |                                        |
| Gris clair      | ![#f3f3f3](https://place-hold.it/15/f3f3f3/000000?text=+) | #f3f3f3  |                    |                                        |
| Gris clair      | ![#e3e3e3](https://place-hold.it/15/e3e3e3/000000?text=+) | #e3e3e3  | rgb(227, 227, 227) |                                        |
| Gris clair      | ![#d3d3d3](https://place-hold.it/15/d3d3d3/000000?text=+) | #d3d3d3  |                    |                                        |
| Gris clair      | ![#c3c3c3](https://place-hold.it/15/c3c3c3/000000?text=+) | #c3c3c3  |                    |                                        |
| Gris clair      | ![#b3b3b3](https://place-hold.it/15/b3b3b3/000000?text=+) | #b3b3b3  |                    |                                        |
| Gris clair      | ![#a3a3a3](https://place-hold.it/15/a3a3a3/000000?text=+) | #a3a3a3  |                    |                                        |
| Gris foncé      | ![#7c7c7c](https://place-hold.it/15/7c7c7c/000000?text=+) | #7c7c7c  |                    | Pour ombres                            |
| Gris foncé      | ![#374650](https://place-hold.it/15/374650/000000?text=+) | #374650  |                    | Lien portail                           |
| Gris foncé      | ![#2c3840](https://place-hold.it/15/2c3840/000000?text=+) | #2c3840  |                    | Pour ombres & contraste (rift switchs) |
| Gris foncé      | ![#303030](https://place-hold.it/15/303030/000000?text=+) | #303030  |                    | Nav                                    |
| Gris foncé      | ![#262626](https://place-hold.it/15/262626/000000?text=+) | #262626  |                    | Header & footer                        |
| Gris foncé      | ![#1b1b1b](https://place-hold.it/15/1b1b1b/000000?text=+) | #1b1b1b  |                    | Boutons actifs                         |
| Noir            | ![#000000](https://place-hold.it/15/000000/000000?text=+) | #000000  | rgb(0, 0, 0)       |                                        |

### Les variables globales
Les variables globales ($_SESSION et $_COOKIE) sont généralement organisées sous forme de tableaux regroupant leur contenu en catégories. Ceci facilite la lecture des données pour les développeurs.

| SESSION   | Description                                              |
| --------- | -------------------------------------------------------- |
| alerts    | Contient les tops de déclenchement des messages d'alerte |
| chat      | Contient les données du chat (données utilisateurs)      |
| changelog | Contient les paramètres d'un journal de modifications    |
| generator | Contient les données du générateur de code               |
| index     | Contient les données connexion                           |
| missions  | Contient les données des missions générées               |
| search    | Contient les données de la recherche                     |
| success   | Contient les données des succès débloqués                |
| theme     | Contient les données des thèmes                          |
| user      | Contient les données utilisateurs et préférences         |

| COOKIE        | Description                                         |
| --------------|---------------------------------------------------- |
| ***Celsius*** |                                                     |
| positionX     | Position de Celsius sur l'axe des abscisses (en px) |
| positionY     | Position de Celsius sur l'axe des ordonnées (en px) |
| ***Chat***    |                                                     |
| identifiant   | Identifiant de l'utilisateur connecté               |
| showChat      | Etat de repli de la fenêtre de chat                 |
| windowChat    | Choix de la fenêtre de chat                         |
| ***Index***   |                                                     |
| identifiant   | Identifiant de l'utilisateur connecté               |
| password      | Mot de passse crypté                                |
