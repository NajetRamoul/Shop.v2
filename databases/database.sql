


DROP DATABASE IF EXISTS shop;
CREATE DATABASE shop;
USE shop;

DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nom` varchar(255) NOT NULL,
    `pseudo` varchar(255) NOT NULL,
    `age` int(11) NOT NULL,
    `mail` varchar(255) NOT NULL,
    `adresse` varchar(255) NOT NULL

)ENGINE=InnoDB;

INSERT INTO `clients` (`id`, `nom`,`pseudo`, `age`, `mail`, `adresse`) VALUES
(1, "fred","fredo","19 ans","red@gmail.fr", "5 rue du jardin"),
(2, "Van Dame", "JCVD", "57 ans", "leauCestBon@hotmail.fr", "belgique"),
(3, "Bourgeois", "Docteur Bourgeois", "666 ans", "florent.bourgeois@uha.fr", "UHA 4.0"),
(4, "Dickinson", "The trooper", "59 ans", "singer@ironMaiden.uk", "Strange World"),
(5, "Edward", "Ed", "14 ans","fullMetalAlchemist@uha.fr", "10 rue de l'homonculus ; amestris");




DROP TABLE IF EXISTS commandes;
CREATE TABLE IF NOT EXISTS commandes (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `client` int(11) NOT NULL,
    `date` varchar(255) NOT NULL,
    `produits` varchar(255) NOT NULL,
    `dateDeLivraison`  varchar(255) NOT NULL,
    `payement` varchar(255) NOT NULL,
    `enseigne` varchar(255) NOT NULL,
    FOREIGN KEY (client) REFERENCES clients(id)
)ENGINE=InnoDB;

INSERT INTO `commandes` (`id`, `client`,`date`, `produits`, `dateDeLivraison`, `payement`, `enseigne`) VALUES
(1, 1, "25/10/2018","doublon, chaine Hi-Fi,Voiture téléguidée,pistolet à eau","29/10/2018","carte bancaire","https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Ikea_logo.svg/249px-Ikea_logo.svg.png"),
(2, 2, "1/3/2016", "chaise Xg3e, doublon ,pc portable Labda", '02/03/2016', "virement bancaire", "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Ikea_logo.svg/249px-Ikea_logo.svg.png"),
(3, 1, "9/3/2014", "vélo, piscine, matela", "29/4/2018", "cash","https://upload.wikimedia.org/wikipedia/en/thumb/1/12/Carrefour_logo_no_tag.svg/97px-Carrefour_logo_no_tag.svg.png"),
(4, 3, "14/1/2018", "chaine Hi-Fi, casque audio, fusil d'assaut", "22/1/2018", "carte bancaire","https://upload.wikimedia.org/wikipedia/en/thumb/1/12/Carrefour_logo_no_tag.svg/97px-Carrefour_logo_no_tag.svg.png"),
(5, 1, "04/05/2018","star wars l'intégrale, sabre laser collector, Dark vador va à la plage", "29/10/2018", "carte bancaire","https://upload.wikimedia.org/wikipedia/en/thumb/1/12/Carrefour_logo_no_tag.svg/97px-Carrefour_logo_no_tag.svg.png"),
(6, 4, "31/05/2018","star wars l'intégrale, royale, vegetarienne, méditéranéenne, cocoa","31/05/2018", "carte bancaire","https://upload.wikimedia.org/wikipedia/en/thumb/d/d2/Pizza_Hut_logo.svg/254px-Pizza_Hut_logo.svg.png"),
(7, 2, "30/05/2018", "vegetarienne,poivrons tomates", "30/05/2018", 'carte bancaire', "https://upload.wikimedia.org/wikipedia/en/thumb/d/d2/Pizza_Hut_logo.svg/254px-Pizza_Hut_logo.svg.png"),
(8, 2, "20/2/2018", "choux, brosse à dent, coca", "20/2/2018", "carte membre", 'https://upload.wikimedia.org/wikipedia/en/7/78/Système_U_2009.jpg');



DROP TABLE IF EXISTS produits;
CREATE TABLE IF NOT EXISTS produits(
    `num_produits` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `client_produits` varchar(11) NOT NULL,
)ENGINE=InnoDB;

 INSERT INTO `produits` (`num_produits`, `client_produits`) VALUES
(1, "Fred"),
(2, "Van Dame"),
(3, "Fred"),
(4, "Bourgeois"),
(5, "Fred"),
(6, "Dickinson"),
(7, "Van Dame"),
(8, "Van Dame");

DROP TABLE IF EXISTS clients_produits;
CREATE TABLE IF NOT EXISTS clients_produits (
    `id_produits` int(11) NOT NULL,
    `id_clients` int(11) NOT NULL, 
    FOREIGN KEY (id_clients) REFERENCES clients(id)
)ENGINE=InnoDB;

INSERT INTO `clients_produits` (`id_produits`, `id_clients`) VALUES
("1", "1"),
("2", "2"),
("3", "1"),
("4", "3"),
("5", "1"),
("6", "4"),
("7", "2"),
("8", "2");




