PUT customer?include_type_name=true
{
  "mappings": {
    "_doc": {
      "properties": {
        "id": {
          "type": "keyword"
        },
        "first_name": {
          "type": "text"
        },
        "last_name": {
          "type": "text"
        }
      },
      "dynamic": "strict"
    }
  }
}