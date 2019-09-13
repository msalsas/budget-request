<?php

namespace App\DataFixtures;

use App\Entity\Budget\Budget;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const EMAIL_PREFIX = "johndoe";
    const EMAIL_SUFFIX = "@email.com";
    const TELEPHONE = "+34 971 23 72 67";
    const ADDRESS = "Old Avenue, 34, 04679 - UK";
    const TITLE_PREFIX = "Budget ";
    const DESCRIPTION = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";

    public function load(ObjectManager $manager)
    {
        $users = array();
        // create 5 users
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail(self::EMAIL_PREFIX . $i . self::EMAIL_SUFFIX);
            $user->setTelephone(self::TELEPHONE);
            $user->setAddress(self::ADDRESS);
            $users[] = $user;
            $manager->persist($user);
        }

        // create 20 budgets!
        for ($i = 0; $i < 20; $i++) {
            $budget = new Budget();
            $user = $this->selectUser($i, $users);
            $budget->setTitle(self::TITLE_PREFIX . $i);
            $budget->setDescription(self::DESCRIPTION);
            $budget->setStatus(Budget::STATUS_PENDING);
            $budget->setUser($user);
            $manager->persist($budget);
        }

        $manager->flush();
    }

    protected function selectUser(int $counter, array $users)
    {
        if ($counter < 3) {
            $user = $users[0];
        } elseif ($counter < 5) {
            $user = $users[1];
        } elseif ($counter < 9) {
            $user = $users[2];
        } elseif ($counter < 14) {
            $user = $users[3];
        } else {
            $user = $users[4];
        }

        return $user;
    }
}
