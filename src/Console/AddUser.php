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

    protected $signature = 'xapro:adduser {--S|simple}';

    protected $description = 'Add new user to users table';

    public function handle()
    {
        $name = $this->ask('Set user\'s name (empty by default)') ?? null;

        $email = $this->getEmail($name);
        if (!$email) return $this->error('Type name or email!');

        $password = $this->ask('Set user\'s password (empty by default)') ?? '';

        if (!$this->option('simple')) {
            $role = $this->ask('Set user\'s role (guest by default)') ?? 'guest';

            // should we create role column?
            $shouldCreateRoleCol = $this->confirm('Should we create role column?');
            if ($shouldCreateRoleCol) {
                $this->call('vendor:publish', ['--provider' => LaratoolsServiceProvider::class, '--tag' => 'migrations']);
                $path = 'vendor/xapro/laratools/migrations/2019_03_04_00_create_role_column.php';
                $this->call('migrate', ['--path' => $path]);
            }
        }

        $params = compact('name','email','password');
        if (isset($role)) $params['role'] = $role;
        
        $user = User::make($params);
        $user->password = Hash::make($password);
        $user->save();

        $this->table(array_keys($params), [$params]);
    }

    private function getEmail($name)
    {
        // if we have a name create email based on it. Null otherwise
        $preparedEmail = $name
            ? $name . '@' . substr(config('app.url'), strpos(config('app.url'), '://') + 3)
            : null;

        // return null if no email and no name
        $email = $this->ask(sprintf('Set user\'s email (%s by default)', $preparedEmail ?? 'empty'))
            ?? $preparedEmail;

        return $email;
    }
}
