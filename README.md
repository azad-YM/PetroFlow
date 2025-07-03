# 🛢️ PetroFlow

**PetroFlow** est un projet de gestion des opérations liées aux produits pétroliers, développé en **PHP** avec **Symfony**, suivant une approche **Domain-Driven Design (DDD)**, **Clean Architecture**, et **Test-Driven Development (TDD)**.

> Ce projet est né d’un besoin réel : améliorer la gestion logistique, les ventes et le suivi des hydrocarbures dans un contexte professionnel exigeant.

---

## 🧱 Architecture

PetroFlow est découpé en plusieurs **Bounded Contexts** clairement définis :

- **Stock & Logistique** : suivi des mouvements de stock, réservations, alertes.
- **Vente & Livraison** : gestion des commandes clients, des livraisons, et des factures.
- **Achat** : suivi des approvisionnements et des fournisseurs.
- **Infrastructure** : entités de base comme les produits, dépôts, véhicules, citernes...
- **Reporting** : lecture optimisée (CQRS) pour les dashboards et exports.

Chaque context suit une organisation stricte :

```

src/
├── Domain/
├── Application/
├── Infrastructure/

```

---

## 🧪 Tests

Le projet est développé en **TDD**.  
Chaque **Use Case** commence par un scénario, suivi de tests unitaires sur le domaine.

Exemple :

```gherkin
Feature: Création de commande client
  Scenario: Un agent commercial crée une commande
    Given le dépôt a 10 000 litres de "Gasoil" en stock disponible
    And un client nommé "Youssouf" existe
    When un agent commercial nommé "Mohamed" enregistre une commande de 2 000 litres de "Gazoil" pour "Youssouf"
    Then une commande est créée pour "Youssouf"
    And elle est en état "EN_ATTENTE_PAIEMENT"
    And 2 000 litres de "Gasoil" sont réservés et bloqués dans le stock
```

---

## 🔧 Stack technique

- **Langage** : PHP 8.3
- **Framework** : Symfony (minimal, utilisé comme outil, pas comme structure)
- **Tests** : PHPUnit
- **Architecture** : Clean Architecture
- **Principes** : BDD, DDD, TDD, CQRS, Hexagonal Design

---

## 📜 Licence

Projet personnel sous licence [MIT](LICENSE).  
Développé avec ❤️ par [Abdoul-Wahid Hassani](https://github.com/azad-YM).

---

