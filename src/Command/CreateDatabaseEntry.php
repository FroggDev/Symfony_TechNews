<?php
/**
 * Created by PhpStorm.
 * User: Remy
 * Date: 01/03/2018
 * Time: 23:35
 */

namespace App\Command;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseEntry extends command
{

    private $eManager;

    public function __construct(?string $name = null, EntityManagerInterface $eManager)
    {
        parent::__construct($name);
        $this->eManager = $eManager;
    }

    /**
     * @return void
     */
    protected function configure() : void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-db-entry')
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new database entry.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a new database entry...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '',
            'Creating Database entry',
            '=======================',
            '',
        ]);

        $newCategory = 'Two spaced cat';
        $newAuthor = 'admin@frogg.fr';

        # you can fetch the EntityManager via $this->getDoctrine()
        # or you can add an argument to your action: index(EntityManagerInterface $em)
        $checkNewCategory = $this->eManager->getRepository(Category::class)->findOneBy(array('label' => $newCategory));
        $checkNewAuthor = $this->eManager->getRepository(Author::class)->findOneBy(array('email' => $newAuthor));

        #Adding new category if not already exist
        if ($checkNewCategory === null) {
            # create a Category
            #------------------
            $category = new Category();
            $category->setLabel($newCategory);
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $this->eManager->persist($category);

            $output->writeln(['Adding category : ' . $newCategory, '']);
        } else {
            $output->writeln(['Category already exist...Skipping category : ' . $newCategory, '']);
            $category = $checkNewCategory;
        }

        #Adding new Author if not already exist
        if ($checkNewAuthor === null) {
            # create an Author
            #------------------
            $author = new Author();
            $author->setEmail($newAuthor)
                ->setFirstName('Frogg')
                ->setLastName('Froggiz')
                ->setPassword('This is a pass')
                ->setRoles(['Admin','Contributor']);
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $this->eManager->persist($author);

            $output->writeln(['Adding author : ' . $newAuthor, '']);
        } else {
            $output->writeln(['Author already exist...Skipping author : ' . $newAuthor, '']);
            $author = $checkNewAuthor;
        }

        # create an Entity
        #------------------
        $article = new Article();
        $article->setTitle('Test Title')
            ->setContent('<p>This is a fake content</p>')
            ->setCategory($category)
            ->setAuthor($author)
            ->setSpecial(true)
            ->setSpotlight(true)
            ->setFeaturedImage('1.jpg');

        $output->writeln(['Adding a new article : Test Title', '']);


        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->eManager->persist($article);
        // actually executes the queries (i.e. the INSERT query)
        $this->eManager->flush();

        $output->writeln(['Congratulation, datas are flushed to Database !', '']);
    }
}
