![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# Symfony Stocks Manager
[![Version](https://img.shields.io/badge/Version-1.0.0-blue)]()

## Description

L'application présente un système simple de commandes de produits et gestion des stocks.

### Schema de la base
![Schéma de la base](./docs/db-schema.png)
### Workflow de gestion des commandes
```mermaid
    graph LR
        A["Nouvelle"]-->B["En cours de préparation"]
        B["En cours de préparation"]-->C["Prête"];
        C["Prête"]-->D["Envoyée"];
        
```

## Missions

L'objectif est de gérer/automatiser avec des évènements les tâches suivantes :
 - Automatiser la gestion des stocks
 - Construire des logs de suivi de commandes
 - Construire des logs des authentification permettant d'afficher sur le dashboard admin les tentatives de logs echouées ou suspectes

## Ressources

