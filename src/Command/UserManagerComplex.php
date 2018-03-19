<?php

namespace App\Command;

use App\SiteConfig;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/*
 * Command :
 * php bin/console app:userManagerComplex
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
*/


/**
 * Class UserManager
 * @package App\Command
 */
class UserManagerComplex extends Command
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
            ->setName('app:userManagerComplex')
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
                    'Enable user account (requier user id)',
                    'Add user role (requier user id)',
                    'Remove user role (requier user id)',
                    'Exit'
                ],
                'Display user list'
            );

            #Action from choice
            switch ($input) {
                case 'Display user list':
                    $this->displayUserList();
                    break;
                case 'Enable user account (requier user id)':
                    $this->enableUser();
                    break;
                case 'Add user role (requier user id)':
                    $this->addUserRole();
                    break;
                case 'Remove user role (requier user id)':
                    $this->removeUserRole();
                    break;
                case 'Exit':
                    exit();
                    break;
                default:
                    # ERROR COLOR (Should not be possible as already managed by choice()
                    $this->output->error("$input is not a valid selection");
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
        $entity = SiteConfig::USERENTITY;
        $userList = $this->eManager->getRepository(get_class(new $entity))->findAll();

        # PREPARE USERS INFOS
        foreach ($userList as $user) {
            $display[] = [
                $user->getId(),
                $user->getEmail(),
                $user->isEnabled(),
                join("+", $user->getRoles())
            ];
        }

        # DISPLAY USER LIST AS TABLE
        $this->output->table(['ID', 'EMAIL', 'ACTIVATED', 'ROLES'], $display);
    }

    /**
     * @return void
     */
    private function enableUser(): void
    {
        $user = $this->getUserId();

        if (!$user) {
            return;
        }

        # set user as active
        $user->setActive();
        # save to database
        $this->eManager->flush();

        # OK COLOR
        $this->output->success("The user '" . $user->getId() . "' has been activated");
    }

    /**
     * @return void
     */
    private function addUserRole(): void
    {

        $user = $this->getUserId();

        if (!$user) {
            return;
        }

        # Add options
        $userRolesDisplay = array_diff(SiteConfig::USERROLES, $user->getRoles());
        $userRolesDisplay[] = "Cancel";
        $userRolesDisplay[] = "Exit";

        # Check if can add role
        if (count($userRolesDisplay) == 2) {
            $this->output->warning("No role can be added from user with id '" . $user->getId());
            return;
        }

        # Ask user for a choice
        $input = $this->output->choice('Select a role to add', $userRolesDisplay, 'Cancel');

        if (!$input == 'Cancel') {
            return;
        }

        switch ($input) {
            case "Cancel":
                return;
                break;
            case "Exit":
                exit();
                break;
            default:
                # role already exist
                if ($user->hasRole($input)) {
                    # ERROR COLOR
                    $this->output->warning("The user already has the role " . $input);
                    return;
                }

                # update user role
                $user
                    ->setRoles($input)
                    ->setActive();
                # save to database
                $this->eManager->flush();

                # OK COLOR
                $this->output->success("The role '$input' has been added to user with id '" . $user->getId() . "'");
                break;
        }
    }


    /**
     * @return void
     */
    private function removeUserRole(): void
    {
        $user = $this->getUserId();

        if (!$user) {
            return;
        }

        $userRoles = $user->getRoles();

        # Add options
        $userRolesDisplay = $userRoles;
        $userRolesDisplay[] = "Cancel";
        $userRolesDisplay[] = "Exit";

        # Check if can remove role
        if (count($userRolesDisplay) == 2) {
            $this->output->warning("No role can be removed from user with id '" . $user->getId());
            return;
        }

        # Ask user for a choice
        $input = $this->output->choice('Remove a role from the user ' . $user->getId(), $userRolesDisplay);

        if ($input == "Cancel") {
            return;
        }

        if ($input == "Exit") {
            exit();
        }

        # update user info
        if (($key = array_search($input, $userRoles)) !== false) {
            unset($userRoles[$key]);
        }
        $user->setAllRoles($userRoles);
        # save role to database
        $this->eManager->flush();

        # OK COLOR
        $this->output->success("The role '$input' has been removed from user with id '" . $user->getId() . "'");
    }

    private function getUserId()
    {
        #Ask for user select
        $input = $this->output->ask('Select an id user');

        $entity = SiteConfig::USERENTITY;
        $user = $this->eManager->getRepository(get_class(new $entity))->findOneBy(['id' => $input]);

        if (!$user) {
            # ERROR COLOR
            $this->output->writeln("<error>User id '$input' not found !</error>");
            return null;
        }

        return $user;
    }
}
