<?php
class Employee {
    private $name;
    private $birth_date;
    private $office_closed_dates;
    private $cake_days;

    public function __construct($name, $birth_date, $office_closed_dates, $cake_days) {
        $this->name = $name;
        $this->birth_date = $birth_date;
        $this->office_closed_dates = $office_closed_dates;
        $this->cake_days = $cake_days;
    }

    public function getNextCakeDay() {
        $now = new DateTime();
        $next_cake_day = null;

        // Check if today is a cake day
        if (in_array($now->format('Y-m-d'), $this->cake_days)) {
            $next_cake_day = $this->getNextWorkingDay($now);
        }

        // If not, check the upcoming cake days
        while ($next_cake_day == null) {
            $upcoming_cake_days = array_filter($this->cake_days, function($day) use ($now) {
                return $day >= $now->format('Y-m-d');
            });

            if (count($upcoming_cake_days) == 0) {
                break;
            }

            $next_cake_day = $this->getNextWorkingDay(new DateTime(reset($upcoming_cake_days)));
        }

        return $next_cake_day;
    }

    private function getNextWorkingDay($date) {
        $one_day = new DateInterval('P1D');
        $next_day = $date->add($one_day);

        // Check if the next day is a weekend or a closed office day
        while ($next_day->format('N') >= 6 || in_array($next_day->format('Y-m-d'), $this->office_closed_dates)) {
            $next_day = $next_day->add($one_day);
        }

        // Check if it's the employee's birthday
        if ($next_day->format('Y-m-d') == $this->birth_date) {
            // If the office is closed on the birthday, get the next working day off
            if (in_array($next_day->format('Y-m-d'), $this->office_closed_dates)) {
                $next_day = $this->getNextWorkingDay($next_day);
            } else {
                $next_cake_day = $next_day->format('Y-m-d');
                $next_day = $next_day->add($one_day);

                // Check if the next day is a cake day or a closed office day
                while ($next_day->format('N') >= 6 || in_array($next_day->format('Y-m-d'), $this->office_closed_dates) || in_array($next_day->format('Y-m-d'), $this->cake_days)) {
                    $next_day = $next_day->add($one_day);
                }

                // If the next day is a cake day, combine it with the employee's birthday cake
                if ($next_cake_day == $next_day->format('Y-m-d') && in_array($next_day->format('Y-m-d'), $this->cake_days)) {
                    $next_day = $next_day->add($one_day);
                }
            }
        }

        // Check if there's a cake the day before
        if (in_array($next_day->sub($one_day)->format('Y-m-d'), $this->cake_days)) {
            // Check if the next day is a cake day or a closed office day
            while ($next_day->lte($end_date)) {
                $is_cake_day = isCakeDay($next_day);

                if ($is_cake_day) {
                    $cake_days[] = $next_day->format('Y-m-d');
                }
                
                $next_day = getNextWorkingDay($next_day);
                
                if ($next_day->eq($employee_birthday)) {
                    if (!$is_office_open) {
                        $next_day = getNextWorkingDay($next_day);
                    } else {
                        $cake_days[] = $employee_birthday->format('Y-m-d');
                    }
                }
                
                // check for two or more cake days coinciding
                if (count($cake_days) > 1) {
                    $cake_days = [max($cake_days)];
                }
                
                // check for cake two days in a row
                if (count($cake_days) == 2) {
                    $first_day = Carbon::parse($cake_days[0]);
                    $second_day = Carbon::parse($cake_days[1]);
                    if ($second_day->diffInDays($first_day) == 1) {
                        $cake_days = [max($cake_days)];
                    }
                }
                
                // check for cake-free day
                $next_working_day = $this->getNextWorkingDay($next_day);
                $is_next_day_cake_day = isCakeDay($next_working_day);
                
                if (!$is_cake_day && $is_next_day_cake_day) {
                    $cake_days[] = $next_working_day->format('Y-m-d');
                    $next_day = $this->getNextWorkingDay($next_working_day);
                }
            }  
     
}
    // output cake days
            // echo "Cake days for $employee_name from {$start_date->format('Y-m-d')} to {$end_date->format('Y-m-d')}: ";
            // if (count($cake_days) == 0) {
            // echo "None";
            // } else {
            // echo implode(", ", $cake_days);
            // }
