# Environment variables
ENV_FILE = ./server/.env
include $(ENV_FILE)

# Other variables
BACKUP_FOLDER = /var/tmp/db_backups
DEV_FOLDER = /home/vagrant/Code/customroutesauth
HOMESTEAD_BOX_FOLDER=../homestead-dev-box

# Commands
# TODO: dev_configure to run all configuration commands to set up dev instance?
test:
	echo $(APP_NAME)

# TODO: create a cron job to create daily database backups
# TODO: transfer the backup to S3 https://laravel.com/docs/5.8/homestead#configuring-minio
db_backup:
	mkdir -p $(BACKUP_FOLDER)
	$(eval BACKUP_NAME := $(DB_DATABASE)_$(shell date '+%Y%m%d_%H%M%S'))
	mysqldump $(DB_DATABASE) | gzip > $(BACKUP_FOLDER)/$(BACKUP_NAME).sql.gz
db_restore:
	# make db_restore BACKUP_NAME=db_20180525_044223
	mysql $(DB_DATABASE) < $(BACKUP_NAME).sql
# TODO: Switch to the current DB context
db_connect:
	mysql -u root -p

dev_ssh:
	make dev_sc CMD='cd $(DEV_FOLDER); bash -l'
dev_sc: # TODO: merge this command with dev_ssh once I figure out how to assign variable names properly in bash
	(cd $(HOMESTEAD_BOX_FOLDER) && make ssh CMD="$(CMD)")
dev_logs:
	make dev_sc CMD='cd $(DEV_FOLDER)/server/storage/logs; tail -n 100 -f laravel.log'
dev_artisan:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; php artisan $(CMD)'
dev_debug_artisan:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; php -dxdebug.remote_enable=1 -dxdebug.remote_autostart=on -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=10.0.2.2 artisan $(CMD)'
dev_vm_up:
	(cd $(HOMESTEAD_BOX_FOLDER) && vagrant up)
dev_vm_halt:
	(cd $(HOMESTEAD_BOX_FOLDER) && vagrant halt)

dev_migrate:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; php artisan migrate'
dev_rollback:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; php artisan migrate:rollback --step=1'

dev_cache: dev_autoload dev_clear_cache
dev_clear_cache:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; php artisan cache:clear; php artisan view:clear; php artisan config:clear'
dev_autoload:
	make dev_sc CMD='cd $(DEV_FOLDER)/server; composer dump-autoload'
