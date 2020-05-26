<?php
declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;
use stdClass;

class User implements JsonSerializable {
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $role_id;

    /**
     * @var int
     */
    private $role_level;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $family_name;

    /**
     * @var string
     */
    private $given_name;

    /**
     * @var string
     */
    private $picture;

    /**
     * User constructor.
     * @param stdClass $gUser
     * @param array $role
     */
    public function __construct(stdClass $gUser, array $role) {
       $this->id = $gUser->id;
       $this->role_id = $role['id'];
       $this->role_level = $role['level'];
       $this->email = $gUser->email;
       $this->family_name = $gUser->family_name;
       $this->given_name = $gUser->given_name;
       $this->picture = $gUser->picture;
    }

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'username' => $this->given_name. ' '.$this->family_name,
            'role' => [
                'id' => $this->role_id,
                'level' => $this->role_level
            ]
        ];
    }

    /**
     * @return array
     */
    public function export() {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'family_name' => $this->family_name,
            'given_name' => $this->given_name,
            'picture' => $this->picture,
            'role' => [
                'id' => $this->role_id,
                'level' => $this->role_level
            ]
        ];
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRoleId(): int {
        return $this->role_id;
    }

    /**
     * @return int
     */
    public function getRoleLevel(): int {
        return $this->role_level;
    }

    /**
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string {
        return $this->family_name;
    }

    /**
     * @return string
     */
    public function getGivenName(): string {
        return $this->given_name;
    }

    /**
     * @return string
     */
    public function getPicture(): string {
        return $this->picture;
    }
}
