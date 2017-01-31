# Foundation

Admin panel and tools built on top of Laravel 5.4.

## Installation

```shell
composer require morningtrain\foundation
```

## Setup

1. Add the following providers and facades to your app config:

```php
    'providers' => [
        ... 
        
        /*
         * Foundation Service Providers
         */

        \morningtrain\Janitor\JanitorServiceProvider::class,
        \morningtrain\Stub\StubServiceProvider::class,
        \morningtrain\Crud\CrudServiceProvider::class,
        \morningtrain\Themer\ThemerServiceProvider::class,
        \morningtrain\Acl\AclServiceProvider::class,
        \morningtrain\Admin\AdminServiceProvider::class,
        
        ...
    ],
    
    'aliases' => [
        ...
        
        /*
         * Foundation Facades
         */

        'Janitor' => \morningtrain\Janitor\Facades\Janitor::class,
        'Crud' => \morningtrain\Crud\Facades\Crud::class,
        'Stub' => \morningtrain\Stub\Facades\Stub::class,
        'Theme' => \morningtrain\Themer\Facades\Theme::class
        
        ...
    ]
```

2. Update your auth configuration user provider to from App\User to App\Models\User

3. Add the following to your existing authentication controllers
```php
    use morningtrain\Admin\Extensions\RedirectsAdmins;

    class ... {
        use RedirectsAdmins;
        
        ...
        /**
         * @return string
         */
        public function redirectPath()
        {
            return $this->redirectAdmin($this->guard()) ?: $this->redirectTo;
        }
    }
```

4. Publish janitor with initialize flag

```shell
php artisan janitor:publish --init
```

## Creating a new crud for the admin panel

1. Run the create command with your desired model name
```shell
php artisan crud:new MyModel --config=admin.crud
```

2. Configure migration, model and controller

3. Register the model into the admin configuration (config/admin.php)
```php
    [
        'items' => [
            ...
            App\Models\MyModel::class   => [
                'icon'  => '{material-icon}'
            ]
            ...
        ]
    ]
```

4. Update (migrates and refreshes the config)
```shell
php artisan admin:update
```