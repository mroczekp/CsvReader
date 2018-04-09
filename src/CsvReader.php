<?php
final class CsvReader
{
    private $firstLineIsHeader;

    protected $filePath;

    /** @var resource  */
    protected $file;

    private function __construct($filePath, $firstLineIsHeader)
    {

        $this->filePath = $filePath;

        try {
            $file     = fopen($filePath, "r");
        }
        catch (Exception $e) {

                throw new Exception('Error opening file.');
        }

        $this->firstLineIsHeader = $firstLineIsHeader;
        $this->file = $file;
    }

    public function getHeader() {

        rewind($this->file);
        $header = fgetcsv($this->file, 1000, ",");
        return $header;
    }

    public function getData() {

        rewind($this->file);

        if ($this->firstLineIsHeader) {

            $data = [];

            $header = $this->getHeader();

            $f = function($cols, $v) {

                $newValue = [];

                foreach ($cols as $index => $nameCol) {

                    if (! isset($v[$index]) ) {

                        $newValue[ $nameCol ] = null;
                        continue;
                    }

                    $newValue[ $nameCol ] = $v[$index];
                }

                return $newValue;

            };

            while (($v = fgetcsv($this->file, 1000, ",")) !== FALSE) {

                $transformedV = call_user_func($f, $header, $v);

                $data[] = $transformedV;
            }

            return $data;
        }


        $data = [];

        while (($v = fgetcsv($this->file, 1000, ",")) !== FALSE) {
            $data[] = $v;
        }

        return $data;
    }

    public static function fromFile($filePath, $firstLineIsHeader = false)
    {
        return new self($filePath, $firstLineIsHeader);
    }



}
