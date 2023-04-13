<?php

// Read in the employee information from a text file
$employees = file('employees.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Loop through each employee
foreach ($employees as $employee) {
    // Extract the employee name and birthdate
    list($name, $birthdate) = explode(',', $employee);
    
    // Parse the birthdate string into a DateTime object
    $birthdate = DateTime::createFromFormat('Y-m-d', trim($birthdate));
    
    // Determine the next birthday for the employee
    $next_birthday = clone $birthdate;
    $next_birthday->modify('+' . (date('Y') - $birthdate->format('Y')) . ' years');
    
    // Skip the employee's birthday if it falls on a weekend or holiday
    if (in_array($next_birthday->format('N'), [6, 7]) || in_array($next_birthday->format('m-d'), ['12-25', '12-26', '01-01'])) {
        continue;
    }
    
    // Determine the next working day if the employee's birthday falls on a holiday
    $next_working_day = clone $next_birthday;
    while (in_array($next_working_day->format('N'), [6, 7]) || in_array($next_working_day->format('m-d'), ['12-25', '12-26', '01-01'])) {
        $next_working_day->modify('+1 day');
    }
    
    // Determine if the employee gets a cake on their birthday
    $cake_day = null;
    if ($next_birthday->format('m-d') == date('m-d')) {
        $cake_day = $next_working_day;
    }
    
    // Determine if the employee gets a cake on the next working day if their birthday falls on a holiday
    if (!$cake_day && $next_working_day->format('m-d') == date('m-d')) {
        $cake_day = clone $next_working_day;
    }
    
    // Determine if the employee gets a cake on the day after their birthday
    $cake_free_day = clone $next_birthday;
    $cake_free_day->modify('+1 day');
    if (!$cake_day && !$cake_free_day->format('N') == 6 && !$cake_free_day->format('N') == 7) {
        $cake_day = $cake_free_day;
    }
    
    // Determine if the employee gets a cake on the next working day if the day after their birthday falls on a holiday
    if (!$cake_day && (in_array($cake_free_day->format('m-d'), ['12-25', '12-26', '01-01']) || in_array($cake_free_day->format('N'), [6, 7]))) {
        $next_working_day = clone $cake_free_day;
        while (in_array($next_working_day->format('N'), [6, 7]) || in_array($next_working_day->format('m-d'), ['12-25', '12-26', '01-01'])) {
            $next_working_day->modify('+1 day');
        }
        $cake_day = $next_working_day;
    }
    
    // Determine if the employee gets a cake the day after the cake day
    $$next_day = clone $cake_day;
    $next_day->modify('+1 day');
    
    if ($next_day->format('N') > 5 || in_array($next_day->format('Y-m-d'), $closed_dates)) {
        // The next day is a weekend or a holiday, postpone the cake to the next working day
        $next_day = get_next_working_day($next_day, $closed_dates);
    }
    
    if (isset($cake_days[$next_day->format('Y-m-d')])) {
        // There is already a cake scheduled for the next day, combine them into one large cake
        $cake_days[$next_day->format('Y-m-d')]['type'] = 'large';
    } else {
        // Schedule a small cake for the next day
        $cake_days[$next_day->format('Y-m-d')] = [
            'type' => 'small',
            'employee' => $employee,
        ];
    }
    
    // Determine if the day after the cake day is cake-free
    $cake_free_day = clone $next_day;
    $cake_free_day->modify('+1 day');
    if ($cake_free_day->format('N') > 5 || in_array($cake_free_day->format('Y-m-d'), $closed_dates)) {
        // The day after the cake day is a weekend or a holiday, postpone the cake-free day to the next working day
        $cake_free_day = get_next_working_day($cake_free_day, $closed_dates);
    }
    
    // Add the cake-free day to the schedule
    if (!isset($cake_days[$cake_free_day->format('Y-m-d')])) {
        $cake_days[$cake_free_day->format('Y-m-d')] = [
            'type' => 'cake-free',
            'employee' => null,
        ];
    }
    
