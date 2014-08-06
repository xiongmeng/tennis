<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserRoleRelationSupport extends Command
{
    const DEFAULT_PASSWORD = '91891888';
    const ARGUMENT_OPERATE = 'operate';

    const OPTION_USER_ID = 'user_id';
    const OPTION_USER_NAME = 'user_name';
    const OPTION_ROLE = 'role';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "dispose relation with user and hall.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $argument = $this->argument(self::ARGUMENT_OPERATE);

        $userId = $this->option(self::OPTION_USER_ID);
        $userName = $this->option(self::OPTION_USER_NAME);
        $role = $this->option(self::OPTION_ROLE);

        if(empty($userId) && empty($userName)){
            $this->error('at least user_id or user_name required');
            return;
        }

        $user = null;
        if(!empty($userId)){
            $user = User::findOrFail($userId);
        }else{
            $user = User::where('nickname', '=', $userName)->firstOrFail();
            if(empty($user)){
                $this->error(sprintf('user_name %s is not existed', $userName));
                return;
            }
        }

        switch($argument){
            case 'add':
                if(!Role::where('user_id', '=', $userId)->where('role_id', '=', $role)->exists()){
                    $user->roles()->save(new Role(array('role_id' => $role)));
                    $this->info('add success');
                }else{
                    $this->info(sprintf('user(%s) has the role(%s)', $user->nickname, $role));
                }
                break;
            case 'remove':
                Role::where('user_id', '=', $user->user_id)->where('role_id', '=', $role)->delete();
                $this->info('remove success');
                break;
            case 'list':
                $roles = $user->roles;
                foreach($roles as $role){
                    $this->info($role['role_id']);
                }
                break;
            default :
                break;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array(self::ARGUMENT_OPERATE, InputArgument::OPTIONAL,
                sprintf(
                    "%s\n%s\n%s",
                    "add - add role to a user",
                    "remove - remove role from a user",
                    "list - list one user's role"
                ))
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $roles = Config::get('acl.roles');

        $help = '';
        foreach($roles as $id=>$role){
            $help .= ' ' . $id . "-" .$role['name'];
        }

        return array(
            array(self::OPTION_USER_ID, null,
                InputOption::VALUE_OPTIONAL, 'the user id', null),
            array(self::OPTION_USER_NAME, null, InputOption::VALUE_OPTIONAL, 'the user name', null),
            array(self::OPTION_ROLE, null, InputOption::VALUE_REQUIRED, $help, null),
        );
    }

}
