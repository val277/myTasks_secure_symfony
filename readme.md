# myTasks Secure Symfony

Application de gestion de tâches sécurisée développée avec Symfony.

## Étapes principales du projet Symfony

### 1. Installation & Configuration

- Créer un projet avec `symfony new myTasks_secure_symfony --webapp` (ou sans `--webapp` si pas d'affichage nécessaire)
- Configurer la base de données dans `.env`
- Créer et migrer la BDD avec Doctrine :
  - `doctrine:database:create`
  - `make:migration`
  - `migrate`

### 2. Création des Entités

- **User** (authentification) et **Task** (tâches)
- Définir les relations (`OneToMany`, `ManyToOne`)
- Ajouter les contraintes de validation (`Assert\NotBlank`, `Assert\Length`, etc.) dans les entités **sauf pour les password** où on va directement les mettre dans les `formType` car on veut les contrôler avant le hachage et donc avant de les assigner
- `mapped => false` à préciser dans le tableau associatif du `buildForm` si un champ n'est pas relié directement à une propriété et qu'on doit gérer sa logique d'abord via le controller (utile encore une fois pour les passwords)

### 3. Authentification

- Formulaire d'inscription (`make:registration-form`)
- Formulaire de connexion (`make:auth`)
- Configuration de la sécurité dans `security.yaml`
  - Gestion du hashage et des accès aux pages selon roles ou authentification (`access_control`)

### 4. CRUD

- Génération automatique avec `make:crud`
- Modification des contrôleurs pour lier les tâches à l'utilisateur connecté à la place du `findAll` de base qui ferait qu'un utilisateur voit les tâches de tout le monde
- Ajout des formulaires personnalisés (`UserInfosType`, `UserPasswordType`) nécessaires pour modifier les infos ou le mot de passe d'un profil

### 5. Contrôleurs & Routage

- Chaque méthode d'un contrôleur correspond à une route (ex. `/task/new`)
- Sécurisation des actions (`$task->getOwner() !== $this->getUser()`) afin d'éviter qu'un user puisse voir ou modifier des tâches qui ne lui appartiennent pas
- Redirections intelligentes (login → tasks si connecté)

### 6. Templates (vues Twig)

- `base.html.twig` : layout principal avec barre de navigation
- `task/`, `user/`, `security/`, `registration/` : vues spécifiques
- Syntaxe Twig : `{{ variable }}`, `{% block %}`, `{% if %}`, `{% for %}`

### 7. Style avec Tailwind CSS

- Installation via `symfonycasts/tailwind-bundle`
- Initialisation (`tailwind:init`) et compilation (`tailwind:build --watch`)
- Application de classes Tailwind dans les templates

---

## Structure du Projet - Modifications Importantes

```
myTasks_secure_symfony/
│
├── config/
│   ├── packages/
│   │   ├── security.yaml                    # Configuration authentification & accès
│   │   ├── doctrine.yaml                    # Configuration BDD
│   │   └── symfonycasts_tailwind.yaml       # Configuration Tailwind CSS
│   └── routes/
│       └── security.yaml                    # Routes de sécurité
│
├── src/
│   ├── Controller/
│   │   ├── TaskController.php               # CRUD tâches + vérifications propriétaire
│   │   ├── UserController.php               # CRUD utilisateurs + édition profil
│   │   ├── SecurityController.php           # Connexion/Déconnexion
│   │   ├── RegistrationController.php       # Inscription utilisateur
│   │   └── HomeController.php               # Page d'accueil
│   │
│   ├── Entity/
│   │   ├── User.php                         # Entité User avec UserInterface
│   │   └── Task.php                         # Entité Task avec relation ManyToOne
│   │
│   ├── Form/
│   │   ├── TaskType.php                     # Formulaire création/édition tâche
│   │   ├── UserType.php                     # Formulaire utilisateur complet
│   │   ├── UserInfosType.php                # Formulaire édition infos profil
│   │   ├── UserPasswordType.php             # Formulaire changement mot de passe
│   │   └── RegistrationFormType.php         # Formulaire inscription
│   │
│   └── Repository/
│       ├── TaskRepository.php               # Requêtes personnalisées tâches
│       └── UserRepository.php               # Requêtes personnalisées utilisateurs
│
└── templates/
    ├── base.html.twig                       # Template principal + navbar
    ├── home/
    │   └── index.html.twig                  # Page d'accueil
    ├── task/
    │   ├── index.html.twig                  # Liste des tâches utilisateur
    │   ├── new.html.twig                    # Création tâche
    │   ├── edit.html.twig                   # Édition tâche
    │   ├── show.html.twig                   # Détails tâche
    │   ├── _form.html.twig                  # Partial formulaire tâche
    │   └── _delete_form.html.twig           # Partial suppression tâche
    ├── user/
    │   ├── index.html.twig                  # Liste utilisateurs
    │   ├── edit_infos.html.twig             # Édition infos profil
    │   ├── edit_password.html.twig          # Changement mot de passe
    │   └── show.html.twig                   # Détails utilisateur
    ├── security/
    │   └── login.html.twig                  # Page de connexion avec style Tailwind
    └── registration/
        └── register.html.twig               # Page d'inscription avec style Tailwind
```
