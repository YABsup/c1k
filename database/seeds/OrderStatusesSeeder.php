<?php

use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
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

        INSERT INTO `order_statuses` (`id`, `name`, `desc`, `created_at`, `updated_at`) VALUES
        (1,	'new',	'Новая заявка',	NULL,	NULL),
        (2,	'inprogress',	'В обработке',	NULL,	NULL),
        (3,	'finish',	'Выполнена',	NULL,	NULL),
        (4,	'cancelled',	'Отменена',	NULL,	NULL);

        ");
    }
}
