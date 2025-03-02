<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<ApiUser>
 */
final class ApiUserProvider implements UserProviderInterface
{
    /**
     * @param string[] $apiKeys
     */
    public function __construct(
        private readonly array $apiKeys,
    ) {
    }

    /**
     * @param ApiUser $user
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return ApiUser::class === $class;
    }


    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        if (!in_array($identifier, $this->apiKeys)) {
            throw new UserNotFoundException();
        }

        return new ApiUser($identifier);
    }
}
