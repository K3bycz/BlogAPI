<?php

namespace App\Formatter;

use App\Entity\User;

class UserFormatter
{
    public function format(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }

    public function formatCollection(iterable $users): array
    {
        $formattedUsers = [];

        foreach ($users as $user) {
            $formattedUsers[] = $this->format($user);
        }

        return $formattedUsers;
    }
}
