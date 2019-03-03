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

    protected $signature = 'xapro:adduser {-A|admin}';

    protected $description = 'Add new user to users table';

    public function handle()
    {
        $this->name = $this->ask('Set user\'s name (empty by default).') ?? '';
        $this->email = $this->ask('Set user\'s email (empty by default).') ?? '';
        $this->password = $this->ask('Set user\'s name (empty by default).') ?? '';
        $user = User::make(
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password)
            ]
        );

        $headers = ['name','email','password'];

        $this->table($headers, $user->only(['name','email','password']));
    }
}
