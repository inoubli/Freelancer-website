<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture{

    /**{
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'tel' => '22314753',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 0,
            'note' => 0,
            'roles' => [User::ROLE_ADMIN]
        ],
        [
            'username' => 'john_doe',
            'email' => 'john@blog.com',
            'tel' => '97888745',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 1,
            'note' => 0,
            'roles' => [User::ROLE_PUBLISHER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob@blog.com',
            'tel' => '97947712',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 1,
            'note' => 0,
            'roles' => [User::ROLE_PUBLISHER]
        ],
        [
            'username' => 'jenny_rowling',
            'email' => 'jenny@blog.com',
            'tel' => '55471644',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 2,
            'note' => 0,
            'roles' => [User::ROLE_PUBLISHER]
        ],
        [
            'username' => 'han_solo',
            'email' => 'han@blog.com',
            'tel' => '96789447',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 2,
            'note' => 0,
            'roles' => [User::ROLE_FREELANCER]
        ],
        [
            'username' => 'jedi_knight',
            'email' => 'jedi@blog.com',
            'tel' => '21223515',
            'pays' => 'TUNISIE',
            'password' => 'secret123#',
            'type' => 2,
            'note' => 0,
            'roles' => [User::ROLE_FREELANCER]
        ]];
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadProjects($manager);
        $this->loadOffers($manager);

    }

    public function loadProjects(ObjectManager $manager){

        //$user = $this->getReference('user_admin');

        for ($i = 0 ; $i < 100; $i++){
            $project = new Project();
            $project->setTitle($this->faker->realText(30));
            $project->setDescription($this->faker->realText());
            $project->setBudget($this->faker->numberBetween(150,10000));
            $project->setStatus($this->faker->numberBetween(0,2));
            $project->setPublished($this->faker->dateTimeThisYear);

            $authorReference = $this->getRandomUserReference($project);


            $project->setAuthor($authorReference);

            $this->setReference("project_$i", $project);

            $manager->persist($project);
        }

        $manager->flush();
    }

    public function loadOffers(ObjectManager $manager){
        for ($i = 0 ; $i < 100; $i++){
            for ($j = 0 ; $j< rand(1,3); $j++) {
                $offer = new Offer();
                $offer->setPrice($this->faker->numberBetween(150,10000));
                $offer->setPeriod($this->faker->word());
                $offer->setDescription($this->faker->realText());
                $offer->setFilename($this->faker->imageUrl(150,150,'cats'));
                $offer->setPublished($this->faker->dateTimeThisYear());
                $offer->setStatus($this->faker->word());

                $authorReference = $this->getRandomUserReference($offer);

                $offer->setAuthor($authorReference);
                $offer->setProject($this->getReference("project_$i"));

                $manager->persist($offer);

            }
        }
        $manager->flush();

    }

    public function loadUsers(ObjectManager $manager){

        foreach (self::USERS as $userFixture) {
            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setTel($userFixture['tel']);
            $user->setPays($userFixture['pays']);
            $user->setNote($userFixture['note']);

            $user->setPassword(
                $this->passwordEncoder->encodePassword($user,($userFixture['password']))
            );

            $user->setType($userFixture['type']);
            $user->setRoles($userFixture['roles']);

            $this->addReference('user_' . $userFixture['username'],$user);

            $manager->persist($user);
        }

        $manager->flush();
    }


    public function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0,5)];
        //Reccursive condition,
        //Stop Condition when creating a Project: $randomUser haven't one of the roles ADMIN,PUBLISHER
        if ($entity instanceof Project && !count(array_intersect(
                $randomUser['roles'],
                [
                    User::ROLE_ADMIN,
                    User::ROLE_PUBLISHER
                ]
            ))){
            return $this->getRandomUserReference($entity);
        }
        //Reccursive condition,
        //Stop Condition when creating an Offer: $randomUser haven't one of the roles ADMIN,FREELANCER
        if ($entity instanceof Offer && !count(array_intersect(
                $randomUser['roles'],
                [
                    //User::ROLE_ADMIN,
                    User::ROLE_FREELANCER
                ]
            ))){
            return $this->getRandomUserReference($entity);
        }


        return $this->getReference('user_' . $randomUser['username']);
    }

}
