<?php

namespace App\Command;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/*
 * Command :
 * php bin/console app:userManager
 *
 *
 *
# STYLES
# https://symfony.com/doc/current/console/style.html
$io = new SymfonyStyle($input, $output);
$io->title('Lorem Ipsum Dolor Sit Amet');
$io->section('Lorem Ipsum Dolor Sit Amet');
$io->note('Lorem Ipsum Dolor Sit Amet');
$io->caution('Lorem Ipsum Dolor Sit Amet');
$io->success('Lorem Ipsum Dolor Sit Amet');
$io->warning('Lorem Ipsum Dolor Sit Amet');
$io->error('Lorem Ipsum Dolor Sit Amet');
$io->table(
    array('Header 1', 'Header 2'),
    array(
        array('Cell 1-1', 'Cell 1-2'),
        array('Cell 2-1', 'Cell 2-2'),
        array('Cell 3-1', 'Cell 3-2'),
    )
);
$io->choice('Select the queue to analyze', array('queue1', 'queue2', 'queue3'), 'queue1');
$io->ask('Select an information');
$io->listing(array(
    'Element #1 Lorem ipsum dolor sit amet',
    'Element #2 Lorem ipsum dolor sit amet',
    'Element #3 Lorem ipsum dolor sit amet',
));
$io->askHidden('What is your password?');

$io->progressStart();
$io->progressStart(100);
$io->progressAdvance();
$io->progressAdvance(10);
$io->progressFinish();
*/


/**
 * Class UserManager
 * @package App\Command
 */
class UserManager extends Command
{
    private $eManager;

    private $output;

    /**
     * UserRoleManager constructor.
     * @param null|string $name
     * @param EntityManagerInterface $eManager
     */
    public function __construct(?string $name = null, EntityManagerInterface $eManager)
    {
        parent::__construct($name);
        $this->eManager = $eManager;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:userManager')
            // the short description shown while running "php bin/console list"
            ->setDescription('manager users.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to manage users and save to database...');
    }

    /**
     * Main function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {

        # INIT STYLES
        $this->output = new SymfonyStyle($input, $output);

        # DISPLAY TITLE
        $this->output->title("Welcome to User Role Manager");

        # Do a progress bar for fun
        $this->output->note("Loading datas...");
        $this->output->progressStart(100);
        for($i=0;$i<10;$i++){
            $this->output->progressAdvance(10);
            sleep(1);
        }
        $this->output->progressFinish();

        # DISPLAY MAIN MENU
        $this->displayMainMenu();
    }

    /**
     * @return void
     */
    private function displayMainMenu(): void
    {
        while (true) {
            # Ask user for a choice
            $input = $this->output->choice(
                'Select an action',
                [
                    'Display user list',
                    'Add user role (requier user id)',
                    'Exit'
                ],
                'Display user list'
            );

            #Action from choice
            switch ($input) {
                case 'Display user list':
                    $this->displayUserList();
                    break;

                case 'Add user role (requier user id)':
                    $this->addUserRole();
                    break;

                case 'Exit':
                    exit();
                    break;
            }
        }
    }

    /**
     * @return void
     */
    private function displayUserList(): void
    {
        $display = [];

        # GET USER INFOS
        $userList = $this->eManager->getRepository(Author::class)->findAll();

        # PREPARE USERS INFOS
        foreach ($userList as $user) {
            $display[] = [
                $user->getId(),
                $user->getEmail(),
                join("+", $user->getRoles()??[])
            ];
        }

        # DISPLAY USER LIST AS TABLE
        $this->output->table(['ID', 'EMAIL', 'ROLES'], $display);
    }

    /**
     * @return void
     */
    private function addUserRole(): void
    {
        ############################
        # STEP 1 : Ask for User ID #
        ############################

        #Ask for user select
        $input = $this->output->ask('Select an id :');

        #get user in doctrine
        $user = $this->eManager->getRepository(Author::class)->find($input);

        #check if found user
        if (!$user) {
            # ERROR COLOR
            //$this->output->writeln("<error>User id '$input' not found !</error>");
            $this->output->error("User id '$input' not found !");
            return;
        }

        ################################
        # STEP 2 : Ask for ROLE to Add #
        ################################

        # Ask user for a choice
        $input = $this->output->choice(
            'Select a role to add',
            ['ROLE_MEMBER','ROLE_AUTHOR','ROLE_ADMIN']
        );

        //POSSIBILITY TO CHECK IF ROLE EXIST BEFORE ADD IT

        # update user role
        $user->setRoles($input);

        # save to database
        $this->eManager->flush();

        # OK COLOR
        $this->output->success("The role '$input' has been added to user with id '" . $user->getId() . "'");

    }

}
