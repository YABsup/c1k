<?php

use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
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
        INSERT INTO `countries` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
        (1,	'Украина',	1,	'2019-07-05 11:08:19',	'2019-07-05 11:08:19'),
        (2,	'Россия',	1,	'2019-07-05 11:08:33',	'2019-07-05 11:08:33'),
        (3,	'Казахстан',	0,	'2019-07-05 11:11:14',	'2019-07-05 11:11:14'),
        (4,	'Неопределена',	1,	'2019-07-05 11:15:17',	'2019-07-05 11:15:17'),
        (5,	'ОАЭ',	1,	'2019-07-10 15:37:29',	'2019-07-10 15:37:29'),
        (6,	'Турция',	1,	'2019-07-10 15:38:23',	'2019-07-10 15:38:23');
        ");
    }
}
