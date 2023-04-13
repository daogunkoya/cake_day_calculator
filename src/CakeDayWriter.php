<?php
namespace TwoTogether;

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

        if (!$fp) {
            echo "Failed to open file: " . $this->filename . "\n";
            return;
        }


        fputcsv($fp, ['Date', 'Number of Small Cakes', 'Number of Large Cakes', 'Names of people getting cake']);

        foreach ($cakeDays as $date => $info) {
            // var_dump($info['names']);
            $smallCakes = $info['small'] ? count($info['names']) : 0;
            $largeCakes = $info['large'] ? 1 : 0;

            fputcsv($fp, [$date, $smallCakes, $largeCakes, implode(', ', $info['names']) ]);
        }

        fclose($fp);
    }
}
