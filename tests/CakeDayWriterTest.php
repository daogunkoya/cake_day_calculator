<?php

use PHPUnit\Framework\TestCase;
use TwoTogether\CakeDayWriter;


class CakeDayWriterTest extends TestCase
{
    public function testWriteCakeDays(): void
    {
        $filename = __DIR__ ."/output/cake_days.csv";


        if (!is_dir(__DIR__ . '/output')) {
            mkdir(__DIR__ . '/output');
        }

        

        $cakeDays = [
            '2023-04-10' => [
                'small' => true,
                'large' => false,
                'names' => ['John Doe', 'Jane Doe'],
            ],
            '2023-04-11' => [
                'small' => false,
                'large' => true,
                'names' => ['Bob Smith'],
            ],
        ];

        $writer = new CakeDayWriter($filename);
        $writer->writeCakeDays($cakeDays);

        $this->assertFileExists($filename);

        $contents = file_get_contents($filename);
       
        $expected = 'Date,"Number of Small Cakes","Number of Large Cakes","Names of people getting cake"' . "\n";
        $expected .= '2023-04-10,2,0,"John Doe, Jane Doe"' . "\n";
        $expected .= '2023-04-11,0,1,"Bob Smith"' . "\n";





        $this->assertEquals($expected, $contents);

        unlink($filename);
    }
}
