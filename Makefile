DOCKER_ENV_FILE_PATH = docker/.env
DOCKER_ENV_LOCAL_FILE_PATH = docker/.env.local

ifeq ($(shell test -f ${DOCKER_ENV_LOCAL_FILE_PATH} && echo yes),yes)
    DOCKER_ENV_FILE_PATH = ${DOCKER_ENV_LOCAL_FILE_PATH}
endif

include ${DOCKER_ENV_FILE_PATH}

DOCKER_COMPOSE = docker compose --env-file ${DOCKER_ENV_FILE_PATH}
DOCKER_COMPOSE_PHP_EXEC = ${DOCKER_COMPOSE} exec php

##################
## Docker
##################

dc_build: #build services
	${DOCKER_COMPOSE} build

dc_up: #up containers
	${DOCKER_COMPOSE} up -d --remove-orphans

dc_rebuild_and_up: #stop, build services again and up them
	${DOCKER_COMPOSE} down --remove-orphans
	${DOCKER_COMPOSE} build
	${DOCKER_COMPOSE} up -d --remove-orphans

dc_ps: #show containers list
	${DOCKER_COMPOSE} ps -a

dc_down: #down containers
	${DOCKER_COMPOSE} down --remove-orphans

dc_enter_php: #enter php container
	${DOCKER_COMPOSE} exec php bash

dc_logs_php: #show php container logs
	${DOCKER_COMPOSE} logs php

##################
## Cache
##################

cache_clear:
	${DOCKER_COMPOSE_PHP_EXEC} rm -Rf var/cache/*
	${DOCKER_COMPOSE_PHP_EXEC} bin/console cache:clear
	${DOCKER_COMPOSE_PHP_EXEC} bin/console cache:clear --env=test


##################
## Install dependecies via composer
##################

composer_install:
	${DOCKER_COMPOSE_PHP_EXEC} composer install -n
