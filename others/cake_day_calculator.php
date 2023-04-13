<?php
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

// InputParser class to parse the input file and create an array of Employee objects
class EmployeeBirthdayReader
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function readEmployees(): array
    {
        // $lines = file($this->filename, FILE_IGNORE_NEW_LINES);
        // $employees = [];

        // foreach ($lines as $line) {
        //     [$name, $dob] = explode(',', $line);
        //     $employees[] = new Employee($name, new DateTime($dob));
        // }

        // return $employees;


        if (!file_exists($this->filename)) {
            echo "File not found: $this->filename\n";
            exit(1);
        }
        $employees = [];
        $file = fopen($this->filename, 'r');
        while (($line = fgets($file)) !== false) {
            $fields = explode(',', trim($line));
            if (count($fields) !== 2) {
                echo "Invalid line: $line\n";
                continue;
            }
            $name = trim($fields[0]);
            $dob = trim($fields[1]);
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
                echo "Invalid date format: $dob\n";
                continue;
            }
            //$employees[] = ['name' => $name, 'dob' => $dob];
        $employees[] = new Employee($name, new DateTime($dob));
        }
        fclose($file);

         return $employees;



    }


    public function getEmployees(): array
    {
        $lines = file($this->filename, FILE_IGNORE_NEW_LINES);
        $employees = [];

        foreach ($lines as $line) {
            [$name, $dob] = explode(',', $line);
            $employees[] = new Employee($name, new DateTime($dob));
        }

        return $employees;
    }
}




class CakeItem {
    private bool $small = false;
    private bool $large = false;
    private array $names = [];

    public function setSmall(bool $small = true): void {
        $this->small = $small;
        $this->large = false;
    }

    public function setLarge(bool $large = true): void {
        $this->large = $large;
        $this->small = false;
    }

    public function addName(string $name, array $list = []): void {
        $this->names[] = $name;
       
        if(!empty($list)) $this->names = array_merge($list, $this->names);
        
        $this->names = array_unique($this->names);
    }

    public function toArray(): array {
        return [
            'small' => $this->small,
            'large' => $this->large,
            'names' => $this->names,
        ];
    }
}








class CakeDayCalculator
{
    private array $employees;
    private $cakeDays;
    public $officeClosedDays;

    public function __construct(array $employees)
    {
        $this->officeClosedDays = array("Saturday", "Sunday", "December 25", "December 26", "January 1");
        $this->cakeDays = [];
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

    private function getNextCakeDay(DateTime $nextBirthday, $name): string
    {
        $cakeItem = new CakeItem();
   	
        $cakeDay = $this->getNextWorkingDay($nextBirthday);

        if($this->isWeekendOrHoliday($cakeDay->modify("+1 day"))) $cakeDay = $this->getNextWorkingDay($cakeDay);
        
       
          // cake free day: check if the previous cake day is a consecutive working day
          if (!empty($this->cakeDays)) {
            $keys = array_keys($this->cakeDays);
            $prevCakeDay = end($keys);
            $prevCakeDay = new DateTime($prevCakeDay);
            $diff = $prevCakeDay->diff($cakeDay);
            
            if ($diff->days == 1 && !$this->isWeekendOrHoliday($cakeDay)) {
                $cakeDay = $cakeDay->modify("+1 day");
                $cakeDay = $this->getNextWorkingDay($cakeDay);
               // var_dump('in==', $cakeDay->format('d/m/Y') );
            }
        }

      

        $cakeDayStr = $cakeDay->format('Y-m-d');
       
        //set cake size and name associated to the cake
        $cakeItem->setSmall(true); 
        $cakeItem->addName($name);
        if (isset($this->cakeDays[$cakeDayStr])) {
            $cakeItem->setLarge(true);
            $cakeItem->addName($name, $this->cakeDays[$cakeDayStr]['names']);
        }
       
         $this->cakeDays[$cakeDayStr] = $cakeItem->toArray();

        return $cakeDayStr;
    }



    public function getNextWorkingDay($date) {
    
        while ($this->isWeekendOrHoliday($date)) {
            $date->modify("+1 day");
            $this->getNextWorkingDay($date);
            }
     
       return $date;
       
    }



    private function isWeekendOrHoliday(DateTime $date): bool
    {
        if (in_array($date->format("l"), $this->officeClosedDays) || in_array($date->format("F j"), $this->officeClosedDays)) {
           return true;
        }
        return false;
    }


    
    
 

}



class CakeRule{

    private $birthdays;
    private $cakeDays;
    private $officeClosedDays;
    private $nextCakeDay;
    public $cakeItem;

    public function __construct($birthdays,$cakeDays) {

        $this->birthdays = $birthdays;
        $this->cakeDays = $cakeDays;
        $this->officeClosedDays = array("Saturday", "Sunday", "December 25", "December 26", "January 1");
       // $this->nextCakeDay = clone $birthday;
    }
       public function getNextCakeDay($birthday) {
        //initialize cakeItem;
        $this->cakeItem = new CakeItem;

        $nextCakeDay = clone $birthday;
        $nextCakeDay = $this->getNextWorkingDay($nextCakeDay);
        $nextCakeDay = $this->getFirstAvailableWorkingDayAfterBirthday($nextCakeDay);
        
        $nextCakeDay = $this->resolveCakeDayConflicts($nextCakeDay);
        $nextCakeDay = $this->resolveConsecutiveCakeDays($nextCakeDay);
        $cakeFreeDays = $this->getCakeFreeDays($nextCakeDay);
        foreach ($cakeFreeDays as $cakeFreeDay) {
            $nextCakeDay = $this->getNextWorkingDay($nextCakeDay);
        }

        //$cakeDayStr = $cakeDay->format('Y-m-d');
        return [$nextCakeDay, $this->cakeItem, $this->cakeDays];
    }
    
    private function getNextWorkingDay($date) {
        while (in_array($date->format("l"), $this->officeClosedDays) || in_array($date->format("F j"), $this->officeClosedDays)) {
            $date->modify("+1 day");
            $this->getNextWorkingDay($date);
        }
        return $date;
    }
    
    private function getFirstAvailableWorkingDayAfterBirthday($date) {
        if (in_array($date->modify("+1 day")->format("l, F j"), $this->officeClosedDays)) {
            $date->modify("+1 weekday");
        }
        return $date;
    }
    
    private function resolveCakeDayConflicts($date) {
        if (array_key_exists($date->format("Y-m-d"), $this->cakeDays)) {
            $date->modify("+1 day");
            $date = $this->resolveCakeDayConflicts($date);
        }
        return $date;
    }
    
    private function resolveConsecutiveCakeDays($date) {
        if (array_key_exists($date->modify("-1 day")->format("Y-m-d"), $this->cakeDays)) {
            $date->modify("+1 day");
            $date = $this->resolveCakeDayConflicts($date);
        }
        return $date;
    }

    private function getCakeFreeDays($date) {
        $nextCakeDayClone = clone $date;
        $nextCakeDayClone->modify("+1 day");
        if (in_array($nextCakeDayClone->format("l, F j"), $this->officeClosedDays)) {
            $nextCakeDayClone->modify("+1 weekday");
        }
        $cakeFreeDays = array($date->format("Y-m-d"), $nextCakeDayClone->format("Y-m-d"));
        foreach ($cakeFreeDays as $cakeFreeDay) {
            if (array_key_exists($cakeFreeDay, $this->cakeDays)) {
                $nextCakeDayClone->modify("+1 day");
                $nextCakeDayClone = $this->getNextCakeDay($nextCakeDayClone);
                $newCakeFreeDays = $this->getCakeFreeDays($nextCakeDayClone);
                $cakeFreeDays = array_merge($cakeFreeDays, $newCakeFreeDays);
            }
        }
        return $cakeFreeDays;
    }


    

    
        
}





   

    class CakeDayWriter
    {
        private string $filename;
    
        public function __construct(string $filename)
        {
            $this->filename = $filename;
        }
    
        public function writeCakeDays(array $cakeDays): void
        {
            $fp = fopen($this->filename, 'w');
    
            fputcsv($fp, ['Date', 'Number of Small Cakes', 'Number of Large Cakes', 'Names of people getting cake']);
    
            foreach ($cakeDays as $date => $info) {
               // var_dump($info['names']);
                $smallCakes = $info['small'] ? count($info['names']) : 0;
                $largeCakes = $info['large'] ? 1 : 0;
    
                fputcsv($fp, [$date, $smallCakes, $largeCakes, implode(', ', $info['names'])]);
            }
    
            fclose($fp);
        }
    }




    // Instantiate the EmployeeBirthdayReader
$reader = new EmployeeBirthdayReader('employee_birthdays.txt');

// Get the list of employees
$employees = $reader->getEmployees();

// Instantiate the CakeDayCalculator
$calculator = new CakeDayCalculator($employees);

// Calculate the cake days for the current year
$cakeDays = $calculator->scheduleCakeDays(date('Y'));

//var_dump($cakeDays);

// // Instantiate the CakeDayWriter
 $writer = new CakeDayWriter('cake_days.csv');

// // Write the cake days to a CSV file
 $writer->writeCakeDays($cakeDays);

//var_dump($employees);







// Error handling: There is no error handling in the code for cases such as the input file not existing, or the input file being incorrectly formatted.

// Duplicate code: The readEmployees and getEmployees methods in the EmployeeBirthdayReader class are identical. One of them could be removed.

// The calculateCakeDay method: The calculateCakeDay method initializes an empty $cakeDays array but never uses it. This could be removed to simplify the code.

// The isWeekendOrHoliday method: The isWeekendOrHoliday method hard-codes the list of holidays. A better approach would be to provide a way to configure the list of holidays, or to use a third-party library to retrieve the list of holidays dynamically.

// The CakeDayWriter class: The CakeDayWriter class could be improved by checking whether the file exists and is writable before attempting to write to it. Additionally, it might be helpful to include a timestamp in the filename to avoid overwriting existing files.

// Code structure: It might be helpful to split the code into multiple files, each containing a single class or function. This would make the code easier to manage and test.

// Test coverage: It would be beneficial to add unit tests to the code to ensure that it works as intended in different scenarios.