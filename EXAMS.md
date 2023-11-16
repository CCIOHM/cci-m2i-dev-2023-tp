# TP INDIVIDUEL Noté: Migration d'une Application d'Upload de Fichiers vers AWS S3 et CloudFront

## Durée
4h00

## Objectif
Transformer une application web existante qui upload et affiche des fichiers localement en une qui utilise AWS S3 pour le stockage et CloudFront pour l'affichage.

## Contexte
Vous avez une application web simple en PHP (8.1) ou Node.js (20.9). Cette application permet aux utilisateurs d'uploader des fichiers dans un dossier "storage" local et affiche ces fichiers sur la même page. Votre mission est de migrer le stockage de ces fichiers vers AWS S3 et d'utiliser CloudFront pour afficher les images.

## Applications:
- Docker compose non obligatoire.
- Info -> Rappel :
   - PHP 8.1     -> composer update
   - NodeJS 20.9 -> npm install

## Tâches à Réaliser

### 1. Préparation AWS
- Créez un compte AWS si nécessaire.
- Créez un bucket S3 privé pour stocker les fichiers uploadés. Il devra inclure une option de chiffrement et de versionning de fichiers.
- Configurez un utilisateur IAM avec les permissions nécessaires pour accéder au bucket S3 depuis l'application. Voici quelques contraintes et informations sur l'utilisateur :
   - Pas d'accès à AWS Management Console
   - Permissions: "Attach policies directly"
     - Créer une nouvelle Policy (Bouton disponible en créant l'utilisateur):
       - Option 1 (par défaut): Visual
         - Service: S3
         - Actions: UNIQUEMENT les actions nécessaires à l'application. (Peut-être modifié après, voir Recommandations plus bas).
         - Resources: Specific -> object -> remplir "Resource bucket name" avec le nom (pas ARN) de votre bucket + "Any Object name" (Peut-être modifié après, voir Recommandations plus bas).
       - Option 2: JSON
         - Via les règles JSON en s'aidant des guides des exo du cours.
     - Après création de la Policy, rafraichir la page de création de l'utilisateur ou recommencer (la policy étant déjà créée), cherchez la policy dans votre liste et affectez la à l'utilisateur.
   - Après création de l'utilisateur, il vous faudra une "Access Key" pour l'application (voir "Security credentials" de l'utilisateur) :
     - Création "Access key": "Application running outside AWS"
     - Récupérez l'Access Key et la Secret access key (1 seule chance de l'avoir) juste à la fin de la création de l'"Access key".
     - Si vous n'avais pas les 2 clés, il faudra créer une nouvelle "Access Key"
- Créez une distribution CloudFront liée à votre bucket S3. Voici quelques contraintes et informations :
  - Enable Origin Shield: No
  - HTTPS
  - Method HTTP: GET, HEAD, OPTIONS
  - WAF: Not not enable
  - Disponibilité: Europe / USA
  - HTTP/2 et HTTP/3 

### 2. Modification de l'Application
- Modifiez l'application pour uploader les fichiers vers le bucket S3 au lieu du stockage local.
- Implémentez la logique pour lister les fichiers stockés dans S3 sur la page web.
- Modifiez l'affichage des fichiers sur la page web pour utiliser les URL CloudFront.

### 3. Fonctionnalités Requises
- Upload sécurisé vers S3.
- Listing des fichiers S3 sur la page web.
- Utilisation de CloudFront pour l'affichage des images.
- Gestion des erreurs et validation des fichiers uploadés.

### 4. Tests et Validation
- Vérifiez que les fichiers du S3 ne sont pas accessibles en public.
- Assurez-vous que les fichiers sont correctement uploadés et listés.
- Vérifiez que les images sont accessibles et affichées via CloudFront.

### 5. Cloudfront avec signature des URLs
- Tutoriel : <a href="https://codestax.medium.com/securing-s3-content-with-cloudfront-signed-url-a96c984246e">Medium tuto</a>
- Ne pas suivre au pied de la lettre (Réfléchir, comprendre et apprendre).
- Version PHP (voir fichier exo-5.php)
- AWS_CLOUDFRONT_URL == AWS_CLOUDFRONT_URL_TO_ACCESS_S3_OBJECT (Tuto)

## Contraintes d'environnement obligatoire !
- Vous devez obligatoirement utiliser le système de variables d'environnement (fichier .env) mis en place dans l'application pour :
  - S3 Access Key "AWS_S3_ACCESS_KEY_ID"
  - S3 Secret access Key "AWS_S3_SECRET_ACCESS_KEY"
  - S3 région "AWS_S3_REGION"
  - S3 nom du bucket "AWS_S3_BUCKET"
  - Cloudfront url "AWS_CLOUDFRONT_URL" (Exemple: https://kjfdozk.cloudfront.net/)
  - Cloudfront private key "AWS_CLOUDFRONT_PUBLIC_KEY_ID" (Exo 5)
  - Cloudfront private key "AWS_CLOUDFRONT_PRIVATE_KEY_FILE_NAME" (Exo 5)

## Contraintes particulières
- L'usage d'internet est autorisé !
- ChatGPT, toutes IA/AI ou assistant intelligent ne sont pas autorisé. Leur usage entrainera un zéro et une remarque négative dans votre dossier.
- Aucun framework, plugin ou module tout fait n'est autorisé.

## Recommandations
- L'usage du SDK officiel de AWS est recommandé. Chaque langage a son propre SDK officiel et les commandes sont très similaires.
- Vous êtes libre d'utiliser guide des exercices pour vous inspirer.
- Pour la création des Policies de l'utilisateur, il est recommandé de d'abord donner un accès à toutes les actions et resources du S3 puis après avoir modifier et l'application avec S3, définir les actions et resources strictes et définitives, sinon vous risquez de vous ralentir/bloquer durant l'exercice.

## Livrables
- Code source de l'application modifiée. Le code source ne doit pas inclure les dépendances (vendor ou node_modules).
- Documentation avec captures d'écrans (nom de services, policies de sécurité/accès et configurations) expliquant la configuration AWS mise en place. Les captures d'écran devront montrer l'intégralité de votre écran !

## Critères d'Évaluation
- Fonctionnement correct de l'upload vers S3 et de l'affichage via CloudFront.
- Accès aux fichiers (lecture/écriture) avec des policies.
- Signature des urls avec Cloudfront.
- Complétude et précision de la documentation.
