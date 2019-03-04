<?php


namespace Xapro\Laratools\Console;


use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Xapro\Laratools\LaratoolsServiceProvider;

class AddUser extends Command
{
    protected $name;
    protected $email;
    protected $password;

    protected $signature = 'xapro:adduser {--A|admin}';

    protected $description = 'Add new user to users table';

    public function handle()
    {
        // get name
        $name = $this->ask('Set user\'s name (empty by default)') ?? '';

        // get email
        $preparedEmail = $name
            ? $name . '@' . substr(config('app.url'), strpos(config('app.url'), '://') + 3)
            : null;
        $email = $this->ask(sprintf('Set user\'s email (%s by default)', $preparedEmail ?? 'empty'))
            ?? $preparedEmail;

        // abort if all empty
        if (!$email) return $this->error('lol?');

        //get password
        $password = $this->ask('Set user\'s password (empty by default)') ?? '';

        //get role
        $role = $this->ask('Set user\'s role (guest by default)') ?? 'guest';

        // should we create role column?
        $shouldCreateRoleCol = $this->confirm('Should we create role column?');
        if ($shouldCreateRoleCol) {
            $this->call('vendor:publish', ['--provider' => LaratoolsServiceProvider::class, '--tag' => 'migrations']);
            $path = 'vendor/xapro/laratools/migrations/2019_03_04_00_create_role_column.php';
            $this->call('migrate', ['--path' => $path]);
        }

        $params = compact('name','email','password','role');
        
        $user = User::make($params)->save();

        $this->table(array_keys($params), [$params]);
    }
}
