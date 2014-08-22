<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UserHallRelationSupport extends Command
{
    const DEFAULT_PASSWORD = '91891888';
    const ARGUMENT_OPERATE = 'operate';

    const OPTION_USER = 'user';
    const OPTION_HALL = 'hall';
    const OPTION_USERNAME = 'username';
    const OPTION_PASSWORD = 'password';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:hall';

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
        $hallIds = $this->option(self::OPTION_HALL);
        $userId = $this->option(self::OPTION_USER);

        switch($argument){
            case 'add':
                if(Hall::whereIn('id', $hallIds)->count() != count($hallIds)){
                    $this->error(sprintf('least one hall (%s) is not existed!', implode(',', $hallIds)));
                    break;
                }

                $user = User::findOrFail($userId);
                $isExisted= $user->Halls()->whereIn('gt_hall_tiny.id', $hallIds)->exists();
                if($isExisted){
                    $this->error(sprintf('least one hall (%s) owned by user(%s)', implode(',', $hallIds), $userId));
                    break;
                }

                $user->Halls()->attach($hallIds);

                $this->info('success');

                break;
            case 'remove':
                count($hallIds) > 0 ? User::findOrFail($userId)->Halls()->detach($hallIds)
                    : User::findOrFail($userId)->Halls()->sync(array());
                $this->info('success');
                break;

            case 'generate';
                if(Hall::whereIn('id', $hallIds)->count() != count($hallIds)){
                    $this->error(sprintf('least one hall (%s) is not existed!', implode(',', $hallIds)));
                    break;
                }

                if(RelationUserHall::whereIn('hall_id', $hallIds)->exists()){
                    $this->error(sprintf('least one hall (%s) is generated!', implode(',', $hallIds)));
                    break;
                }

                $username = $this->option(self::OPTION_USERNAME);
                $password = $this->option(self::OPTION_PASSWORD);
                empty($password) && $password = self::DEFAULT_PASSWORD;

                Hall::whereIn('id' , $hallIds)->chunk(20, function($halls) use($username, $password){
                    foreach($halls as $hall){
                        DB::beginTransaction();

                        $hallId = $hall->id;
                        $this->info(sprintf('generate for hall (%s)', $hallId));

                        empty($username) && $username = $hall->code . $hallId;

                        $createdUser = User::create(
                            array('nickname' => $username, 'password' => Hash::make($password))
                        );

                        if($createdUser instanceof User){
                            $this->info(sprintf('created user(%s) with name(%s), password(%s)',
                                $createdUser->user_id, $createdUser->nickname, $password));

                            $createdUser->roles()->save(new Role(array('role_id' => 3)));

                            $this->info('attached the role 3(hall) to user');

                            $createdUser->Halls()->attach($hall->id);

                        }

                        DB::commit();

                        $this->info('attached the hall with the user');
                    }
                });
                break;
            case 'destroy':
                Hall::with('Users')->whereIn('id' , $hallIds)->chunk(20, function($halls){
                    foreach($halls as $hall){
                        DB::beginTransaction();
                        $this->info(sprintf('destroy for hall (%s)', $hall->id));
                        foreach($hall->Users as $user){
                            if($user instanceof User){
                                $this->info(sprintf('destroy user (%s)', $user->user_id));

                                Role::where('user_id', '=', $user->user_id)->delete();
                                $this->info('delete the role relation with user');

                                $user->Halls()->sync(array());
                                $this->info('delete the hall relation with user');

                                $user->delete();
                                $this->info('delete the user');
                            }
                        }
                        DB::commit();
                    }
                });
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
                    "%s\n%s\n%s\n%s",
                    "add - add hall to a user",
                    "remove - remove hall from a user",
                    "generate - a user for hall, and create relation",
                    "destroy - destroy a user which created from hall"
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
        return array(
            array(self::OPTION_HALL, null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'the hall ids', null),
            array(self::OPTION_USER, null, InputOption::VALUE_OPTIONAL, 'the user id', null),
            array(self::OPTION_USERNAME, null, InputOption::VALUE_OPTIONAL, 'only needed for username', null),
            array(self::OPTION_PASSWORD, null, InputOption::VALUE_OPTIONAL, 'just needed for generate', null)
        );
    }

}
