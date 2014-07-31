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

                Hall::whereIn('id' , $hallIds)->chunk(20, function($halls){
                    foreach($halls as $hall){
                        $hallId = $hall->id;
                        $this->info(sprintf('generate for hall (%s)', $hallId));

                        $createdUser = User::create(
                            array('nickname' => $hall->code . $hallId, 'password' => Hash::make(self::DEFAULT_PASSWORD))
                        );

                        $this->info(sprintf('created user(%s) with name(%s), password(%s)',
                            $createdUser->user_id, $createdUser->nickname, self::DEFAULT_PASSWORD));

                        $createdUser->roles()->attach(3);

                        $this->info('attached the role 3(hall) to user');

                        $createdUser->Halls()->attach($hall->id);

                        $this->info('attached the hall with the user');
                    }
                });
                break;
            case 'destroy':
                Hall::with('Users')->whereIn('id' , $hallIds)->chunk(20, function($halls){
                    foreach($halls as $hall){
                        $this->info(sprintf('destroy for hall (%s)', $hall->id));

                        foreach($hall->Users as $user){
                            $this->info(sprintf('destroy user (%s)', $user->user_id));

                            $user->roles()->sync(array());
                            $this->info('delete the role relation with user');

                            $user->Halls()->sync(array());
                            $this->info('delete the hall relation with user');

                            $user->delete();
                            $this->info('delete the user');
                        }
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
        );
    }

}
