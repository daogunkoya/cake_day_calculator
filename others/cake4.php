<?php
class CakeScheduler {
    private $birthdays;
    private $cakeDays;
    private $officeClosedDays;

    public function __construct($birthdays) {
        $this->birthdays = $birthdays;
        $this->cakeDays = array();
        $this->officeClosedDays = array("Saturday", "Sunday", "December 25", "December 26", "January 1");
    }

    public function scheduleCakes() {
        $this->cakeDays = array();
        foreach ($this->birthdays as $name => $birthday) {
            $nextCakeDay = $this->getNextCakeDay($birthday);
            $this->cakeDays[$nextCakeDay][] = $name;
        }
        return $this->cakeDays;
    }

    private function getNextCakeDay($birthday) {
        $nextCakeDay = clone $birthday;
        // If birthday falls on an office closed day, move to next working day
        while (in_array($nextCakeDay->format("l, F j"), $this->officeClosedDays)) {
            $nextCakeDay->modify("+1 day");
        }
        // If the office is closed on the next working day after the birthday, move to the next available working day
        if (in_array($nextCakeDay->modify("+1 day")->format("l, F j"), $this->officeClosedDays)) {
            $nextCakeDay->modify("+1 weekday");
        }
        // If two or more cakes days coincide, provide one large cake to share
        if (array_key_exists($nextCakeDay->format("Y-m-d"), $this->cakeDays)) {
            $nextCakeDay->modify("+1 day");
            $nextCakeDay = $this->getNextCakeDay($nextCakeDay);
        }
        // If there is to be cake two days in a row, provide one large cake on the second day
        if (array_key_exists($nextCakeDay->modify("-1 day")->format("Y-m-d"), $this->cakeDays)) {
            $nextCakeDay->modify("+1 day");
            $nextCakeDay = $this->getNextCakeDay($nextCakeDay);
        }
        // For health reasons, the day after each cake must be cake-free
        $nextCakeDayClone = clone $nextCakeDay;
        $nextCakeDayClone->modify("+1 day");
        if (in_array($nextCakeDayClone->format("l, F j"), $this->officeClosedDays)) {
            $nextCakeDayClone->modify("+1 weekday");
        }
        $cakeFreeDays = array($nextCakeDay->format("Y-m-d"), $nextCakeDayClone->format("Y-m-d"));
        foreach ($cakeFreeDays as $cakeFreeDay) {
            if (array_key_exists($cakeFreeDay, $this->cakeDays)) {
                $nextCakeDayClone->modify("+1 day");
                $nextCakeDayClone = $this->getNextCakeDay($nextCakeDayClone);
            }
        }
        return $nextCakeDayClone;
    }
}






function getCakeDate($employeeStartDate, $employeeBirthdayDate, $currentDate) {
  $cakeDays = array();
  $cakeDays[] = strtotime(date('Y') . '-01-01'); // New Year's Day
  $cakeDays[] = strtotime(date('Y') . '-12-25'); // Christmas Day
  $cakeDays[] = strtotime(date('Y') . '-12-26'); // Boxing Day

  // Check if the employee's birthday falls on a weekend
  $birthdayWeekday = date('N', $employeeBirthdayDate);
  if ($birthdayWeekday == 6) { // Saturday
    $employeeBirthdayDate = strtotime('next monday', $employeeBirthdayDate);
  } else if ($birthdayWeekday == 7) { // Sunday
    $employeeBirthdayDate = strtotime('next tuesday', $employeeBirthdayDate);
  }

  // Check if the employee's birthday falls on a cake day
  $employeeBirthdayDay = date('j', $employeeBirthdayDate);
  if (in_array($employeeBirthdayDate, $cakeDays)) {
    $employeeBirthdayDate = strtotime('next working day', $employeeBirthdayDate);
  }

  // Add the employee's birthday to the cake days
  $cakeDays[] = $employeeBirthdayDate;

  // Sort the cake days in ascending order
  sort($cakeDays);

  // Check if there are any consecutive cake days
  for ($i = 0; $i < count($cakeDays) - 1; $i++) {
    $currentCakeDay = $cakeDays[$i];
    $nextCakeDay = $cakeDays[$i + 1];
    if ($nextCakeDay - $currentCakeDay <= 86400) { // Less than or equal to one day
      // Remove the next cake day and add it to the current cake day
      unset($cakeDays[$i + 1]);
      $cakeDays[$i] = $nextCakeDay;

      // Check if the new cake day falls on a cake-free day
      $newCakeDay = strtotime('next working day', $nextCakeDay);
      if (in_array($newCakeDay, $cakeDays)) {
        unset($cakeDays[array_search($newCakeDay, $cakeDays)]);
      } else {
        $cakeDays[$i] = $newCakeDay;
      }
    }
  }

  // Check if there are any cake-free days after each cake day
  $cakeDaysWithBreaks = array();
  foreach ($cakeDays as $cakeDay) {
    $cakeDaysWithBreaks[] = $cakeDay;
    $cakeFreeDay = strtotime('next working day', $cakeDay);
    $cakeDaysWithBreaks[] = $cakeFreeDay;
  }

  // Remove any duplicate cake days or cake-free days
  $cakeDaysWithBreaks = array_unique($cakeDaysWithBreaks);

  // Check if there are any cake days in the future
  foreach ($cakeDaysWithBreaks as $cakeDay) {
    if ($cakeDay > $currentDate && $cakeDay < strtotime('+1 week', $currentDate)) {
      return $cakeDay;
    }
  }

  // No cake days in the next week
  return false;
}

$employeeStartDate = strtotime('2022-01-01');
$employeeBirthdayDate = strtotime('1990-04-08');
//$currentDate = strtotime('2023



