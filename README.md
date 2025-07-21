![Logo](https://cdn.pilotsolution.net/www/v4/index-global/img/logo-pilot-solution-3.svg)

# Repositorio
## Descripción
Este repositorio es un microservicio orientado a armar backend y APIs, donde los backend son de uso privado para comunicación con los MFE y las APIs tienen un sistema de autenticación y autorización mediante token JWT.

## Tecnologias
- PHP 8
- SLIM 4
- Patron ADR (Action-Domain-Responder)

## Requisitos Previos

Para empezar se debe cumplir con las tecnologias antes listadas.

## Consideraciones
En el caso de que tu entorno local siga teniendo php7, se recomienda el uso del siguiente comando:
`docker exec --workdir /srv/workspaces/devs/<MI_WORKSPACE>/<NOMBRE_PROYECTO> pilot-app-php81 ...`

Ejemplo:
```console
docker exec --workdir /srv/workspaces/devs/nombre.dev/hub-api pilot-app-php81 composer install
```

## Nombre del proyecto
Stock en Red

## Descripción del proyecto
Administración de la entidad Stock en Red (Factory Stock) para el BI del HUB con importación de archivos para carga masiva de datos
