Louvres Tickets

New symfony project for learning purposes 

Getting Started/Pour commencer:

Copy the folder content on your drive. 
You'll find in the "DB" folder a copy of the testing database on .sql format. Import this file using your DB manager.
Don't forget a composer update if needed.

In config/packages/doctrine.yaml, in the parameters section, replace the env(DTATABASE_URL) value with your own database access values, following this pattern: "mysql://USERNAME:PASSWORD@IPADRESS:PORT/DB NAME" .

The database contains several tables required for the project to work. Fixtures are complete. You'll only need them if you don't use the test DB, otherwise the site logic won't work.

With this preparation, the website should work locally by typing "localhost" on your browser once your php server and DB manager are up and running. 

Caution: assets included are for private use (under the fair use laws governing educations purposes) and subject to copyright from their righful owners. 

/Copiez le contenu du dossier sur votre machine. Vous trouverez un dossier "DB" qui contient une copie de la base de donnée de test. Importez ce fichier en utlisant votre gestionnaire de DB. 
N'oubliez pas l'update de composer si besoin. 

Dans config/packages/doctrine.yaml, section "parameters", remplacez la valeur de la ligne env(DTATABASE_URL) par vos propres données, suivant le shéma "mysql://NOMUTILSATEURS:MOTDEPASSE@ADRESSEIP:PORT/NOM DE LA BASE".

La base de données contient plusieurs tables nécessaires au bon fonctionnement du site. Les fixtures sont complètes, vous n'en aurez besoin que si vous n'utilisez pas la base de test, car elles contiennent la logique métier. 

Avec ces préparations, le site devrait fonctionner une fois votre serveur php et gestionnaire de DB lancés. 

Attention: les assets (images) fournis le sont à titre d'exemples illustratifs au titre de l'usage privée accordé à des fins éducatives, et ne sont pas libres de droit. 

Testing:

Run PHP unit in the folder root (typing "./bin/phpunit tests/ --debug") to launch the test suite. 
The controller is tested in unit and functionnal aspects. 
The mailer is tested on a functionnal basis.
The form builder is tested in unit and functionnal aspects.
If you run a test with Php-stan, remeber to exclude the migration folder.

/Lancez PHP unit dans le dossier racine (en tapant "./bin/phpunit tests/ --debug") pour lancer la série de tests.
Le controlleur est testé sur des aspects unitaire et fonctionnels.
La classe gérant l'envoi de mail est testé fonctionnellement.
Le constructeur de formulaire est testé sur des aspects unitaire et fonctionnels. 
Si vous lancez des tests avec Php stan, n'oubliez pas d'exclure les dossiers comme les migrations. 


