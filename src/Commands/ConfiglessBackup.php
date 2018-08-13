<?php

namespace SjorsO\ConfiglessLaravelBackup\Commands;

use Exception;
use Spatie\Backup\Commands\BaseCommand;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Exceptions\InvalidCommand;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class ConfiglessBackup extends BaseCommand
{
    /** @var string */
    protected $signature = 'backup:run-configless {--filename=} {--only-db} {--db-name=*} {--only-files} {--only-to-disk=} {--disable-notifications} {--set-destination-disks=}';

    /** @var string */
    protected $description = 'Run the backup.';

    public function handle()
    {
        consoleOutput()->comment('Starting backup...');

        $disableNotifications = $this->option('disable-notifications');

        try {
            $this->guardAgainstInvalidOptions();

            $config = config('backup');

            if ($this->option('set-destination-disks')) {
                $disks = explode(',', $this->option('set-destination-disks'));

                $config['backup']['destination']['disks'] = $disks;
            }

            $backupJob = BackupJobFactory::createFromArray($config);

            if ($this->option('only-db')) {
                $backupJob->dontBackupFilesystem();
            }
            if ($this->option('db-name')) {
                $backupJob->onlyDbName($this->option('db-name'));
            }

            if ($this->option('only-files')) {
                $backupJob->dontBackupDatabases();
            }

            if ($this->option('only-to-disk')) {
                $backupJob->onlyBackupTo($this->option('only-to-disk'));
            }

            if ($this->option('filename')) {
                $backupJob->setFilename($this->option('filename'));
            }

            if ($disableNotifications) {
                $backupJob->disableNotifications();
            }

            $backupJob->run();

            consoleOutput()->comment('Backup completed!');
        } catch (Exception $exception) {
            consoleOutput()->error("Backup failed because: {$exception->getMessage()}.");

            if (! $disableNotifications) {
                event(new BackupHasFailed($exception));
            }

            return 1;
        }
    }

    protected function guardAgainstInvalidOptions()
    {
        if ($this->option('only-db') && $this->option('only-files')) {
            throw InvalidCommand::create('Cannot use `only-db` and `only-files` together');
        }
    }
}
