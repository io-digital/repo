<?php

namespace garethnic\Repo;

use garethnic\Repo\Helper;
use Illuminate\Console\Command;

class RepoScaffold extends Command
{
    protected $helper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repo:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an object to scaffold';

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

        if (empty($name)) {
            return $this->error("You need to specify an object to create");
        }

        $newObject = ucfirst($name).'.php';
        $newContract = ucfirst($name).'Repository.php';
        $newConcrete = 'Eloquent'.ucfirst($name).'Repository.php';

        // Object creation
        $objectPath = __DIR__.'/object.stub';
        $objectAppPath = app_path('Models/Objects/'.$newObject);

        $this->helper->replaceAndSave($objectPath, '{{name}}', $name, $objectAppPath);

        // Contract creation
        $contractPath = __DIR__.'/contract.stub';
        $contractAppPath = app_path('Models/Contracts/Repositories/'.$newContract);

        $this->helper->replaceAndSave($contractPath, '{{name}}', $name, $contractAppPath);

        // Concrete creation
        $concretePath = __DIR__.'/concrete.stub';
        $concreteAppPath = app_path('Models/Concrete/Eloquent/'.$newConcrete);

        $this->helper->replaceAndSave($concretePath, '{{name}}', $name, $concreteAppPath);

        $appBindContract = substr('App\Models\Contracts\Repositories\\'.$newContract, 0, -4);
        $appBindConcrete = substr('App\Models\Concrete\Eloquent\\'.$newConcrete, 0, -4);

        $bindImplementation = "public function register()
    {
        \$this->app->bind(
             '$appBindContract', // Repository (Interface)
             '$appBindConcrete' // Eloquent (Class)
        );
        ";

        $search = 'public function register()
    {';

        $this->helper->replaceAndSave(getcwd().'/app/providers/AppServiceProvider.php', $search , $bindImplementation);

        $this->info('Your structure has been created');
    }
}
