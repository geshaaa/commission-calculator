<?php
namespace Test\Unit\Parser;

use App\Helper\CsvParser;
use App\Helper\ParserFactory;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    private $parserFactory;

    public function setUp()
    {
        $this->parserFactory = new ParserFactory();
    }

    /**
     * @test
     *
     * @param string $file
     * @param string $expectedParserClass
     *
     * @dataProvider parserFilesProvider
     */
    public function happyPath(string $file, string $expectedParserClass)
    {
        $actualParser = $this->parserFactory->getParser($file);

        self::assertSame(get_class($actualParser), $expectedParserClass);
    }

    public function parserFilesProvider()
    {
        return [
            'csv file' => [
                'file' =>  __DIR__ . '/fixtures/request.csv',
                'expectedParserClass' => CsvParser::class
            ]
        ];
    }

    /**
     * @test
     *
     * @param string $file
     *
     * @dataProvider notValidExtensionsProvider
     * @expectedException \Exception
     * @expectedExceptionMessage File format is not supported
     */
    public function notValidExtension(string $file)
    {
        $parser = $this->parserFactory->getParser($file);
    }

    public function notValidExtensionsProvider()
    {
        return [
            'txt file' => [
                'file' => 'test.txt'
            ],
            'no extension file' => [
                'file' => 'test'
            ]
        ];
    }
}