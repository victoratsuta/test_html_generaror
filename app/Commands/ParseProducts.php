<?php

namespace App\Commands;

use App\Services\HtmlBuilder;
use App\Services\IerarhieBuilder;
use App\Services\ParserCsv;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ParseProducts extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate HTML from .csv data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start Parsing');

        $csvParser = new ParserCsv();
        $products = $csvParser->get(ParserCsv::FILE_NAME_PRODUCTS);
        $groups = $csvParser->get(ParserCsv::FILE_NAME_GROUPS);

        $objectBuilder = new IerarhieBuilder($products, $groups);
        $object = $objectBuilder->build();

        $htmlBuilder = new HtmlBuilder($object);

        $html =  $htmlBuilder->build();

        echo $html , PHP_EOL;

    }
}
