<?php

namespace App\Security\Voter;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EditionVoter implements VoterInterface
{
    public const CONFERENCE = 'conference.edition';

    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
    ) {}

    /**
     * @inheritDoc
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $result = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (self::CONFERENCE !== $attribute || !$subject instanceof Conference) {
                continue;
            }

            if ($this->checker->isGranted('ROLE_WEBSITE')) {
                $result = self::ACCESS_GRANTED;
                break;
            }

            $user = $token->getUser();
            if (!$user instanceof User) {
                $result = self::ACCESS_DENIED;
                continue;
            }

            foreach ($subject->getOrganizations() as $organization) {
                if ($user->getOrganizations()->contains($organization)) {
                    $result = self::ACCESS_GRANTED;
                    break;
                }
            }

            $result = $user === $subject->getCreatedBy() ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
        }

        return $result;
    }
}
