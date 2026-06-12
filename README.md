# Test Technique – Développeur Logiciel Full Stack (Symfony & Symfony UX)
# Guilhem GOULABERT

## Contexte du test

Afin d’évaluer votre maîtrise des technologies et de votre approche de développement logiciel, vous êtes invité à
réaliser un mini-projet complet basé sur un **système d'inscription à un site web et du login après validation**.

## Déroulé du développement

Suite à des difficultés (et impossibilité) d'utiliser Docker, je n'ai commencé le développement sur une autre installation que tardivement. De plus, j'ai subis un imprévu vétérinaire la journée du 12/06, me limitant dans le temps.
Malgré l'absence de quelques parties, mon application tourne, avec les fonctionnalités demandées

---

## Application

Afin de lancer convenablement l'application, voici quelques limitations :
- pas de Docker : impossibilité de lancer Docker avec mailer database et php, l'image et le container php ressortant unhealthy malgré le suivi des docs et README (utilisation de symfony-docker de Dunglas : https://github.com/dunglas/symfony-docker)
- pas de tests ni css : vu ma limite de temps suite à mes imprévus, j'ai préféré me focaliser sur le développement de la logique plutôt que du rendu

## Lancer l'application

Pour la bonne utilisation de l'application, il suffit de connecter une base de données locale à l'applicatio via le .env
J'ai personnellement utilisé MySQL, d'où la ligne non-commentée.
Pour le Mailer, je suis passé par Mailjet. Le SMTP est connectée à l'application, via des clés crées spécifiquement pour cette application, et avec mon adresse email comme adresse d'envoi.

Une fois la base de données connectée, il suffit de lancer l'executable **start.sh** via un invité de commande pour que l'application se lance.
Ce script va créer la base de données, créer les migrations pour les tables et entités, et les exporter (seules les validations sont requises) puis lance le serveur embarqué de Symfony.

### Valider un utilisateur

Compte de la consigne, une commande a été créée pour valider les utilisateurs et leur envoyer un email avec un lien.
Cette commande est **app\:fighters\:validate** et validera tous les utilisateurs qui ne l'ont pas encore été.
