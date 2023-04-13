<?php

namespace TwoTogether;

use DateTime;

// InputParser class to parse the input file and create an array of Employee objects
class EmployeeBirthdayReader
{
    public string $filename;

    public function __construct(string $filename)
    {
        try{
            $this->filename = $filename;
        }catch(\Exception $e){
                echo "something went wron with accessing the file". $e->getMessage() . PHP_EOL;
        }
                 
    }

    public function readEmployees(): array
    {



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