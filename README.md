
## Api-platform & elasticsearch starter
Ceci est un exemple de mise en place d'Api-platform et Elasticsearch

## Installation
### $make build
 Cette commande est à exécuter une seul fois pour crer tous les contenaures nécessaires
 ### $make start
Lancement de tous les containers nécessaires
 ### $make setup-elasticsearch-mapping
Cette commande est à exécuter une seule fois. Elle permettra de créer le mapping nécessaire pour synchroniser les entités dans api platform avec celles d'elasticsearch.
Ceci correspond à exécuter la requête ci dessous :
 

    curl -XPUT "http://es01:9200/customer?include_type_name=true" -H 'Content-Type:application/json'-d'{"mappings":{"_doc":{"properties":{"id":{"type":"keyword"},"first_name":{"type":"text"},"last_name":{"type":"text"}},"dynamic":"strict"}}}'

