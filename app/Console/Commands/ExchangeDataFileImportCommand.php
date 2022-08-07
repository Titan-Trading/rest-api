<?php

namespace App\Console\Commands;

use App\Models\Trading\ExchangeDataset;
use App\Models\Trading\ExchangeKlineData;
use App\Models\Trading\Symbol;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

ini_set('memory_limit', '1024M');

class ExchangeDataFileImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:exchange_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import exchange data from csv files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $supportedYears = [
            '2019',
            '2020',
            '2021',
            '2022'
        ];

        try {
            // get all exchange symbols
            $exchangeSymbols = Symbol::whereHas('exchanges', function($q) {
                $q->whereName('KuCoin');
            })->with(['exchanges', 'targetCurrency', 'baseCurrency'])->get();

            foreach($exchangeSymbols as $symbol) {
                $exchange = $symbol->exchanges[0];

                $exchangeSymbol = str_replace('[target]', $symbol->targetCurrency->name, $exchange->symbol_template);
                $exchangeSymbol = str_replace('[base]', $symbol->baseCurrency->name, $exchangeSymbol);

                // loop through supported years
                foreach($supportedYears as $year) {
                    $fileName = 'Kucoin_' . $symbol->targetCurrency->name . $symbol->baseCurrency->name . '_' . $year . '_minute.csv';
                    $filePath = storage_path('files/' . $fileName);
                    
                    // try to find the a csv file for symbol
                    if(file_exists($filePath)) {
                        $this->info('Dataset file found: ' . $fileName);

                        $fileHandle = fopen($filePath, 'r');

                        $sourceTitleRow = fgetcsv($fileHandle, 1024);
                        $headerRow = fgetcsv($fileHandle, 1024);

                        // find dataset or create one
                        $dataset = ExchangeDataset::whereSource('cryptodatadownload.com')
                            ->whereExchangeId($exchange->id)
                            ->whereSymbolId($symbol->id)
                            ->whereInterval('1m')
                            ->where('year', $year)
                            ->first();
                        if(!$dataset) {
                            $dataset = new ExchangeDataset();
                            $dataset->creator_id = 2;
                            $dataset->exchange_id = $exchange->id;
                            $dataset->symbol_id = $symbol->id;
                            $dataset->interval = '1m';
                            $dataset->year = $year;
                            $dataset->name = $exchange->name . ' ' . $symbol->targetCurrency->name . '/' . $symbol->baseCurrency->name . ' 1m';
                            $dataset->source = 'cryptodatadownload.com';
                            $dataset->periods = 0;
                            $dataset->started_at = Carbon::now();
                            $dataset->ended_at = Carbon::now();
                            $dataset->save();
                        }
                        else if($dataset->periods) {
                            $this->info('already found periods for the symbol, skipping');
                            continue;
                        }

                        $startedAt = null;
                        $endedAt = null;

                        $lastRowProcessed = [];
                        $dataRows = [];
                        $rowsInserted = 0;

                        $rowCount = 1;

                        // read csv file
                        while($row = fgetcsv($fileHandle, 1024)) {

                            if(count($row) !== count($headerRow)) {
                                $this->info('Invalid row: column count does not match header column count');
                                continue;
                            }

                            $timestamp = (int) $row[0];
                            $date = $row[1];
                            $importedSymbol = $row[2];
                            $open = $row[3];
                            $high = $row[4];
                            $low = $row[5];
                            $close = $row[6];
                            $volume = $row[7];
                            $baseVolume = $row[8];

                            // first row grab ended at date
                            if($rowCount === 1) {
                                $endedAt = $date;
                            }

                            // create dataset data row
                            $dataRows[] = [
                                'creator_id' => 2,
                                'exchange_dataset_id' => $dataset->id,
                                'exchange_id' => $exchange->id,
                                'symbol_id' => $symbol->id,
                                'interval' => '1m',
                                'open' => $open,
                                'high' => $high,
                                'low' => $low,
                                'close' => $close,
                                'volume' => $volume,
                                'base_volume' => $baseVolume,
                                'timestamp' => $timestamp,
                                'date' => $date,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];

                            // add every 1000 rows
                            if(count($dataRows) === 1000) {
                                $inserted = ExchangeKlineData::insert($dataRows);
                                if($inserted) {
                                    $this->info('Inserted ' . count($dataRows) . ' records into ' . $dataset->name);
                                    $rowsInserted += count($dataRows);
                                }

                                $dataRows = [];
                            }

                            $lastRowProcessed = $row;
                            $rowCount++;
                        }

                        // add all dataset rows that are left over
                        $inserted = ExchangeKlineData::insert($dataRows);
                        if($inserted) {
                            $this->info('Inserted ' . count($dataRows) . ' records into ' . $dataset->name);
                            $rowsInserted += count($dataRows);
                        }

                        // nothing was added
                        if($rowsInserted) {
                            // get started at date from last row that was processed
                            $startedAt = $lastRowProcessed[1];

                            // update dataset started at, ended at and name
                            $dataset->name = $dataset->name . ' ' . $startedAt . ' to ' . $endedAt;
                            $dataset->started_at = $startedAt;
                            $dataset->ended_at = $endedAt;
                            $dataset->periods = $rowsInserted;
                            $dataset->save();
                        }
                        else {
                            $dataset->delete();
                        }
                    }
                }
            }

            $this->info('Import completed');
        }
        catch(Throwable $ex) {
            dd($ex);
        }

        return Command::SUCCESS;
    }
}
