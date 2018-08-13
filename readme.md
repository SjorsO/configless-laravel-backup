# Configless Laravel Backup
This package is an extension of [spatie/laravel-backup](https://github.com/spatie/laravel-backup). It adds a `ConfiglessBackup` command which is a clone of the normal `BackupCommand`. The new command adds a `--set-destination-disks` option. Using this option you can make backups to specific disks without having to publish the laravel-backup config file.

I made this package because I prefer not publishing config files.

## Install
```bash
composer require sjorso/configless-laravel-backup
```

## Usage
Schedule the backup command:
```php
$schedule->command('backup:run-configless --set-destination-disks=s3')->dailyAt('01:00');
```
Thats it! You are now making backups to the disk you specified.

## Configuration
The `ConfiglessBackup` command is a clone of the normal `BackupCommand`. You can view the documentation of that command in the [in Spatie's documentation](https://docs.spatie.be/laravel-backup/v5/taking-backups/overview#taking-backups)




## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
