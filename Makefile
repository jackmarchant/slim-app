PHINX_CONFIG=phinx.php
PHINX_PATH=vendor/bin/phinx

migrate:
	php $(PHINX_PATH) migrate -c $(PHINX_CONFIG)

create-migration: $(migration_name)
	php $(PHINX_PATH) create $(migration_name) -c $(PHINX_CONFIG)

rollback:
	php $(PHINX_PATH) rollback -c $(PHINX_CONFIG)