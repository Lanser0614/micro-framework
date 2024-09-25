<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\App\Entity;

use Lanser\MyFreamwork\Core\Attributes\Column;
use Lanser\MyFreamwork\Core\Attributes\Entity;
use Lanser\MyFreamwork\Core\Attributes\PrimaryKey;

#[Entity(table: 'users')]
class User implements \JsonSerializable
{
    #[Column(columnName: 'id')]
    #[PrimaryKey]
    private ?string $id = null;
    #[Column(columnName: 'name')]
    private string $name;
    #[Column(columnName: 'email')]
    private string $email;
    #[Column(columnName: 'password')]
    private string $password;
    #[Column(columnName: 'phone')]
    private string $phoneNumber;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function jsonSerialize(): array
    {
       return [
           'id' => $this->id,
           'name' => $this->name,
           'email' => $this->email,
           'password' => $this->password,
           'phoneNumber' => $this->phoneNumber,
       ];
    }
}