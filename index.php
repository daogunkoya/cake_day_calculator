<?php
use TwoTogether\EmployeeBirthdayReader;
use TwoTogether\CakeDayCalculator;
use TwoTogether\CakeDayWriter;
require_once __DIR__ . '/vendor/autoload.php';


try {
    // Instantiate the EmployeeBirthdayReader
    $reader = new EmployeeBirthdayReader('data/employee_birthdays.txt');

    // Get the list of employees
    $employees = $reader->readEmployees();

    // Instantiate the CakeDayCalculator
    $calculator = new CakeDayCalculator($employees);

    // Calculate the cake days for the current year
    $cakeDays = $calculator->scheduleCakeDays(date('Y'));

    //var_dump($cakeDays);

    // // Instantiate the CakeDayWriter
    $writer = new CakeDayWriter('output/cake_days.csv');

    // // Write the cake days to a CSV file
    $writer->writeCakeDays($cakeDays);

    echo 'Cake days written to output/cake_days.csv.' . PHP_EOL;
} catch (\Exception $e) {
    echo 'An error occurred: ' . $e->getMessage() . PHP_EOL;
}







// Error handling: There is no error handling in the code for cases such as the input file not existing, or the input file being incorrectly formatted.

// Duplicate code: The readEmployees and getEmployees methods in the EmployeeBirthdayReader class are identical. One of them could be removed.

// The calculateCakeDay method: The calculateCakeDay method initializes an empty $cakeDays array but never uses it. This could be removed to simplify the code.

// The isWeekendOrHoliday method: The isWeekendOrHoliday method hard-codes the list of holidays. A better approach would be to provide a way to configure the list of holidays, or to use a third-party library to retrieve the list of holidays dynamically.

// The CakeDayWriter class: The CakeDayWriter class could be improved by checking whether the file exists and is writable before attempting to write to it. Additionally, it might be helpful to include a timestamp in the filename to avoid overwriting existing files.

// Code structure: It might be helpful to split the code into multiple files, each containing a single class or function. This would make the code easier to manage and test.

// Test coverage: It would be beneficial to add unit tests to the code to ensure that it works as intended in different scenarios.