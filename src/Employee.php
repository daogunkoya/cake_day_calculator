<?php
namespace TwoTogether;
use DateTime;

// Employee class to store name and date of birth
class Employee
{
    private string $name;
    private DateTime $dateOfBirth;

    public function __construct(string $name, DateTime $dateOfBirth)
    {
        $this->name = $name;
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }
}