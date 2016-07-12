# Repo

This package creates scaffolding to implement the Repository pattern.

## Install

`composer require io-digital/repo`

Add the ServiceProvider to your config/app.php providers array:

``` php
IoDigital\Repo\RepoServiceProvider::class,
```

Then run the following artisan command:

``` bash
$ php artisan vendor:publish --provider="IoDigital\Repo\RepoServiceProvider"
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

The repository interface provides the following methods:

``` php
public function all($with = [], $orderBy = []);

public function find($id, $relations = []);

public function findBy($attribute, $value, $columns = ['*']);

public function findAllBy($attribute, $value, $columns = array('*'));

public function findWhere($where, $columns = ['*'], $or = false);

public function paginate($perPage = 25, $columns = ['*']);

public function simplePaginate($limit = null, $columns = ['*']);

public function create($attributes = []);

public function edit($id, $attributes = []);

public function delete($id);
```

The implementations can found in `Models/Concrete/AbstractEloquentRepository.php`

Example usage for the find functions:

```php
//returns with ->first()
$data = $this->model->findBy('title', $title);

//returns with ->get()
$data = $this->model->findAllBy('category', $category);

//returns with ->get()
$data = $this->model->findWhere([
            'category' => $category,
           ['year', '>' , $year],
           ['name', 'like', "%$name%"],
           ['surname', 'like', '%nes']
     ]);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## TODO

Clean up code

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
