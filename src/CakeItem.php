<?php
namespace TwoTogether;

//defines 
class CakeItem
{
    public bool $small = false;
    public bool $large = false;
    public array $names = [];

    public function setSmall(bool $small = true): void
    {
        $this->small = $small;
        $this->large = false;
    }

    public function setLarge(bool $large = true): void
    {
        $this->large = $large;
        $this->small = false;
    }

    public function addName(string $name, array $list = []): void
    {
        $this->names[] = $name;

        if (!empty($list)) $this->names = array_merge($list, $this->names);

        $this->names = array_unique($this->names);
    }

    public function toArray(): array
    {
        return [
            'small' => $this->small,
            'large' => $this->large,
            'names' => $this->names,
        ];
    }
}