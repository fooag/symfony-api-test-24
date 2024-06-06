# Create Test Database

## Erklärung

Diese Datei ist ein Stellvertreter für die Arbeitschritte, die ich nicht git persistieren kann.
Folgende Dinge sind neu:
- Testdatenbank
- .env.test.local (nicht sichtbar da in .gitignore)

## Begründung
Um Filter und Zugriffsbeschränkungen testen zu können benötige ich eine Testdatenbank. Um die bereits existierende 
Datenbank nicht zu verunreinigen habe ich mir einen identischen Container angelegt: 

```shell
docker build -t foo_test_container --build-arg POSTGRES_VERSION=14 docker/postgres
```

```shell
docker run --rm --detach --name foo_test_container --publish "65432:5432" --env POSTGRES_PASSWORD=admin --env POSTGRES_USER=postgres --volume "$(pwd)/docker/postgres/db-init-scripts":/docker-entrypoint-initdb.d --volume "$(pwd)/var/foo_test_container":/var/lib/postgresql/data foo_test_container
```

Dazu muss noch eine .env.test.local - Datei mit folgendem Inhalt angelegt werden:
```
DATABASE_URL=postgresql://web:FooBar@host.docker.internal:65432/foo_intro?serverVersion=14&charset=utf8
```

Fixtures werden über folgenden Befehl eingespielt:
```
bin/console doctrine:fixtures:load --env=test --purge-with-truncate --purge-exclusions=public.bundesland
```