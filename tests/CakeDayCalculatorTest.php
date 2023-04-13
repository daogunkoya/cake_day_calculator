<?php

use PHPUnit\Framework\TestCase;
use TwoTogether\CakeDayCalculator;
use TwoTogether\CakeItem;
use TwoTogether\Employee;

class CakeDayCalculatorTest extends TestCase
{
    public function testScheduleCakeDays()
    {

        $employees = [
            new Employee('John Doe', new DateTime('1980-01-01')),
            new Employee('Jane Smith', new DateTime('1985-02-14')),
            new Employee('Bob Johnson', new DateTime('1990-03-25')),
            new Employee('Alice Williams', new DateTime('1995-04-10')),
            new Employee('Dave Brown', new DateTime('1999-05-15')),
            new Employee('Mary Davis', new DateTime('2000-06-20')),
            new Employee('Tom Wilson', new DateTime('2005-07-31')),
        ];

        $calculator = new CakeDayCalculator($employees);
        $schedule = $calculator->scheduleCakeDays(2023);

        $this->assertCount(7, $schedule);
        $this->assertIsArray($schedule);
        $this->assertNotEmpty($schedule);

        foreach ($schedule as $date => $cakeData) {
            $this->assertIsString($date);
        }
    }




    public function testIsWeekendOrHoliday(): void
    {
        $calculator = new CakeDayCalculator([]);

        // test a weekend date
        $weekendDate = new DateTime('2023-04-15'); // a Saturday
        $this->assertTrue($calculator->isWeekendOrHoliday($weekendDate));

        // test a holiday date
        $holidayDate = new DateTime('2023-12-25'); // Christmas day
        $this->assertTrue($calculator->isWeekendOrHoliday($holidayDate));

        // test a regular workday date
        $workdayDate = new DateTime('2023-04-14'); // a Friday
        $this->assertFalse($calculator->isWeekendOrHoliday($workdayDate));
    }





    public function testGetNextWorkingDayReturnsWorkingDay()
    {
        // Create a CakeDayCalculator instance
        $calculator = new CakeDayCalculator([]);

        // Create a DateTime object representing a weekend day (Saturday)
        $date = new DateTime('2023-04-15');

        // Call the getNextWorkingDay method
        $nextWorkingDay = $calculator->getNextWorkingDay($date);

        // Assert that the getNextWorkingDay method returns a DateTime object representing a working day (Monday)
        $this->assertEquals('2023-04-17', $nextWorkingDay->format('Y-m-d'));
    }



    public function testGetNextWorkingDayReturnsSameDayIfWeekday()
    {
        // Create a CakeDayCalculator instance
        $calculator = new CakeDayCalculator([]);

        // Create a DateTime object representing a weekday (Tuesday)
        $date = new DateTime('2023-04-18');

        // Call the getNextWorkingDay method
        $nextWorkingDay = $calculator->getNextWorkingDay($date);

        // Assert that the getNextWorkingDay method returns the same DateTime object representing a weekday (Tuesday)
        $this->assertEquals('2023-04-18', $nextWorkingDay->format('Y-m-d'));
    }



    public function testGetNextWorkingDayReturnsNextDayIfHoliday()
    {
        // Create a CakeDayCalculator instance
        $calculator = new CakeDayCalculator([]);

        // Create a DateTime object representing a holiday (December 25)
        $date = new DateTime('2023-12-25');

        // Call the getNextWorkingDay method
        $nextWorkingDay = $calculator->getNextWorkingDay($date);

        // Assert that the getNextWorkingDay method returns a DateTime object representing the next working day (December 27)
        $this->assertEquals('2023-12-27', $nextWorkingDay->format('Y-m-d'));
    }







    public function testSetCakeSizeAndNames()
    {
        $cakeItem = new CakeItem();
        $calculator = new CakeDayCalculator([]);

        // Test when the cake day has not been set before
        $name = "John Doe";
        $cakeDay = new DateTime("2023-04-15");
        $calculator->setCakeSizeAndNames($cakeItem, $name, $cakeDay);

        $this->assertTrue($cakeItem->small);
        $this->assertFalse($cakeItem->large);
        $this->assertEquals([$name], $cakeItem->names);

        // Test when the cake day has been set before with only one person
        $name2 = "Jane Smith";
        $cakeItem2 = new CakeItem();
        $cakeItem2->setSmall(false);
        $cakeItem2->addName("Alice");
        $calculator->cakeDays = [
            "2023-04-15" => $cakeItem2->toArray()
        ];
        $calculator->setCakeSizeAndNames($cakeItem, $name, $cakeDay);

        $this->assertFalse($cakeItem->small);
        $this->assertTrue($cakeItem->large);
        //$this->assertEquals([$name, "Alice"], $cakeItem->names);

        // Test when the cake day has been set before with multiple people
        $name3 = "Bob Johnson";
        $cakeItem3 = new CakeItem();
        $cakeItem3->setSmall(false);
        $cakeItem3->addName("Alice");
        $cakeItem3->addName("Charlie");
        $calculator->cakeDays = [
            "2023-04-15" => $cakeItem3->toArray()
        ];
        $calculator->setCakeSizeAndNames($cakeItem, $name, $cakeDay);

        $this->assertFalse($cakeItem->small);
        $this->assertTrue($cakeItem->large);
        // $this->assertEquals([$name, "Alice", "Charlie"], $cakeItem->names);
    }



    public function testAddCakeDay()
    {
        $cakeDayCalculator = new CakeDayCalculator([]);

        // Create a sample CakeItem
        $cakeItem = new CakeItem();
        $cakeItem->setSmall(true);
        $cakeItem->addName('John');

        // Add the CakeItem to the cake days array
        $cakeDayStr = '2023-04-13';
        $cakeDayCalculator->addCakeDay($cakeDayStr, $cakeItem);

        // Assert that the CakeDayCalculator now contains the expected data
        $expectedResult = [
            $cakeDayStr => [
                'small' => true,
                'large' => false,
                'names' => ['John']
            ]
        ];

        $this->assertEquals($expectedResult, $cakeDayCalculator->cakeDays);
    }



    // public function testGetNextCakeDay(): void
    // {
    //     $cakeDayCalculator = new CakeDayCalculator([/* employees here */]);
    //     $cakeDayCalculator->officeClosedDays = ["Saturday", "Sunday", "December 25", "December 26", "January 1"];
        
    //     $nextBirthday = new DateTime("1990-04-23");
    //     $name = "John Doe";
    //     $result = $cakeDayCalculator->getNextCakeDay($nextBirthday, $name);
    //     $expectedResult = $cakeDayCalculator->calculateNextCakeDay($nextBirthday)->format('Y-m-d');
        
    //     $this->assertEquals($expectedResult, $result);
    // }
    


}
