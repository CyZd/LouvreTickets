Louvres Tickets

New symfony project for learning purposes 

Getting Started/Pour commencer:

Copy the folder content on your drive. 
You'll find in the "DB" folder a copy of the testing database on .sql format. Import this file using your DB manager.

In config/packages/doctrine.yaml, in the parameters section, replace the env(DTATABASE_URL) value with your own database access values, following this pattern: "mysql://USERNAME:PASSWORD@IPADRESS:PORT/DB NAME" .

The database contains several tables required for the project to work. Fixtures are not fully complete, don't use them, it would break the work-logic. 

With this preparation, the website should work locally by typing "localhost" on your browser once your php server and DB manager are up and running. 

/Copiez le contenu du dossier sur votre machine. Vous trouverez un dossier "DB" qui contient une copie de la base de donnée de test. Importez ce fichier en utlisant votre gestionnaire de DB. 

Dans config/packages/doctrine.yaml, section "parameters", remplacez la valeur de la ligne env(DTATABASE_URL) par vos propres données, suivant le shéma "mysql://NOMUTILSATEURS:MOTDEPASSE@ADRESSEIP:PORT/NOM DE LA BASE".

La base de données contient plusieurs tables nécessaires au bon fonctionnement du site. Les fixtures ne sont pas complètes, ne vous en servez pas, elle briseraient la logique métier.

Avec ces préparations, le site devrait fonctionner une fois votre serveur php et gestionnaire de DB lancés. 

Testing:

Run PHP unit in the folder root (typing "./bin/phpunit tests/ --debug") to launch the test suite. 
The controller is tested in unit and functionnal aspects (currently one test failing). 
The mailer is tested on a functionnal basis (works but the test is currently not operational).
The form builder is tested in unit and functionnal aspects.

/Lancez PHP unit dans le dossier racine (en tapant "./bin/phpunit tests/ --debug") pour lancer la série de tests.
Le controlleur est testé sur des aspects unitaire et fonctionnels (Actuellement un test en échec).
La classe gérant l'envoi de mail est testé fonctionnellement (la classe fonctionne mais le test échoue).
Le constructeur de formulaire est testé sur des aspects unitaire et fonctionnels. 



