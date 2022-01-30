# Starmoozie\LaravelLogManager

## Install

``` bash
# install the package with composer
composer require starmoozie/logmanager

# [optional] Add a sidebar_content item for it
php artisan starmoozie:add-sidebar-content "<li class='nav-item'><a class='nav-link' href='{{ starmoozie_url('log') }}'><i class='nav-icon la la-terminal'></i> Logs</a></li>"
```

**For a better user experience, make sure Laravel is configured to create a new log file for each day.** That way, you can browse log entries by day too. You can do that in your ```config/logging.php``` file. 

From a default Laravel configuration, make sure the ```daily``` channel is inside the ```stack``` channel, which is used by default:

```php
    'channels' => [
        'stack' => [
            'driver'   => 'stack',
            'channels' => ['daily'],
        ],
        'single' => [
            'driver' => 'single',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => 'debug',
        ],
        'daily' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/laravel.log'),
            'level'  => 'debug',
            'days'   => 7,
        ],
```

You can change the number of days, or path, level, etc inside this the ```daily``` channel.


## Usage

Add a menu element for it or just try at **your-project-domain/admin/log**