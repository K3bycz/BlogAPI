<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Group;
use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Blog testowy 1
        $blog1 = new BlogPost();
        $blog1->setTitle('Lorem ipsum');
        $blog1->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis dolor lectus, blandit at urna imperdiet, tempor varius eros. Praesent posuere lectus erat, in luctus sem tincidunt non. Fusce ultricies malesuada rhoncus. Etiam volutpat posuere ex, et tincidunt orci dignissim sit amet. In ac tempus nibh. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.');
        $blog1->setImageUrl('https://www.nylabone.com/-/media/project/oneweb/nylabone/images/dog101/top-10-lists/10-intelligent-dog-breeds.jpg?h=318&iar=0&w=720&hash=BDE1A53E84C77A8C1C4DA40F79DE0915');

        $manager->persist($blog1);

        // Blog testowy 2
        $blog2 = new BlogPost();
        $blog2->setTitle('Etiam pharetra');
        $blog2->setContent('Etiam pharetra orci sed semper semper. Donec aliquet commodo dapibus. Praesent orci neque, hendrerit viverra ex in, pellentesque imperdiet nisl. Morbi tristique nisl sit amet nisi semper imperdiet. Morbi egestas molestie turpis. In tincidunt consequat orci quis sagittis. Etiam molestie tellus quam, a egestas ligula imperdiet eget. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean ac pulvinar dui. Ut tristique, tortor at mattis eleifend, odio neque ultricies turpis, vitae dignissim neque ligula in nunc. ');
        $blog2->setImageUrl('https://images.theconversation.com/files/625056/original/file-20241010-19-5h51ab.jpg?ixlib=rb-4.1.0&q=45&auto=format&w=754&h=503&fit=crop&dpr=1');

        $manager->persist($blog2);

        // Tworzenie grup
        $groupAdmin = new Group('Admin'); 
        $manager->persist($groupAdmin);

        $groupUser = new Group('User');
        $manager->persist($groupUser);

        // Tworzenie użytkownika
        $user = new User();
        $user->setName('John')
            ->setSurname('Doe')
            ->setEmail('john.doe@user.pl');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user123');
        $user->setPassword($hashedPassword);

        $user->addGroup($groupUser);
        $manager->persist($user);

        // Tworzenie admina
        $user = new User();
        $user->setName('Admin')
            ->setSurname('Admin')
            ->setEmail('admin@admin.pl');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin123');
        $user->setPassword($hashedPassword);

        $user->addGroup($groupAdmin);
        $roles = $user->getRoles(); 
        $roles[] = 'ROLE_ADMIN'; 
        $user->setRoles(array_unique($roles));
        $manager->persist($user);

        $manager->flush();
    }
}

