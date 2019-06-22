PHINX_CONFIG=phinx.php
PHINX_PATH=vendor/bin/phinx

migrate:
	php $(PHINX_PATH) migrate -c $(PHINX_CONFIG)

NAME?=NewMigration
create-migration:
	php $(PHINX_PATH) create $(NAME) -c $(PHINX_CONFIG)

rollback:
	php $(PHINX_PATH) rollback -c $(PHINX_CONFIG)