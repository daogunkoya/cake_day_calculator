<?php

use PHPUnit\Framework\TestCase;
use TwoTogether\EmployeeBirthdayReader;
use TwoTogether\Employee;

class EmployeeBirthdayReaderTest extends TestCase
{
    public function testGetEmployees()
    {
        $reader = new EmployeeBirthdayReader('data/employee_birthdays.txt');
        $expectedEmployees = [
            new Employee('John', new DateTime('1990-05-03')),
            new Employee('Lisa', new DateTime('1988-12-21')),
            new Employee('Michael', new DateTime('1975-08-11')),
            new Employee('Sarah', new DateTime('1995-01-07')),
            new Employee('Steve', new DateTime('1992-10-14')),
            new Employee('Mary', new DateTime('1989-06-21')),
            new Employee('Dave', new DateTime('1986-12-25')),
            new Employee('Rob', new DateTime('1950-12-26')),
            new Employee('Sam', new DateTime('1988-07-08')),
            new Employee('Kate', new DateTime('1988-07-10')),
            new Employee('Alex', new DateTime('1988-07-17')),
            new Employee('Jan', new DateTime('1981-07-18')),
            new Employee('Peter', new DateTime('1985-07-19')),
        ];
        $this->assertEquals($expectedEmployees, $reader->readEmployees());
    }



    //Test case to check if the EmployeeBirthdayReader constructor sets the filename property correctly:
    public function testConstructor(): void
    {
        $filename = 'data/employees.txt';
        $reader = new EmployeeBirthdayReader($filename);

        $this->assertEquals($filename, $reader->filename);
    }





    public function testInvalidLines(): void
{
    $filename = 'data/employee_birthdays.txt';
    $reader = new EmployeeBirthdayReader($filename);

    $expectedEmployees = [
        new Employee('John', new DateTime('1990-05-03')),
        new Employee('Lisa', new DateTime('1988-12-21')),
        new Employee('Michael', new DateTime('1975-08-11')),
        new Employee('Sarah', new DateTime('1995-01-07')),
        new Employee('Steve', new DateTime('1992-10-14')),
        new Employee('Mary', new DateTime('1989-06-21')),
        new Employee('Dave', new DateTime('1986-12-25')),
        new Employee('Rob', new DateTime('1950-12-26')),
        new Employee('Sam', new DateTime('1988-07-08')),
        new Employee('Kate', new DateTime('1988-07-10')),
        new Employee('Alex', new DateTime('1988-07-17')),
        new Employee('Jan', new DateTime('1981-07-18')),
        new Employee('Peter', new DateTime('1985-07-19')),
    ];

    $this->assertEquals($expectedEmployees, $reader->readEmployees());
}
}
