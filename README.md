# Example plugin for Magic admin CRUD for WordPress

This plugin is an example of use to the https://github.com/moiseh/wp-magic-crud API.

## List of example CRUDs included

* [Games](https://github.com/moiseh/wpmc-example/blob/master/cruds/game.json)
* [Players](https://github.com/moiseh/wpmc-example/blob/master/cruds/player.json)
* [Teams](https://github.com/moiseh/wpmc-example/blob/master/cruds/team.json)

## RESTful services use examples

To enable REST for specific CRUD, just define the attribute `rest.expose_as_rest` as `true` in the JSON file.

Note: The **wp-magic-crud** doesn't have built-in auth security layer yet, so it's strongly recommended to protect the WP rest routes using [some JWT bearer auth plugin](https://wordpress.org/plugins/search/jwt+auth/)

### 1. CRUD operations

```bash
curl -X POST -H "Content-type: application/json" -d '{"name":"Michael","lastname":"J. Fox", "team_id": 2}' "http://localhost/wp-json/crud/player"
curl -X PATCH -H "Content-type: application/json" -d '{"lastname":"J. Fox Junior"}' "http://localhost/wp-json/crud/player/1"
curl -X GET "http://localhost/wp-json/crud/player/1"
```

### 2. Data pagination

```bash
curl -X GET "http://localhost/wp-json/crud/game?limit=5"
curl -X GET "http://localhost/wp-json/crud/player?search=michael"
curl -X GET "http://localhost/wp-json/crud/team?page=3"
```

### 3. Actions processing

```bash
curl -X POST -H "Content-type: application/json" -d '{"team_id": 2}' "http://localhost/wp-json/crud/player/action/set_team/1" 
```