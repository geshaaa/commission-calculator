<?php

namespace App\Helper;

trait CheckDateWeek
{
    /**
     * @param string $firstDate
     * @param string $secondDate
     * @return bool
     * @throws \Exception
     */
    private function checkIfTwoDatesAreInSameWeek(string $firstDate, string $secondDate): bool
    {
        $firstDate = new \DateTime($firstDate);
        $secondDate = new \DateTime($secondDate);

        $dayOfWeek = $secondDate->format('w');

        // Second date will always be the earlier date so if is Sunday we can keep it same date
        $daysTillNextWeek = $dayOfWeek == 0 ? 0 : (7 - $dayOfWeek);

        $secondDate->modify("+$daysTillNextWeek days midnight");

        return $firstDate->getTimestamp() <= $secondDate->getTimestamp();
    }

}