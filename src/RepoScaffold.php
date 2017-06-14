<?php

namespace IoDigital\Repo;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class RepoScaffold extends Command
{
    protected $helper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repo:create
    {name : The name of the structure to create}
    {--m|m : Optionally create a migration file}
    {--c|c : Optionally create a resource controller file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model to scaffold';

    /**
     * Create a new command instance.
     *
     * @param Helper $helper
     * @return void
     */
    public function __construct(Helper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $createMigration = $this->option('m');
        $createResourceController = $this->option('c');

        if (empty($name)) {
            return $this->error("You need to specify an object to create");
        }

        $newObject = ucfirst($name).'.php';
        $newContract = ucfirst($name).'Repository.php';
        $newConcrete = 'Eloquent'.ucfirst($name).'Repository.php';

        $this->createObjectFiles($name, $newObject, $newContract, $newConcrete);

        $this->makeBindings($newContract, $newConcrete);

        $this->makeMigration($createMigration);

        $this->makeResourceController($name, $createResourceController);

        return $this->info('Your structure has been created');
    }

    /**
     * Create migration file if not null
     *
     * @param $option
     */
    private function makeMigration($option)
    {
        if ($option) {
            $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

            $this->call('make:migration', ['name' => "create_{$table}_table", '--create' => $table]);
        }
    }

    /**
     * Create resource controller if not null
     *
     * @param $option
     */
    private function makeResourceController($name, $option = null)
    {
        if ($option !== null) {
            $controllerName = Str::studly(class_basename($this->argument('name')));
            $controllerNameFile = $controllerName . 'Controller.php';

            try {
                $controllerPath = __DIR__ . '/controller.stub';
                $controllerAppPath = app_path('Http/Controllers/' . $controllerNameFile);

                $this->helper->replaceAndSave($controllerPath, '{{name}}', $controllerName, $controllerAppPath);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * Create oject repository files
     *
     * @param $name
     * @param $newObject
     * @param $newContract
     * @param $newConcrete
     */
    private function createObjectFiles($name, $newObject, $newContract, $newConcrete) {
        try {
            // Object creation
            $objectPath = __DIR__ . '/object.stub';
            $objectAppPath = app_path('Models/Objects/' . $newObject);

            $this->helper->replaceAndSave($objectPath, '{{name}}', $name, $objectAppPath);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        try {
            // Contract creation
            $contractPath = __DIR__ . '/contract.stub';
            $contractAppPath = app_path('Models/Contracts/Repositories/' . $newContract);

            $this->helper->replaceAndSave($contractPath, '{{name}}', $name, $contractAppPath);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        try {
            // Concrete creation
            $concretePath = __DIR__ . '/concrete.stub';
            $concreteAppPath = app_path('Models/Concrete/Eloquent/' . $newConcrete);

            $this->helper->replaceAndSave($concretePath, '{{name}}', $name, $concreteAppPath);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Make the necessary bindings for the repository
     *
     * @param $newContract
     * @param $newConcrete
     */
    private function makeBindings($newContract, $newConcrete)
    {
        $appBindContract = substr('App\Models\Contracts\Repositories\\' . $newContract, 0, -4);
        $appBindConcrete = substr('App\Models\Concrete\Eloquent\\' . $newConcrete, 0, -4);

        $bindImplementation = "public function register()
    {
        \$this->app->bind(
             '$appBindContract', // Repository (Interface)
             '$appBindConcrete' // Eloquent (Class)
        );
        ";

        $search = 'public function register()
    {';

        try {
            $this->helper->replaceAndSave(getcwd() . '/app/providers/AppServiceProvider.php', $search, $bindImplementation);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
