<?php
use PHPUnit\Framework\TestCase;

final class CsvReaderTest extends TestCase
{

    const BIGFILE_LINES_AMOUNT = 10000 * 100;

    public static function setUpBeforeClass()
    {

        $generateRandomString = function ($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        };

        $file = fopen(__DIR__ . "/BIGFILE.csv","w");

        for ($i=0; $i< self::BIGFILE_LINES_AMOUNT; $i++) {

            $line = [$i, 'data_' + $i , $generateRandomString(), $generateRandomString(), $generateRandomString(), $generateRandomString() ];
            fputcsv($file,$line);
        }
        fclose($file);

    }

    public static function tearDownAfterClass()
    {
        unlink(__DIR__ . "/BIGFILE.csv");
    }

    public function testThrowsExceptionWhenInputFileDoesNotExsists() {

        $this->expectExceptionMessage('Error opening file.');
        CsvReader::fromFile('thereIsNoFile.csv');
    }

    public function testCanByUseWhenFileExists() {

        $this->assertInstanceOf(
            CsvReader::class,
            CsvReader::fromFile(__DIR__. '/sample.csv')
        );
    }

    public function testReadingLinesFromEmptyFile() {

        $v= CsvReader::fromFile(__DIR__. '/sample.csv')->getData();

        $this->assertInternalType('array', $v);
        $this->assertCount(0, $v);
    }


    public function testReadingLinesFromFileWith10LinesIgnoringHeader() {

        $v= CsvReader::fromFile(__DIR__. '/sample10Lines.csv')->getData();

        $this->assertInternalType('array', $v);
        $this->assertCount(11, $v);

    }


    public function testReadingLinesFromBigFileIgnoringHeader() {

        $v= CsvReader::fromFile(__DIR__. '/BIGFILE.csv')->getData();

        $this->assertInternalType('array', $v);
        $this->assertCount(self::BIGFILE_LINES_AMOUNT, $v);






    }


    public function testReadingLinesFromFileWith10LinesPlusHeader() {

        $v=CsvReader::fromFile(__DIR__. '/sample10Lines.csv', $firstLineIsHeader = true);
        $data= $v->getData();

        $this->assertInternalType('array', $data);
        $this->assertCount(10, $data);

        $sampleRow = $data[0];

        $this->assertEquals(
            ['ordinal', 'sampleData', 'colWithEmptyValues'],
            array_keys($sampleRow)
        );

        $header = $v->getHeader();

        $this->assertEquals(
            ['ordinal', 'sampleData', 'colWithEmptyValues'],
            $header
        );
    }

//    public function testReadingLinesFromFileWith10LinesPlusHeader() {
//
//        $v=CsvReader::fromFile(__DIR__. '/sample10Lines.csv', $firstLineIsHeader = true);
//        $data= $v->getData();
//
//        $this->assertInternalType('array', $data);
//        $this->assertCount(10, $data);
//
//        $sampleRow = $data[0];
//
//        $this->assertEquals(
//            ['ordinal', 'sampleData', 'colWithEmptyValues'],
//            array_keys($sampleRow)
//        );
//
//        $header = $v->getHeader();
//
//        $this->assertEquals(
//            ['ordinal', 'sampleData', 'colWithEmptyValues'],
//            $header
//        );
//
//
//    }





//    public function testCanBeCreatedFromValidEmailAddress()
//    {
//        $this->assertInstanceOf(
//            Email::class,
//            Email::fromString('user@example.com')
//        );
//    }
//
//    public function testCannotBeCreatedFromInvalidEmailAddress()
//    {
//        $this->expectException(InvalidArgumentException::class);
//
//        Email::fromString('invalid');
//    }
//
//    public function testCanBeUsedAsString()
//    {
//        $this->assertEquals(
//            'user@example.com',
//            Email::fromString('user@example.com')
//        );
//    }
}





