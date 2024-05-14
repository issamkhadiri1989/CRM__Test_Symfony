# Installation du projet en local

## Démarrage des containers Docker

Lancer la commande

```
docker compose up -d --build
```

Une fois la commande est bien exécutée, lancer la commande suivante pour s'assurer que les containers sont UP

```
docker compose ps
```

Pour accéder à un container, il faut lancer la commande suivante

```
docker compose exec <the service name> bash
```

Par exemple, pour exécuter les commandes dans `server`, lancer `docker compose exec server bash`

Pour quitter le container, il faut lancer la commande `exit`

Pour arrêter les containers lancer

```
docker compose stop
```

Pour redémarrer les containers (sans les reconstruire) lancer

```
docker compose up -d 
```

## Installer le projet

```
// s'assurer que le container est démarré
docker compose up -d
```

```
// entrer dans le container 
docker compose exec server bash
```

lancer ensuite les 2 commandes suivantes :

```
composer self-update
composer install
```

## Configurer le projet

Dans `server` lancer la commande

```
composer dump-env dev
```

Le fichier `.env.local.php` est créé. mettre la ligne de connexion suivante dans la variable d'env DATABASE_URL

```
'DATABASE_URL' => 'mysql://root:superSecr3t@database/database?serverVersion=8.0.32&charset=utf8mb4',
```

## Créer les tables

Lancer la commande suivante pour créer les tables concernées

```
php bin/console doctrine:migrations:migrate
```

Répondre `yes` pour continuer

## Charger les données

Charger les données de tests avec la commande

```
php bin/console doctrine:fixtures:load
```

Répondre `yes` pour continuer .

Cette commande ajoute 2 administrateurs par défaut `admin@crm.com / 1234` et `admin-02@crm.com / 1234`

# Demo

## Page de connexion

![](doc/img001.png)

![](doc/img002.png)

## Liste  des sociétés

![](doc/img003.png)

### Ajouter une nouvelle société

![](doc/img004.png)

### Modifier une société

![](doc/img006.png)

### Supprimer la société seulement si aucun employé n'est affecté à cette société

![](doc/img005.png)

![](doc/img007.png)

## Invitations

### Envoyer une invitation à un employé

![](doc/img008.png)

![](doc/img009.png)

![](doc/img010.png)

Le mail n'est pas effectivement envoyé ici: nous passons par l'async.

A ce stade, il faut lancer la commande `php bin/console messenger:consyme async` afin de consommer les files d'attentes
et envoyer effectivement le mail.

La boite de réception est disponible sur `http://localhost:1080`

![](doc/img011.png)

![](doc/img012.png)

Dans un autre navigateur, copier/coller le lien pour commencer le processus de confirmation.

![](doc/img013.png)

Saisir un mot de passe. Pour plus de sécurité il faut que le mot de passe soit :

- en moins 8 caractères
- contient en moins 1 MAJ
- contient en moins 1 MIN

![](doc/img014.png)

Une fois le mot de passe est défini, l'utilisateur peut désormais completer sont profil.

Le lien "See my network" n'est pas valable tant que l'employé n'a pas confirmé son profile.

![](doc/img015.png)

Maintenant, il faut compléter le profile pour confirmer le compte.

**Invitation validée**

![](doc/img017.png)

L'invitation n'est plus possible d'être retirée / annulée.

**Compte confirmé**

![](doc/img016.png)

![](doc/img018.png)

Le menu "See my network" est désormais opérationnel.

![](doc/img019.png)

- Employees in the same company: 1 = Actuellement il existe seulement un seul employé dans la société.
- La liste plus bas, affiche les autres employés (l'employé connecté n'est pas inclus)

> _NOTE_ : Maintenant essayant d'inviter un autre Employé à la même société.
> Ici le but est d'accéder aux autres employés de la même société.

![](doc/img020.png)

L'utilisateur connecté ici peut accéder aux données de "Issam KHADIRI" car ils sont dans la même société.

![](doc/img021.png)

Actuellement nous avons 3 employés :

![](doc/img022.png)

Dans en terme de permissions: Issam KHADIRI et Essa Rodgers peuvent voir les détails mutuellement mais pas celui de
Vincenzo car il n'appartient pas à la même société.

Ici Vincenzo essaie d'accéder aux info de Issam KHADIRI.

![](doc/img023.png)

(voir `src/Security/Voter/ViewOtherProfileVoter.php`)

# Sécurité 

## Supprimer une invitation par une personne eligible 

![](doc/img024.png)

La suppression d'une invitation n'est possible que si :
- L'utilisateur connecté est son auteur
- Que si l'invitation n'est pas confirmée
- Si l'utilisateur connecté est un ADMIN

(voir `src/Security/Voter/CancelInvitationVoter.php`)

## Voir les détails d'une société que l'employé connecté n'y appartient pas 

![](doc/img025.png)

Ici Vincenzo essaie de voir les détails de la société COMPANY 2 SARL

(voir `src/Security/Voter/CanViewCompanyVoter.php`)

## Supprimer une société 

Le but est de supprimer une société uniquement si :
- L'utilisateur connecté est ADMIN
- Il est son propriétaire
- Aucun employé n'est affecté à cette société

![](doc/img026.png)

On voit déjà que le menu n'existe plus. Et si l'utilisateur essaie de le supprimer avec l'URL 

![](doc/img027.png)

Si un Employé essaie de la supprimer 

![](doc/img028.png)

Si  `admin-02@crm.com` essaie de supprimer la société 2

![](doc/img029.png)

(voir `src/Security/Voter/DeleteCompanyVoter.php`)

# Ajouter un nouveau Administrateur

![](doc/img030.png)

(Remarque: les screenshots avant celle-ci ne sont pas à jour avec quelques ajustements)

Si un utilisateur normal (non Admin) essaie d'accéder à cette page

![](doc/img031.png)

# Historique

Pour chaque opération, un historique est tracé dans /timeline

![img.png](img.png)
