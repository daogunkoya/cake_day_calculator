#Cake Day Calculator
Cake Day Calculator is a command-line PHP script that helps you calculate the cake days for employees in your organization. The script takes a list of employees' birthdays and generates a schedule of cake days, including the size of the cake (small or large) and the names of the employees who have a cake on that day.

The following rules apply for cake days:

A small cake is provided on the employee's first working day after their birthday.
All employees get their birthday off.
The office is closed on weekends, Christmas Day, Boxing Day and New Year's Day.
If the office is closed on an employee's birthday, they get the next working day off.
If two or more cake days coincide, we instead provide one large cake to share.
If there is to be cake two days in a row, we instead provide one large cake on the second day.
For health reasons, the day after each cake must be cake-free. Any cakes due on a cake-free day are postponed to the next working day.
There is never more than one cake a day.
To use the script, you need to provide a list of employee birthdays in a text file. The script reads the file, calculates the cake days, and writes the output to a file. You can customize the output file location and format.

The script comes with a test suite that helps you ensure that the script is working as expected. You can run the test suite using PHPUnit.


##Requirements
PHP version 7.4 or higher
Composer

##Installation
Clone this repository or download the source code as a ZIP archive and extract it to a local folder.
Install the dependencies by running composer install from the project root folder.

##Usage
Create a text file named employee_birthdays.txt in the project root folder. Each line of the file should contain an employee's name, birthdate, and cake preference, separated by commas (,). The date format should be Y-m-d and the cake preference should be either small or large.


John Smith, 1988-01-01, small
Jane Doe, 1990-02-15, large
...

### Run the command php index.php 

from the project root folder to generate the cake day schedule.
The application will create a file named cake_days.csv in the output folder. This file will contain a list of all cake days with the employees and cake sizes assigned to each day.


##Testing
The application includes unit tests that can be run using PHPUnit. To run the tests, use the following command from the project root folder:

###  ./vendor/bin/phpunit tests/


## Project Structure
The project folder contains the following files and directories:

src/ - Contains the source code for the application.
index.php - The entry point for the application.
data/employee_birthdays.txt - A sample input file containing employee data.
output/ - A directory where the application writes the cake day schedule file.
data/ - A directory where the application stores data files.
tests/ - Contains the unit tests for the application.


##Class Structure
The application consists of the following classes:

Employee - Represents an employee with a name and birthdate.
EmployeeBirthdayReader - Reads employee data from a text file.
CakeItem - Represents a cake item with a size and associated employee names.
CakeDayCalculator - Calculates the schedule for cake days.
CakeDayWriter - Writes the schedule to a text file.
autoload.php - Autoloads the necessary classes for the application.
