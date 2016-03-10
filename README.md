# Repo

This package creates scaffolding to implement the Repository pattern.

## Install

Add the following to your `composer.json` file:

```
"garethnic/repo": "dev-master"
```

Add the garethnic\ServiceProvider to your config/app.php providers array:

``` php
garethnic\Repo\RepoServiceProvider::class,
```

Then run the following artisan command:

``` bash
$ php artisan vendor:publish
```

This will create the following folder structure in your `app/` folder:

- Models
    - Concrete
        - Eloquent
        - AbstractEloquentRepository.php
    - Contracts
        - Repositories
        - RepositoryInterface.php
    - Objects

## Usage

After installing the package the artisan command `repo:create` should be available.

To create the repository structure for your object run the command:

``` bash
$ php artisan repo:create Post
```

This will create the following files:

- Models/Objects/Post.php
- Models/Contracts/Repositories/PostRepository.php
- Models/Concrete/Eloquent/EloquentPostRepository.php

It will also add the bindings in your `AppServiceProvider.php` file:

``` php
$this->app->bind(
    'App\Models\Contracts\Repositories\PostRepository', // Repository (Interface)
    'App\Models\Concrete\Eloquent\EloquentPostRepository' // Eloquent (Class)
);
```

Then in your Controller it's simply:

``` php
...
use App\Models\Contracts\Repositories\PostRepository;

...

protected $model;

public function __construct(PostRepository $repo)
{
    $this->model = $repo;
}
```

### Options

* `-m` or `--m`

Use the the `-m` option to create a migration file for your object:

``` bash
$ php artisan repo:create Post -m
```

And off you go!

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/garethnic/repo
[link-downloads]: https://packagist.org/packages/garethnic/repo
[link-author]: https://github.com/garethnic
