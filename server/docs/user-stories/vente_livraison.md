# 🧾 USER STORIES & BDD – Vente & Livraison (avec gestion stock)

---

### 🧩 US-VEN-01 – Création d’une commande client

> En tant que **agent commercial**,
> Je veux **enregistrer une commande client** dans un dépôt,
> Afin de **lancer le processus de livraison et de paiement**.

```gherkin
Feature: Création de commande client

  Scenario: Commande simple d’un produit
    Given un client nommé "Société Azur" existe
    And le produit "Gasoil" est disponible dans le dépôt "Fomboni"
    When j’enregistre une commande de 2 000 litres de "Gasoil" pour "Société Azur"
    Then une commande est créée
    And son état de paiement est "NON_PAYÉ"
    And son état de livraison est "NON_LIVRÉ"

  Scenario: Commande de plusieurs produits
    Given un client nommé "Station Moheli Nord" existe
    And le produit "Gasoil" est disponible dans le dépôt "Fomboni"
    And le produit "Essence SP" est aussi disponible dans le même dépôt
    When "Station Moheli Nord" commande 1 500 litres de "Gasoil"
    And 1 000 litres de "Essence SP"
    Then une commande est créée contenant 2 lignes de produit
    And son état de paiement est "NON_PAYÉ"
    And son état de livraison est "NON_LIVRÉ"

```

---

## 🧩 US-VEN-02 – Paiement d’une commande (optionnel pour crédit)

> En tant qu’**agent comptable**,
> Je veux **enregistrer un paiement (total ou partiel) pour une commande**,
> Afin de **mettre à jour son état financier et l’autorisation éventuelle de livraison**.

```gherkin
Feature: Paiement d’une commande

  Scenario: Paiement total d’une commande
    Given une commande de 2 000 litres pour "Client X" en état "NON_PAYÉ"
    When un paiement de la totalité du montant est enregistré
    Then l’état de paiement passe à "PAYÉ"
    And la commande est automatiquement autorisée à la livraison

  Scenario: Paiement partiel d’une commande
    Given une commande de 2 000 litres pour "Client Y" en état "NON_PAYÉ"
    When un paiement de 1 000 litres est enregistré
    Then l’état de paiement passe à "PARTIELLEMENT_PAYÉ"
    And la commande n’est pas autorisée à la livraison par défaut
```

---

## 🧩 US-VEN-03 – Autorisation manuelle de livraison

> En tant que **manager ou responsable**,
> Je veux **autoriser manuellement la livraison d’une commande partiellement ou non payée**,
> Afin de **permettre la livraison malgré un paiement incomplet**.

```gherkin
Feature: Autorisation manuelle de livraison

  Scenario: Autorisation manuelle après paiement partiel
    Given une commande est en état de paiement "PARTIELLEMENT_PAYÉ"
    And la livraison n’est pas autorisée
    When un manager autorise la livraison
    Then la commande est autorisée à la livraison

  Scenario: Autorisation manuelle sur commande non payée
    Given une commande est en état "NON_PAYÉ"
    When un manager autorise la livraison
    Then la commande est autorisée à la livraison
```

---

## 🧩 US-VEN-04 – Révocation de l’autorisation de livraison

> En tant que **manager ou responsable**,
> Je veux **révoquer l’autorisation de livraison sur une commande**,
> Afin de **bloquer la livraison en cas de problème**.

```gherkin
Feature: Révocation de l’autorisation de livraison

  Scenario: Révocation d’une livraison autorisée
    Given une commande est autorisée à la livraison
    When un manager révoque l’autorisation
    Then la commande n’est plus autorisée à la livraison
```

---

## 🧩 US-VEN-05 – Vérification de l’autorisation de livraison

> En tant qu’**agent logistique**
> Je veux **vérifier si une commande est autorisée à être livrée**
> Afin de **savoir si je peux entamer la préparation de la livraison**

```gherkin
Feature: Vérification de l'autorisation de livraison

  Scenario: Commande autorisée à être livrée
    Given une commande est authorisée à être livrée
    When je consulte son statut
    Then je vois que la livraison peut être préparée

  Scenario: Commande non autorisée à être livrée
    Given une commande n’est pas autorisée à être livrée
    When je consulte son statut
    Then je vois que la livraison ne peut pas être préparée
```

---

## 🧩 US-VEN-06 – Préparation logistique d’une commande

> En tant qu’**agent logistique**
> Je veux **préparer la livraison d’une commande client**
> Afin de **lui affecter les ressources nécessaires au transport**

```gherkin
Feature: Préparation logistique de la commande

  Scenario: Préparation d’une commande autorisée
    Given une commande autorisée à être livrée existe
    And elle est en état de livraison "NOT_DELIVERED"
    When Un agent "user-id" prépare une livraison
    And Affecte le chauffeur "Ali" et le véhicule "Camion C17"
    And Une date prévue de livraison au "12 juillet 2025"
    Then la commande passe à l’état de livraison "PREPARATION_EN_COURS"
    And les informations de préparation sont enregistrées dans la livraison

  Scenario: Préparation d’une nouvelle livraison pour une commande partiellement livrée
    Given une commande a déjà une livraison effectuée partiellement
    And une quantité restante est encore à livrer
    And la commande est toujours autorisée à être livrée
    When un agent "user-id" prépare une **deuxième livraison**
    And affecte le chauffeur "Bakari" et le véhicule "Camion C24"
    And une date prévue de livraison au "15 juillet 2025"
    Then une **nouvelle livraison** est ajoutée à la commande
    And la commande reste en état "PARTIALLY_DELIVERED"

  Scenario: Tentative de préparation sur une commande non autorisée
    Given une commande est en état "PARTIALLY_PAYED"
    And elle n’est pas autorisée à être livrée
    When j’essaie de préparer sa livraison
    Then une erreur m’indique que la livraison n’est pas autorisée
    And la commande reste à l’état "NOT_DELIVERED"
```

---

## 🧩 US-DEL-08 – Saisie du relevé compteur de livraison

> En tant que **agent de livraison**,
> Je veux **enregistrer manuellement le relevé du compteur de sortie**,
> Afin de **mesurer la quantité effectivement livrée pour une commande**.

```gherkin
Feature: Saisie du relevé compteur

  Scenario: Enregistrement d’un relevé via compteur
    Given une commande est en cours de livraison
    And le compteur affiche "12 000 litres" en début
    When l’agent saisit une valeur de fin à "10 000 litres"
    Then le système enregistre un relevé compteur de "2 000 litres"
    And la quantité mesurée est liée à la livraison
    And le stock n’est pas encore décrémenté

  Scenario: Saisie manuelle du volume livré
    Given une commande est en cours de livraison
    And la citerne utilisée n’est pas reliée au compteur général
    When l’agent saisit manuellement une quantité de "1 500 litres"
    Then le système enregistre un relevé manuel de "1 500 litres"
    And la quantité est liée à la livraison
    And le stock n’est pas encore décrémenté

  Scenario: Plusieurs relevés pour une seule livraison
    Given une commande est en cours de livraison
    And le premier relevé par compteur indique un début à "12 000 litres" et une fin à "10 000 litres"
    And le second relevé manuel indique une saisie de "1 000 litres"
    When les deux relevés sont enregistrés
    Then le système calcule un volume total livré de "3 000 litres"
    And chaque relevé est associé à une citerne distincte
    And le stock n’est pas encore décrémenté

```

---

## 🧩 US-DEL-09 – Validation manuelle de la livraison

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

## 🧩 US-STOCK-10 – Déduction manuelle du stock par citerne

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

## 🧩 US-DEL-11 – Correction ou annulation du relevé

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

## 🧩 US-STOCK-12 – Annulation de la livraison

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
