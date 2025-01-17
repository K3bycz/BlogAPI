<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Group;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    //Dodawanie użytkownika - test1
    public function testUserEntity()
    {
        $user = new User();

        $user->setName('Jan');
        $user->setSurname('Kowalski');
        $user->setEmail('jan.kowalski@user.pl');
        $user->setPassword('adminadmin');

        $this->assertSame('Jan', $user->getName());
        $this->assertSame('Kowalski', $user->getSurname());
        $this->assertSame('jan.kowalski@user.pl', $user->getEmail());
        $this->assertSame('adminadmin', $user->getPassword());
    }

    //Dodawanie użytkownika do grupy - test2
    public function testAddGroup()
    {
        $user = new User();
        $group = new Group('Admin');

        $user->addGroup($group);

        $this->assertCount(1, $user->getGroups());
        $this->assertTrue($user->getGroups()->contains($group));
    }

    // Usuwanie użytkownika z grupy - test3
    public function testRemoveGroup()
    {
        $user = new User();
        $group = new Group('Admin');

        $user->addGroup($group);
        $user->removeGroup($group);

        $this->assertCount(0, $user->getGroups());
        $this->assertFalse($user->getGroups()->contains($group));
    }
}