<?php
declare(strict_types=1);

namespace Akeneo\Connectivity\Connection\Domain\WrongCredentialsConnection\Model\Read;

/**
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2020 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class WrongCredentialsCombination
{
    /** @var string */
    private $connectionCode;

    /** @var array */
    private $users;

    public function __construct(string $connectionCode)
    {
        $this->connectionCode = $connectionCode;
        $this->users = [];
    }

    public function users(): array
    {
        return $this->users;
    }

    public function addUser(string $username, \DateTime $dateTime): void
    {
        $this->users[$username] = $dateTime;
    }

    public function connectionCode(): string
    {
        return $this->connectionCode;
    }

    public function normalize(): array
    {
        $users = [];
        foreach ($this->users as $username => $date) {
            $users[] = ['username' => $username, 'date' => $date->format('c')];
        }

        return [
            'code' => $this->connectionCode,
            'users' => $users
        ];
    }
}