<?php

use Illuminate\Database\Seeder;

class PriceProvidersSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        //

        DB::statement("
        INSERT INTO `price_providers` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
        (1,	'privat24cash',	'privat24cash',	NULL,	NULL),
        (2,	'binance',	'binance',	NULL,	NULL),
        (3,	'privat24card',	'privat24card',	NULL,	NULL),
        (4,	'bitfinex',	'bitfinex',	NULL,	NULL),
        (5,	'bitstamp',	'bitstamp',	NULL,	NULL),
        (6,	'bittrex',	'bittrex',	NULL,	NULL),
        (7,	'forex',	'forex',	NULL,	NULL),
        (8,	'unexbank',	'unexbank',	NULL,	NULL),
        (10,	'bestchange',	'BestChange.ru',	NULL,	NULL),
        (11,	'one2one',	'Один к одному',	NULL,	NULL),
        (12,	'manual',	'Ручной ввод',	NULL,	NULL);
        ");
        DB::statement("

        UPDATE `price_providers` SET
        `id` = '0',
        `code` = 'manual',
        `name` = 'Ручной ввод',
        `created_at` = NULL,
        `updated_at` = NULL
        WHERE `code` = 'manual';

        ");

    }
}
