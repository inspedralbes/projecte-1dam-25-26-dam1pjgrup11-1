# dockerphp
Miniaplicació LAMP (PHP & Mysql) que té l'entorn de desenvolupament preparat amb docker, és a dir, és l'esquema bàsic d'una aplicació PHP amb entorn de DEV amb docker

## Ús
```bash
 $> docker compose up
```
I a continuació, obrir el navegador per accedir a http://localhost:8080

## Explicació
- L'aplicació s'aixecarà al port 8080 de la màquina local
- El codi de l'aplicació està tot a la carpeta ./php i el serveix el contenidor _web_
  - La resta de fitxers i carpetes són auxiliars
- El contenidor _mysql_ 
  - No és visible des de fora dels contenidors
  - El primer cop s'inicialitza amb una BBDD, i una taula amb algunes dades
- El docker-compose.yml Mostra dues formes de definir variables d'entorn amb docker i php
  - L'objectiu és que la cadena de connexió del php sigui dinàmica i no estigui escrita en sang ([hardcoded](https://en.wikipedia.org/wiki/Hard_coding)) a l'aplicació, per fer-ho heu d'utilitzar les variables d'entorn.
- El contenidor _web_ és una imatge construïda a mida i derivada de php:apache per incloure els drivers de mysqli de php
- Hi ha un contenidor _adminer_ al port 8081 que permet gestionar la BBDD mysql
- Tots els fitxers estan molt comentats per que quedi clar quin és l'objectiu de cada un

## Recursos addicionals
- [Xuleta d'instruccions bàsiques de Docker i Docker Compose](Docker.md)
