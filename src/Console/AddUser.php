<?php


namespace Xapro\Laratools\Console;


use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

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

        $params = compact('name','email','password','role');
        
        $user = User::make($params);

        $this->table(array_keys($params), [$params]);
    }
}
