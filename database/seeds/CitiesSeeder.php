<?php

use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
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
        INSERT INTO `cities` (`id`, `code`, `name`, `active`, `order`, `country_id`, `created_at`, `updated_at`) VALUES
        (1,	'ANTR',	'Антрацит, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-07-11 09:13:04'),
        (2,	'ASTN',	'Астана, Казахстан',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (3,	'BAKU',	'Баку, Азербайджан',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-08-07 12:50:29'),
        (4,	'BRN',	'Брянск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (5,	'VINN',	'Винница, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (6,	'VLGD',	'Волгоград, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (7,	'GOMEL',	'Гомель, Беларусь',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (8,	'HGKG',	'Гонконг, Китай',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (9,	'GUAN',	'Гуанчжоу, Китай',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (10,	'DNPR',	'Днепр, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (11,	'DNT',	'Донецк, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (12,	'DUBAI',	'Дубай',	1,	5,	5,	'2019-06-16 10:00:28',	'2019-07-10 12:58:50'),
        (13,	'EKB',	'Екатеринбург, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (14,	'ERVN',	'Ереван, Армения',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (15,	'ZHYTO',	'Житомир, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (16,	'ZAP',	'Запорожье, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (17,	'IVFR',	'Ивано-Франковск, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (18,	'IZHV',	'Ижевск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (19,	'IRK',	'Иркутск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (20,	'KZN',	'Казань, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (21,	'KEM',	'Кемерово, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (22,	'KIEV',	'Киев',	1,	1,	1,	'2019-06-16 10:00:28',	'2019-09-02 22:01:21'),
        (23,	'KISH',	'Кишинев, Молдова',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (24,	'KST',	'Костанай, Казахстан',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (25,	'KRAM',	'Краматорск, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (26,	'KRASN',	'Краснодар, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (27,	'KRSK',	'Красноярск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (28,	'KRMN',	'Кременчуг, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (29,	'KRVR',	'Кривой Рог, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (30,	'KROP',	'Кропивницкий, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (31,	'KURSK',	'Курск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (32,	'LNDN',	'Лондон, Великобритания',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (33,	'LOSAN',	'Лос-Анджелес, США',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (34,	'LUTSK',	'Луцк, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (35,	'LVOV',	'Львов, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (36,	'MANAM',	'Манама, Бахрейн',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (37,	'MRPL',	'Мариуполь, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (38,	'MELIT',	'Мелитополь, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (39,	'MINSK',	'Минск, Беларусь',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (40,	'MSK',	'Москва',	1,	2,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (41,	'NABCH',	'Набережные Челны, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (42,	'NNOV',	'Нижний Новгород, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (43,	'MYKL',	'Николаев, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (44,	'NVKZN',	'Новокузнецк, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (45,	'NSK',	'Новосибирск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (46,	'NYC',	'Нью-Йорк, США',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (47,	'ODS',	'Одесса, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (48,	'OMSK',	'Омск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (49,	'BEIJ',	'Пекин, Китай',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (50,	'PERM',	'Пермь, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (51,	'POLT',	'Полтава, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (52,	'PTGR',	'Пятигорск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (53,	'RIGA',	'Рига, Латвия',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (54,	'RIVNE',	'Ровно, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (55,	'RSND',	'Ростов-на-Дону, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (56,	'SMR',	'Самара, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (57,	'SPB',	'Санкт-Петербург',	1,	3,	2,	'2019-06-16 10:00:28',	'2019-06-24 14:01:45'),
        (58,	'SIMF',	'Симферополь, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (59,	'SOCHI',	'Сочи, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (60,	'STAV',	'Ставрополь, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (61,	'STAM',	'Стамбул',	1,	4,	6,	'2019-06-16 10:00:28',	'2019-07-10 12:58:33'),
        (62,	'STPNK',	'Степанакерт, Армения',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (63,	'SUMY',	'Сумы, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (64,	'TGN',	'Таганрог, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (65,	'TALLN',	'Таллин, Эстония',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (66,	'TBIL',	'Тбилиси, Грузия',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (67,	'TEHR',	'Тегеран, Иран',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (68,	'TERNO',	'Тернополь, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (69,	'TULA',	'Тула, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (70,	'UZHH',	'Ужгород, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (71,	'HRK',	'Харьков, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (72,	'KHERS',	'Херсон, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (73,	'HMLN',	'Хмельницкий, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (74,	'CHEB',	'Чебоксары, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (75,	'CHEL',	'Челябинск, Россия',	0,	100,	2,	'2019-06-16 10:00:28',	'2019-07-11 09:11:48'),
        (76,	'CHERK',	'Черкассы, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (77,	'CHERN',	'Черновцы, Украина',	0,	100,	1,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (78,	'EKIB',	'Экибастуз, Казахстан',	0,	100,	4,	'2019-06-16 10:00:28',	'2019-06-16 10:00:28'),
        (186,	'Cashless',	'Cashless',	1,	100,	4,	NULL,	NULL);
        ");

    }
}
