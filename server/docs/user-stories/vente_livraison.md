# 🧾 USER STORIES & BDD – Vente & Livraison (avec gestion stock)

---

## 🧩 US-VEN-01 – Création d’une commande client (réservation stock)

> En tant qu’**agent commercial**,
> Je veux **enregistrer une commande de produits pétroliers pour un client**,
> Afin de **réserver le stock nécessaire et préparer la livraison**.

```gherkin
Feature: Création de commande client

  Scenario: Un agent commercial crée une commande
    Given le dépôt "deposit-id" a 10 000 litres de "product-id" en stock disponible
    And un client nommé "customer-id" existe
    When un agent commercial nommé "user-id" enregistre une commande de 2 000 litres de "product-id" pour "customer-id"
    Then une commande avec un identifiant "customer-order-id" est créée pour "customer-id"
    And elle est en état "EN_ATTENTE_PAIEMENT"
    And 2 000 litres de "product-id" sont réservés et bloqués dans le stock
```

---

## 🧩 US-VEN-02 – Paiement d’une commande (optionnel pour crédit)

> En tant qu’**agent comptable**,
> Je veux **enregistrer un paiement pour une commande**,
> Afin de **valider la transaction et débloquer la suite du processus**.

```gherkin
Feature: Paiement d’une commande

  Scenario: Paiement total d’une commande
    Given une commande de 2 000 litres de "product-id" existe pour "customer-id" en état "EN_ATTENTE_PAIEMENT"
    When un paiement de 2 000 000 KMF est enregistré pour cette commande
    Then la commande passe à l’état "PRÊTE_LIVRAISON"

  Scenario: Commande en crédit sans paiement immédiat
    Given une commande de 2 000 litres de "Gasoil" existe pour "Client Créditeur" en état "EN_ATTENTE_PAIEMENT"
    When aucun paiement n’est encore enregistré
    Then la commande peut rester en état "EN_ATTENTE_PAIEMENT"
    And la livraison peut être autorisée en mode crédit
```

---

## 🧩 US-VEN-03 – Génération du bon de livraison

> En tant que **responsable livraison**,
> Je veux **générer un bon de livraison en assignant chauffeur, véhicule et dépôt**,
> Afin de **préparer la livraison physique**.

```gherkin
Feature: Préparation de la livraison

  Scenario: Création d’un bon de livraison
    Given une commande "PRÊTE_LIVRAISON" ou "EN_ATTENTE_PAIEMENT" (en crédit) existe
    And un véhicule "NGZ-1234" est disponible
    And un chauffeur "Ali Madi" est assigné
    When je génère un bon de livraison
    Then la commande passe à l’état "EN_COURS_LIVRAISON"
    And un bon de livraison est lié à cette commande
```
---

## 🧩 US-DEL-04 – Saisie du relevé compteur de livraison

> En tant que **agent de livraison**,
> Je veux **enregistrer manuellement le relevé du compteur de sortie**,
> Afin de **mesurer la quantité effectivement livrée pour une commande**.

```gherkin
Feature: Saisie du relevé compteur

  Scenario: Enregistrement d’un relevé simple
    Given une commande est en cours de livraison
    And le compteur affiche 10 000 litres en début
    When l’agent saisit une valeur de fin à 12 000 litres
    Then le système enregistre un relevé compteur de 2 000 litres
    And la quantité mesurée est liée à la commande
    And le stock n’est pas encore décrémenté
```

---

## 🧩 US-DEL-05 – Validation manuelle de la livraison

> En tant que **agent de livraison**,
> Je veux **valider qu’une livraison s’est bien déroulée**,
> Afin de **déclencher la déduction du stock et clôturer la livraison**.

```gherkin
Feature: Validation de la livraison

  Scenario: Livraison validée après relevé compteur
    Given un relevé compteur indique 2 000 litres livrés pour une commande
    When l’agent confirme que la livraison est bien réalisée
    Then la commande passe à l’état "LIVRÉE"
    And le stock est déduit selon les citernes affectées
    And la réservation de stock est libérée
```

---

## 🧩 US-STOCK-06 – Déduction manuelle du stock par citerne

> En tant qu’**agent de livraison**,
> Je veux **répartir manuellement la quantité livrée sur les citernes du dépôt**,
> Afin de **maintenir la traçabilité du stock par citerne**.

```gherkin
Feature: Déduction manuelle du stock par citerne

  Scenario: Toute la livraison vient d’une seule citerne
    Given une livraison validée indique 2 000 litres de Gasoil livrés
    And la citerne "A" contient 5 000 litres de Gasoil
    When l’agent attribue 2 000 litres à la citerne "A"
    Then la citerne "A" est décrémentée de 2 000 litres
    And une ligne de retrait est enregistrée : "Citerne A → -2 000 L"

  Scenario: Livraison répartie sur plusieurs citernes
    Given la livraison fait 3 000 litres
    And la citerne A contient 1 000 L et la citerne B contient 5 000 L
    When l’agent répartit 1 000 litres à A et 2 000 à B
    Then A est décrémentée de 1 000 L, B de 2 000 L
    And deux lignes de retrait sont enregistrées
```

---

## 🧩 US-DEL-07 – Correction ou annulation du relevé

> En tant qu’**agent de livraison**,
> Je veux **pouvoir corriger un relevé compteur en cas d’erreur**,
> Afin de **garantir l’exactitude de la quantité livrée**.

```gherkin
Feature: Correction du relevé compteur

  Scenario: Correction avant validation de la livraison
    Given un relevé compteur a été saisi avec une erreur
    When l’agent modifie les valeurs de début ou de fin
    Then le système recalcule la quantité livrée
    And la commande reste en état "EN_COURS_LIVRAISON"
```

---

## 🧩 US-STOCK-08 – Annulation de la livraison

> En tant que **agent de livraison**,
> Je veux **annuler une livraison si elle a échoué**,
> Afin de **libérer la réservation sans toucher au stock**.

```gherkin
Feature: Annulation d’une livraison

  Scenario: Livraison échouée avant validation
    Given une commande est en cours de livraison avec un relevé saisi
    When la livraison est annulée par l’agent
    Then la commande passe à l’état "ANNULÉE"
    And le stock réservé est libéré
    And aucune déduction n’est faite dans les citernes
```

---

## 📌 Résumé des états & actions

| État de la commande  | Action                | Impact                                         |
| -------------------- | --------------------- | ---------------------------------------------- |
| `EN_COURS_LIVRAISON` | Relevé compteur saisi | Aucun impact sur stock                         |
| `EN_COURS_LIVRAISON` | Livraison validée     | Stock décrémenté par citerne + réserve libérée |
| `EN_COURS_LIVRAISON` | Livraison annulée     | Réserve libérée, pas de décrément              |
| `LIVRÉE`             | → Clôture (US-VEN-06) | Archivage, reçu, aucun impact stock            |

---
