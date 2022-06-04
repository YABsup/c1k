<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedCitiesFromBest extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'seed:cities';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

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
    * @return mixed
    */
    public function handle()
    {
        //
        $seed = [
            'AKT'=>'Актобе, Казахстан',
            'ALM'=>'Алма-Ата, Казахстан',
            'AMST'=>'Амстердам, Нидерланды',
            'ANAPA'=>'Анапа, Россия',
            'ANTW'=>'Антверпен, Бельгия',
            'ANTR'=>'Антрацит, Украина',
            'ARKH'=>'Архангельск, Россия',
            'ASTRA'=>'Астрахань, Россия',
            'BAKU'=>'Баку, Азербайджан',
            'BARC'=>'Барселона, Испания',
            'BER'=>'Берлин, Германия',
            'BRUS'=>'Брюссель, Бельгия',
            'BRN'=>'Брянск, Россия',
            'VALEN'=>'Валенсия, Испания',
            'WARS'=>'Варшава, Польша',
            'VILN'=>'Вильнюс, Литва',
            'VINN'=>'Винница, Украина',
            'VTB'=>'Витебск, Беларусь',
            'VVO'=>'Владивосток, Россия',
            'VLGD'=>'Волгоград, Россия',
            'VORON'=>'Воронеж, Россия',
            'HAMB'=>'Гамбург, Германия',
            'GLND'=>'Геленджик, Россия',
            'GOMEL'=>'Гомель, Беларусь',
            'HGKG'=>'Гонконг, Китай',
            'GRZ'=>'Грозный, Россия',
            'GUAN'=>'Гуанчжоу, Китай',
            'GUAQ'=>'Гуаякиль, Эквадор',
            'DJRB'=>'Джерба, Тунис',
            'DNPR'=>'Днепр, Украина',
            'DNT'=>'Донецк, Украина',
            'DRES'=>'Дрезден, Германия',
            'DUBAI'=>'Дубай, Объединенные Арабские Эмираты',
            'EKB'=>'Екатеринбург, Россия',
            'ERVN'=>'Ереван, Армения',
            'ZHYTO'=>'Житомир, Украина',
            'ZAP'=>'Запорожье, Украина',
            'IVFR'=>'Ивано-Франковск, Украина',
            'IZHV'=>'Ижевск, Россия',
            'IRK'=>'Иркутск, Россия',
            'ISFHN'=>'Исфахан, Иран',
            'YOLA'=>'Йошкар-Ола, Россия',
            'KZN'=>'Казань, Россия',
            'KLNG'=>'Калининград, Россия',
            'KLG'=>'Калуга, Россия',
            'KRGN'=>'Караганда, Казахстан',
            'KEM'=>'Кемерово, Россия',
            'KIEV'=>'Киев, Украина',
            'KIROV'=>'Киров, Россия',
            'KISH'=>'Кишинев, Молдова',
            'CPNH'=>'Копенгаген, Дания',
            'KST'=>'Костанай, Казахстан',
            'KOST'=>'Кострома, Россия',
            'KRAM'=>'Краматорск, Украина',
            'KRASN'=>'Краснодар, Россия',
            'KRSK'=>'Красноярск, Россия',
            'KRMN'=>'Кременчуг, Украина',
            'KRVR'=>'Кривой Рог, Украина',
            'KROP'=>'Кропивницкий, Украина',
            'KURSK'=>'Курск, Россия',
            'LARN'=>'Ларнака, Кипр',
            'LZIG'=>'Лейпциг, Германия',
            'LIMAS'=>'Лимасол, Кипр',
            'LPT'=>'Липецк, Россия',
            'LNDN'=>'Лондон, Великобритания',
            'LOSAN'=>'Лос-Анджелес, США',
            'LUH'=>'Луганск, Украина',
            'LUTSK'=>'Луцк, Украина',
            'LVOV'=>'Львов, Украина',
            'MGNT'=>'Магнитогорск, Россия',
            'MADR'=>'Мадрид, Испания',
            'MIAMI'=>'Майами, США',
            'MLG'=>'Малага, Испания',
            'MANAM'=>'Манама, Бахрейн',
            'MRPL'=>'Мариуполь, Украина',
            'MHKL'=>'Махачкала, Россия',
            'MELIT'=>'Мелитополь, Украина',
            'CDMX'=>'Мехико, Мексика',
            'MILAN'=>'Милан, Италия',
            'MINSK'=>'Минск, Беларусь',
            'MGL'=>'Могилев, Беларусь',
            'MSK'=>'Москва, Россия',
            'MUN'=>'Мюнхен, Германия',
            'NABCH'=>'Набережные Челны, Россия',
            'NNOV'=>'Нижний Новгород, Россия',
            'MYKL'=>'Николаев, Украина',
            'NICE'=>'Ницца, Франция',
            'NVKZN'=>'Новокузнецк, Россия',
            'NOVOR'=>'Новороссийск, Россия',
            'NSK'=>'Новосибирск, Россия',
            'ASTN'=>'Нур-Султан, Казахстан',
            'NYC'=>'Нью-Йорк, США',
            'ODS'=>'Одесса, Украина',
            'OMSK'=>'Омск, Россия',
            'ORYOL'=>'Орел, Россия',
            'OREN'=>'Оренбург, Россия',
            'ORLND'=>'Орландо, США',
            'OSLO'=>'Осло, Норвегия',
            'PARIS'=>'Париж, Франция',
            'PAPH'=>'Пафос, Кипр',
            'BEIJ'=>'Пекин, Китай',
            'PENZA'=>'Пенза, Россия',
            'PERM'=>'Пермь, Россия',
            'POLT'=>'Полтава, Украина',
            'PRAG'=>'Прага, Чехия',
            'PTGR'=>'Пятигорск, Россия',
            'RIGA'=>'Рига, Латвия',
            'ROME'=>'Рим, Италия',
            'RIVNE'=>'Ровно, Украина',
            'RSND'=>'Ростов-на-Дону, Россия',
            'RTRD'=>'Роттердам, Нидерланды',
            'RZN'=>'Рязань, Россия',
            'SMR'=>'Самара, Россия',
            'SPB'=>'Санкт-Петербург, Россия',
            'SANTD'=>'Санто-Доминго, Доминиканская республика',
            'SANTG'=>'Сантьяго, Доминиканская республика',
            'SRN'=>'Саранск, Россия',
            'SRT'=>'Саратов, Россия',
            'SOCHI'=>'Сочи, Россия',
            'STAV'=>'Ставрополь, Россия',
            'STAM'=>'Стамбул, Турция',
            'STPNK'=>'Степанакерт, Армения',
            'STOCK'=>'Стокгольм, Швеция',
            'SUMY'=>'Сумы, Украина',
            'TGN'=>'Таганрог, Россия',
            'TALLN'=>'Таллин, Эстония',
            'TAMB'=>'Тамбов, Россия',
            'TASHK'=>'Ташкент, Узбекистан',
            'TBIL'=>'Тбилиси, Грузия',
            'TVER'=>'Тверь, Россия',
            'TEHR'=>'Тегеран, Иран',
            'TERNO'=>'Тернополь, Украина',
            'TIRAN'=>'Тирана, Албания',
            'TRIP'=>'Триполи, Ливия',
            'TULA'=>'Тула, Россия',
            'TUNIS'=>'Тунис, Тунис',
            'TYUM'=>'Тюмень, Россия',
            'UZHH'=>'Ужгород, Украина',
            'UFA'=>'Уфа, Россия',
            'FRAN'=>'Франкфурт-на-Майне, Германия',
            'HRK'=>'Харьков, Украина',
            'KHERS'=>'Херсон, Украина',
            'HMLN'=>'Хмельницкий, Украина',
            'CHEB'=>'Чебоксары, Россия',
            'CHEL'=>'Челябинск, Россия',
            'CHRP'=>'Череповец, Россия',
            'CHERK'=>'Черкассы, Украина',
            'CHERN'=>'Черновцы, Украина',
            'CHCG'=>'Чикаго, США',
            'SHYM'=>'Шымкент, Казахстан',
            'EKIB'=>'Экибастуз, Казахстан',
            'YARS'=>'Ярославль, Россия',
        ];


        //
        foreach($seed as $city_code=>$city_name)
        {
            $country = null;//Турция

            if( strpos($city_name,'Украина') )
            {
                $country = \App\Country::whereId(1)->first();
            }elseif( strpos($city_name,'Россия') ){
                $country = \App\Country::whereId(2)->first();
            }elseif( strpos($city_name,'Турция') ){
                $country = \App\Country::whereId(6)->first();
            }elseif( strpos($city_name,'Объединенные Арабские Эмираты') ){
                $country = \App\Country::whereId(5)->first();
            }else{
                $country_name = explode(', ', $city_name)[1];
                $country = \App\Country::whereName($country_name)->first();
                if( $country == null )
                {
                    $country = new \App\Country;
                    $country->name = $country_name;
                    $country->active = false;
                    $country->save();
                    echo $country_name."\n";
                }
            }

            $city = \App\City::whereCode($city_code)->first();
            if( $city == null )
            {
                $city = new \App\City;
                $city->code = $city_code;
                $city->name = $city_name;
                $city->country_id = $country->id;
                $city->active = 0;
                $city->save();
            }
            // 	    $city = new City;
            //     $city->id = $i;
            //     $city->code = $table_row[$i]->firstChild->nodeValue;
            //     $city->name = $table_row[$i]->lastChild->nodeValue;
            //     $city->active = 0;
            //     $city->save();
        }






    }
}
