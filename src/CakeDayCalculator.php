<?php

namespace TwoTogether;

use TwoTogether\CakeItem;


use DateTime;

class CakeDayCalculator
{
    private array $employees;
    public array $cakeDays = [];
    public $officeClosedDays = array("Saturday", "Sunday", "December 25", "December 26", "January 1");

    public function __construct(array $employees)
    {

        $this->employees = $employees;
    }

    public function scheduleCakeDays(int $currentYear): array
    {
        // $cakeDays = [];

        foreach ($this->employees as $employee) {
            $dob = $employee->getDateOfBirth();
            $name = $employee->getName();
            $nextBirthday = new DateTime($currentYear . '-' . $dob->format('m-d'));
            $this->getNextCakeDay($nextBirthday, $name);
        }

        return $this->cakeDays;
    }


    public function getNextCakeDay(DateTime $nextBirthday, $name): string
    {


        $cakeItem = new CakeItem();
        $nextCakeDay = $this->calculateNextCakeDay($nextBirthday);

        $this->setCakeSizeAndNames($cakeItem, $name, $nextCakeDay);
        $this->addCakeDay($nextCakeDay->format('Y-m-d'), $cakeItem);

        return $nextCakeDay->format('Y-m-d');
    }




    public function calculateNextCakeDay(DateTime $nextBirthday): DateTime
    {

        $cakeDay = $this->getNextWorkingDay($nextBirthday);

        if ($this->isWeekendOrHoliday($cakeDay->modify("+1 day"))) $cakeDay = $this->getNextWorkingDay($cakeDay);

        // cake free day: check if the previous cake day is a consecutive working day
        if (!empty($this->cakeDays)) {
            $keys = array_keys($this->cakeDays);
            $prevCakeDay = end($keys);
            $prevCakeDay = new DateTime($prevCakeDay);
            $diff = $prevCakeDay->diff($cakeDay);

            if ($diff->days == 1 && !$this->isWeekendOrHoliday($cakeDay)) {
                $cakeDay = $cakeDay->modify("+1 day");
                $cakeDay = $this->getNextWorkingDay($cakeDay);
            }
        }

        return $cakeDay;
    }


    //methods to handle setting the cake size and names.
    public function setCakeSizeAndNames(CakeItem $cakeItem, string $name, DateTime $cakeDay): void
    {
        if (isset($this->cakeDays[$cakeDay->format('Y-m-d')])) {
            $cakeItem->setLarge(true);
            $cakeItem->addName($name, $this->cakeDays[$cakeDay->format('Y-m-d')]['names']);
        } else {
            $cakeItem->setSmall(true);
            $cakeItem->addName($name);
        }
    }


    //a add cake day to cake list
    public function addCakeDay(string $cakeDayStr, CakeItem $cakeItem): void
    {
        $this->cakeDays[$cakeDayStr] = $cakeItem->toArray();
    }
    






    public function getNextWorkingDay($date)
    {

        while ($this->isWeekendOrHoliday($date)) {
            $date->modify("+1 day");
            $this->getNextWorkingDay($date);
        }

        return $date;
    }



    public function isWeekendOrHoliday(DateTime $date): bool
    {
        if (in_array($date->format("l"), $this->officeClosedDays) || in_array($date->format("F j"), $this->officeClosedDays)) {
            return true;
        }
        return false;
    }


    
}
