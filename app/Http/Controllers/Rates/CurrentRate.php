<?php

namespace App\Http\Controllers\Rates;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Pair;

use App\Http\Controllers\Rates\CurrentRate;

class CurrentRate extends Controller
{
	static public function sort_array_by_order($a, $b)
	{
		if ($a['order'] == $b['order']) {
			return 0;
		}
		return ($a['order'] < $b['order']) ? -1 : 1;
	}

	public static function get_rate_best($pair)
	{

		$rate_provider = Cache::get('bestchange');
		if($rate_provider != null)
		{
			if(array_key_exists($pair->symbol, $rate_provider))
			{
				return $rate_provider[$pair->symbol];
			}else{
				return null;
			}
		}else{

			return null;
		}
		//dd($rate);
	}
	public static function get_rate($pair)
	{
		$rate_provider = Cache::get( 'bestchange' );
		//dd($rate_provider);
		if($rate_provider != null)
		{
			if(array_key_exists($pair->symbol, $rate_provider))
			{
				return $rate_provider[$pair->symbol];
			}else{
				return null;
			}
			return $rate;
		}else{

			return null;
		}
	}
	public static function get_rate_limit($pair, $provider)
	{
		$rate_provider = Cache::get( $provider );
		$rate = null;

		if($rate_provider != null)
		{
			if( array_key_exists($pair->symbol, $rate_provider) )
			{
				$rate = $rate_provider[$pair->symbol];
				if( array_key_exists('bid', $rate) && array_key_exists('ask', $rate) )
				{
					if( $rate['bid'] === 0 )
					{
						$rate['bid'] = $rate['ask'];
					}
				}
				if( array_key_exists('rate_to_pos_bid', $rate) && array_key_exists('rate_to_pos_ask', $rate) )
				{
					if( $rate['rate_to_pos_bid'] === 0 )
					{
						$rate['rate_to_pos_bid'] = $rate['rate_to_pos_ask'];
					}
				}

				if( array_key_exists('ask', $rate) && array_key_exists('bid', $rate) )
				{
					if( $rate['ask'] === 0 )
					{
						$rate['ask'] = $rate['bid'];
					}
				}
				if( array_key_exists('rate_to_pos_ask', $rate) && array_key_exists('rate_to_pos_bid', $rate) )
				{
					if( $rate['rate_to_pos_ask'] === 0 )
					{
						$rate['rate_to_pos_ask'] = $rate['rate_to_pos_bid'];
					}
				}
			}
		}
		return $rate;
	}
	public static function get_rate_final($pair, $best_rate, $limit_rate)
	{
		if( ($best_rate == null) || ($limit_rate == null) )
		{
			return null;
		}
		if( $pair->provider_id == 0)
		{
			$bid_limit = $pair->bid_coef;
			$ask_limit = $pair->ask_coef;
		}else{
			$bid_limit = $limit_rate['bid'] * $pair->bid_coef;
			$ask_limit = $limit_rate['ask'] * $pair->ask_coef;
		}
		$result = array();
		if( $best_rate['bid'] == 0)
		{
			$result['bid'] = $bid_limit;
		}elseif( ($best_rate['rate_to_pos_bid']+$pair->bid_step) < $bid_limit)
		{
			$result['bid'] = $best_rate['rate_to_pos_bid']+$pair->bid_step;
		}else{
			$result['bid'] = $bid_limit;
		}

		if( ($best_rate['rate_to_pos_ask']-$pair->ask_step) > $ask_limit)
		{
			$result['ask'] = $best_rate['rate_to_pos_ask']-$pair->ask_step;
		}else{
			$result['ask'] = $ask_limit;
		}

		return array('bid'=>$result['bid'],'ask'=>$result['ask']);
	}

	public function bestchange_file()
	{
		header("Content-Type: text/plain; charset=UTF-8");
		header("Content-Disposition: attachment; filename='exportxmlbest.xml'");
		$this->bestchange();
	}
	public function bestchange_export()
	{

		// 212.224.113.172 и 185.26.99.215 kursExpert

		header("Content-Type: text/xml");
		$this->bestchange();
	}

	public function bestchange_export_new()
	{

		// 212.224.113.172 и 185.26.99.215 kursExpert

		header("Content-Type: text/xml");
		$this->bestchange();
	}

	public function bestchange_old()
	{
		$current_best_mode = Cache::get( 'best_mode' );

		if($current_best_mode != null)
		{
			if($current_best_mode == 'off')
			{
				echo '<rates>';
				echo '</rates>';
				exit();
			}
		}
		$pairs =Pair::where('active','=',1)->with('base_currency','base_reserv','quote_reserv','quote_currency','provider','city')->get();

		echo '<rates>';

		foreach($pairs as $pair)
		{

			if($pair->active)
			{
				$best_rate = $this->get_rate_best($pair);
				if($best_rate == null)
				{
					continue;
				}
				$limit_rate = $this->get_rate_limit($pair, $pair->provider->code);
				if($limit_rate == null)
				{
					continue;
				}
				$final_rate = $this->get_rate_final($pair, $best_rate, $limit_rate);
				if($final_rate == null)
				{
					continue;
				}
				if( $pair->buy_enable == 1)
				{
					echo '<item>';
					echo '<from>'.$pair->base_currency->code.'</from>';
					echo '<to>'.$pair->quote_currency->code.'</to>';
					echo '<in>1</in>';
					//echo '<out>'.$rate['bid']*$pair->sub_bid.'</out>';
					echo '<out>'.(round($final_rate['bid'],6)*1).'</out>';

					if( !is_null($pair->quote_reserv) )
					{
						if($pair->quote_reserv->amount < 0)
						{
							echo '<amount>0</amount>';
						}else{
							echo '<amount>'.$pair->quote_reserv->amount.'</amount>';
						}
					}else{
						echo '<amount>0</amount>';
					}
					$pair_name_not_cash = $pair->base_currency->code;
					$pair_name_not_cash =  str_replace( ReferenceRates::uah_mapped,'UAH', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::rub_mapped,'RUB', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::euro_mapped,'EUR', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::usd_mapped,'USD', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::cny_mapped,'CNY', $pair_name_not_cash);

					//TODO ref to app
					$pair_name_not_cash = str_replace(array( 'WIRE', 'PP','EXM','EPM','ADVC','P24','TERC','TTRC','TRC','TBEP20'),'',$pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'NTLRUSD', 'PMUSD','EPMUSD','USDT','PPUSD','PRUSD','WMZ', 'CPTSUSD'),'USD', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'ACRUB','PRRUB'),'RUB', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCUAH'),'UAH', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCKZT','KSPBKZT'),'KZT', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ

					echo '<minamount>'.$pair->base_min.' '.$pair_name_not_cash.'</minamount>';
					echo '<maxamount>'.$pair->base_max.' '.$pair_name_not_cash.'</maxamount>';

					$item_param = 'manual';
					if( strpos( $pair->quote_currency->code, 'CASH' )  === 0 )
					{
						$item_param = $item_param.',floating';
					}


					$quote_corrency = $pair->base_currency->code;
					if( (strpos($quote_corrency, 'CARD') !== false) || (strpos($quote_corrency, 'P24') !== false) || (strpos($quote_corrency, 'MONO') !== false) || (strpos($quote_corrency, 'SBER') !== false) || (strpos($quote_corrency, 'ACRUB') !== false) )
					{
						$item_param = $item_param.',cardverify';
					}


					echo '<param>'.$item_param.'</param>';


					if($pair->city->code != 'Cashless')
					{
						echo '<city>'.$pair->city->code.'</city>';
					}
					echo '</item>';
				}
				if( $pair->sell_enable == 1)
				{
					echo '<item>';
					echo '<from>'.$pair->quote_currency->code.'</from>';
					echo '<to>'.$pair->base_currency->code.'</to>';
					//echo '<in>'.$rate['ask']*$pair->add_ask.'</in>';
					echo '<in>'.(round($final_rate['ask'],6)*1).'</in>';
					echo '<out>1</out>';

					if(!is_null($pair->base_reserv))
					{
						if($pair->base_reserv->amount < 0)
						{
							echo '<amount>0</amount>';
						}else{
							echo '<amount>'.$pair->base_reserv->amount.'</amount>';
						}
					}else{
						echo '<amount>0</amount>';
					}
					$pair_name_not_cash = $pair->quote_currency->code;
					$pair_name_not_cash =  str_replace( ReferenceRates::uah_mapped,'UAH', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::rub_mapped,'RUB', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::euro_mapped,'EUR', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::usd_mapped,'USD', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::cny_mapped,'CNY', $pair_name_not_cash);


					//TODO ref to app
					$pair_name_not_cash = str_replace(array( 'WIRE', 'PP','EXM','EPM','ADVC','P24','TERC','TTRC','TRC','TBEP20'),'',$pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'NTLRUSD', 'PMUSD','EPMUSD','USDT','PPUSD','PRUSD', 'CPTSUSD','WMZ'),'USD', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'ACRUB','PRRUB'),'RUB', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair_name_not_cash);
					$pair_name_not_cash = str_replace( array('ADVCUAH'),'UAH', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCKZT','KSPBKZT'),'KZT', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ



					echo '<minamount>'.$pair->quote_min.' '.$pair_name_not_cash.'</minamount>';
					echo '<maxamount>'.$pair->quote_max.' '.$pair_name_not_cash.'</maxamount>';
					$item_param = 'manual';
					if( strpos( $pair->base_currency->code, 'CASH' ) === 0 )
					{
						$item_param = $item_param.',floating';
					}

					echo '<param>'.$item_param.'</param>';

					if($pair->city->code != 'Cashless')
					{
						echo '<city>'.$pair->city->code.'</city>';
					}
					echo '</item>';
				}
			}

		}
		echo '</rates>';
		exit();
	}

	public function bestchange()
	{
		$current_best_mode = Cache::get( 'best_mode' );

		if($current_best_mode != null)
		{
			if($current_best_mode == 'off')
			{
				echo '<rates>';
				echo '</rates>';
				exit();
			}
		}
		$pairs = Pair::where('active','=',1)->with('base_currency','base_reserv','quote_reserv','quote_currency','provider','city');

		$cash_mode = Cache::get( 'cash_mode', null );
		if($cash_mode != null)
        {
            if($cash_mode == 'off')
            {
                $pairs = $pairs->whereNotIn( 'base_currency_id', [178,179,180,181,182,183,191] )->whereNotIn( 'quote_currency_id', [178,179,180,181,182,183,191] );
            }
        }
		$pairs = $pairs->get();

		echo '<rates>';

		foreach($pairs as $pair)
		{

			if($pair->active)
			{
				$best_rate = $this->get_rate_best($pair);
				if($best_rate == null)
				{
					continue;
				}
				$limit_rate = $this->get_rate_limit($pair, $pair->provider->code);
				if($limit_rate == null)
				{
					continue;
				}
				$final_rate = $this->get_rate_final($pair, $best_rate, $limit_rate);
				if($final_rate == null)
				{
					continue;
				}
				if( $pair->buy_enable == 1)
				{

					$item = [];
					$item['from'] = $pair->base_currency->code;
					$item['to'] = $pair->quote_currency->code;
					$item['in'] = 1;
					$item['out'] = (round($final_rate['bid'],6)*1);

					$item['amount'] = 0;

					if( !is_null($pair->quote_reserv) )
					{
						if( $pair->quote_reserv->amount > 0 )
						{
							$item['amount'] = $pair->quote_reserv->amount;
						}
					}

					$pair_name_not_cash = $pair->base_currency->code;
					$pair_name_not_cash =  str_replace( ReferenceRates::uah_mapped,'UAH', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::rub_mapped,'RUB', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::euro_mapped,'EUR', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::usd_mapped,'USD', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::cny_mapped,'CNY', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::kzt_mapped,'KZT', $pair_name_not_cash);

					//TODO ref to app
					$pair_name_not_cash = str_replace(array( 'WIRE', 'PP','EXM','EPM','ADVC','P24','TERC','TTRC','TRC','TBEP20'),'',$pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'NTLRUSD', 'PMUSD','EPMUSD','USDT','PPUSD','PRUSD','WMZ', 'CPTSUSD'),'USD', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'ACRUB','PRRUB'),'RUB', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCUAH'),'UAH', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCKZT','KSPBKZT'),'KZT', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ

					$item['minamount'] = $pair->base_min.' '.$pair_name_not_cash;
					$item['maxamount'] = $pair->base_max.' '.$pair_name_not_cash;

					$item_param = 'manual';
					if( strpos( $pair->quote_currency->code, 'CASH' )  === 0 )
					{
						$item_param = $item_param.',floating';
					}


					$quote_corrency = $pair->base_currency->code;
					if( (strpos($quote_corrency, 'CARD') !== false) || (strpos($quote_corrency, 'P24') !== false) || (strpos($quote_corrency, 'MONO') !== false) || (strpos($quote_corrency, 'SBER') !== false) || (strpos($quote_corrency, 'ACRUB') !== false) )
					{
						$item_param = $item_param.',cardverify';
					}

					$item['param'] = $item_param;

					$item['city'] = null;
					if($pair->city->code != 'Cashless')
					{
						$item['city'] = $pair->city->code;
					}


					$this->echo_xml_item( $item );

					//extra mappend pairs
//					if( $item['from'] == 'KSPBKZT')
//					{
//						$kaspis = ['KSPBDKZT','KSPBCKZT','KSPBGKZT','KSPGKZT'];
//						foreach( $kaspis as $kaspi)
//						{
//							$item['from'] = $kaspi;
//							$this->echo_xml_item( $item );
//						}
//					}
//					if( $item['to'] == 'KSPBKZT')
//					{
//						$kaspis = ['KSPBDKZT','KSPBCKZT','KSPBGKZT','KSPGKZT'];
//						foreach( $kaspis as $kaspi)
//						{
//							$item['to'] = $kaspi;
//							$this->echo_xml_item( $item );
//						}
//					}


				}
				if( $pair->sell_enable == 1)
				{
					$item = [];

					$item['from'] = $pair->quote_currency->code;
					$item['to'] = $pair->base_currency->code;
					$item['in'] = (round($final_rate['ask'],6)*1);
					$item['out'] = 1;

					$item['amount'] = 0;

					if( !is_null($pair->base_reserv) )
					{
						if( $pair->base_reserv->amount > 0 )
						{
							$item['amount'] = $pair->base_reserv->amount;
						}
					}


					$pair_name_not_cash = $pair->quote_currency->code;
					$pair_name_not_cash =  str_replace( ReferenceRates::uah_mapped,'UAH', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::rub_mapped,'RUB', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::euro_mapped,'EUR', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::usd_mapped,'USD', $pair_name_not_cash);
					$pair_name_not_cash =  str_replace( ReferenceRates::cny_mapped,'CNY', $pair_name_not_cash);


					//TODO ref to app
					$pair_name_not_cash = str_replace(array( 'WIRE', 'PP','EXM','EPM','ADVC','P24','TERC','TTRC','TRC'),'',$pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'NTLRUSD', 'PMUSD','EPMUSD','USDT','PPUSD','PRUSD', 'CPTSUSD','WMZ'),'USD', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'ACRUB','PRRUB'),'RUB', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair_name_not_cash);
					$pair_name_not_cash = str_replace( array('ADVCUAH'),'UAH', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ
					$pair_name_not_cash = str_replace( array('ADVCKZT','KSPBKZT'),'KZT', $pair_name_not_cash); //TODO REF ГРЕБАНЫЙ КОСТЫЛЬ, UP: НЕ КОСТЫЛЬ

					$item['minamount'] = $pair->quote_min.' '.$pair_name_not_cash;
					$item['maxamount'] = $pair->quote_max.' '.$pair_name_not_cash;


					$item_param = 'manual';
					if( strpos( $pair->base_currency->code, 'CASH' ) === 0 )
					{
						$item_param = $item_param.',floating';
					}
					$item['param'] = $item_param;

					$item['city'] = null;
					if($pair->city->code != 'Cashless')
					{
						$item['city'] = $pair->city->code;
					}
					$this->echo_xml_item( $item );

					//extra mappend pairs
//					if( $item['from'] == 'KSPBKZT')
//					{
//						$kaspis = ['KSPBDKZT','KSPBCKZT','KSPBGKZT','KSPGKZT'];
//						foreach( $kaspis as $kaspi)
//						{
//							$item['from'] = $kaspi;
//							$this->echo_xml_item( $item );
//						}
//					}
//					if( $item['to'] == 'KSPBKZT')
//					{
//						$kaspis = ['KSPBDKZT','KSPBCKZT','KSPBGKZT','KSPGKZT'];
//						foreach( $kaspis as $kaspi)
//						{
//							$item['to'] = $kaspi;
//							$this->echo_xml_item( $item );
//						}
//					}
				}
			}

		}
		echo '</rates>';
		exit();
	}

	public function echo_xml_item( $item )
	{

		echo '<item>';
		echo '<from>'.$item['from'].'</from>';
		echo '<to>'.$item['to'].'</to>';
		echo '<in>'.$item['in'].'</in>';
		echo '<out>'.$item['out'].'</out>';

		echo '<amount>'.$item['amount'].'</amount>';

		echo '<minamount>'.$item['minamount'].'</minamount>';
		echo '<maxamount>'.$item['maxamount'].'</maxamount>';

		echo '<param>'.$item['param'].'</param>';

		if( $item['city'] != null )
		{
			echo '<city>'.$item['city'].'</city>';
		}
		echo '</item>';

	}


	public function exchangesumo()
	{
		$current_best_mode = Cache::get( 'best_mode' );
		header("Content-Type: application/json");
		if($current_best_mode != null)
		{
			if($current_best_mode == 'off')
			{
				//	echo '<rates>';
				echo json_encode(array());
				exit();
			}
		}
		$pairs = Pair::where('active','=',1)->with('base_currency','base_reserv','quote_reserv','quote_currency','provider','city');
		$cash_mode = Cache::get( 'cash_mode', null );
		if($cash_mode != null)
		{
			if($cash_mode == 'off')
			{
				$pairs = $pairs->whereNotIn( 'base_currency_id', [178,179,180,181,182,183,191] )->whereNotIn( 'quote_currency_id', [178,179,180,181,182,183,191] );
			}
		}
		$pairs = $pairs->get();

		$export = array();

		//echo '<rates>';

		foreach($pairs as $pair)
		{

			if($pair->active)
			{
				$best_rate = $this->get_rate_best($pair);
				if($best_rate == null)
				{
					continue;
				}
				$limit_rate = $this->get_rate_limit($pair, $pair->provider->code);
				if($limit_rate == null)
				{
					continue;
				}
				$final_rate = $this->get_rate_final($pair, $best_rate, $limit_rate);
				if($final_rate == null)
				{
					continue;
				}
				if( $pair->buy_enable == 1)
				{

					if( !is_null($pair->quote_reserv) )
					{
						if($pair->quote_reserv->amount < 0)
						{
							$reserv_amount = 0;
						}else{
							$reserv_amount = $pair->quote_reserv->amount;
						}
					}else{
						$reserv_amount = 0;
					}

					$export['from'][str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair->base_currency->code)]['to'][str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair->quote_currency->code)] = array(
						'in'=>1,
						'out'=> $final_rate['bid'],
						'amount'=>$reserv_amount,
						'in_min_amount' => $pair->base_min,
					);

				}
				if( $pair->sell_enable == 1)
				{

					if(!is_null($pair->base_reserv))
					{
						if($pair->base_reserv->amount < 0)
						{
							$reserv_amount = 0;
						}else{
							$reserv_amount = $pair->base_reserv->amount;
						}
					}else{
						$reserv_amount = 0;
					}
					$export['from'][str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair->quote_currency->code)]['to'][str_replace(array( 'SBER','SKL','MONOB','CASH','CARD'),'', $pair->base_currency->code)] = array(
						'out'=>1,
						'in'=> $final_rate['ask'],
						'amount'=>$reserv_amount
					);

				}
			}

		}
		echo json_encode( $export );
		exit();
	}


	public static function api_to_front()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		header("Content-Type: text/json");
		header("Cache-Control: no-cache, no-store");

		$result = array('pairs'=>array(),'categories'=>array(),'currencies'=>array());
		$pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','provider','city')->orderBy('city_id','ASC')->orderBy('symbol','ASC');
		$cash_mode = Cache::get( 'cash_mode', null );
		if($cash_mode != null)
		{
			if($cash_mode == 'off')
			{
				$pairs = $pairs->whereNotIn( 'base_currency_id', [178,179,180,181,182,183,191] )->whereNotIn( 'quote_currency_id', [178,179,180,181,182,183,191] );
			}
		}
		$pairs = $pairs->get();


		$tmp_cities = array();
		$tmp_coins = array();
		$tmp_countries = array();

		foreach ($pairs as $pair)
		{

			//$rate = \App\Http\Controllers\Rates\CurrentRate::get_rate($pair);

			$best_rate = CurrentRate::get_rate_best($pair);
			if($best_rate == null)
			{
				continue;
			}
			$limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
			if($limit_rate == null)
			{
				continue;
			}
			$final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
			if($final_rate == null)
			{
				continue;
			}

			$result['pairs'][]=array(
				"id"=>$pair->id,
				"category_id"=>$pair->city_id,
				"pair"=> array(
					"base_id"=>(1000+$pair->base_currency_id),
					"quote_id"=>(1000+$pair->quote_currency_id),
					"buy_fee_amount"=>0,
					"sell_fee_amount"=>0
				),
				"sell_price"=>number_format($final_rate['ask'], $pair->quote_currency->round,'.',''),
				"buy_price"=>number_format($final_rate['bid'], $pair->quote_currency->round,'.',''),
				"is_top"=>true,
				"order"=>(count($result['pairs'])+1),
				"min_amount_base"=>floatval($pair->base_min),
				"min_amount_quote"=>floatval($pair->quote_min),
				"max_amount_quote"=>floatval($pair->quote_max),
				"max_amount_base"=>floatval($pair->base_max)
			);

			//Заполняем categories
			if( ! in_array($pair->city_id, $tmp_cities))
			{
				$result['categories'][] = array(
					"id"=>$pair->city_id,
					"parent_id"=>null,
					"title"=>$pair->city->name,
					"order"=>$pair->city->order,
				);
				$tmp_cities[] = $pair->city_id;
			}


			//Заполняем currencies
			if( ! in_array((1000+$pair->base_currency_id), $tmp_coins))
			{
				$result['currencies'][] = array(
					"id"=>(1000+$pair->base_currency_id),
					"title"=>$pair->base_currency->code,
					"decimal_places"=>$pair->base_currency->round,
					"allias"=>$pair->base_currency->name
				);
				$tmp_coins[] = (1000+$pair->base_currency_id);
			}
			if( ! in_array((1000+$pair->quote_currency_id), $tmp_coins))
			{
				$result['currencies'][] = array(
					"id"=>(1000+$pair->quote_currency_id),
					"title"=>$pair->quote_currency->code,
					"decimal_places"=>$pair->quote_currency->round,
					"allias"=>$pair->quote_currency->name
				);
				$tmp_coins[] = (1000+$pair->quote_currency_id);
			}
		}
		$tmp_array = $result['categories'];
		$result['categories'] = array();
		uasort($tmp_array,"self::sort_array_by_order" );
		foreach($tmp_array as $key=>$val)
		{
			$result['categories'][] = $val;
		}

		echo json_encode($result);
		exit();

	}


	public static function api_to_front_new()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: *');
		header('Access-Control-Allow-Headers: *');
		header("Content-Type: text/json");
		header("Cache-Control: no-cache, no-store");


		$result = array();

		$pairs = Pair::where('active','=',1)->with('base_currency','quote_currency','base_reserv','quote_reserv','provider', 'city', 'city.country')->orderBy('city_id','ASC');

		$cash_mode = Cache::get( 'cash_mode', null );
		if($cash_mode != null)
		{
			if($cash_mode == 'off')
			{
				$pairs = $pairs->whereNotIn( 'base_currency_id', [178,179,180,181,182,183,191] )->whereNotIn( 'quote_currency_id', [178,179,180,181,182,183,191] );
			}
		}
		$pairs = $pairs->get();


		$tmp_cities = array();
		$tmp_coins = array();
		$tmp_countries = array();

		foreach ($pairs as $pair)
		{
			$best_rate = CurrentRate::get_rate_best($pair);
			if($best_rate == null)
			{
				continue;
			}
			$limit_rate = CurrentRate::get_rate_limit($pair, $pair->provider->code);
			if($limit_rate == null)
			{
				continue;
			}
			$final_rate = CurrentRate::get_rate_final($pair, $best_rate, $limit_rate);
			if($final_rate == null)
			{
				continue;
			}
			$result['country'][$pair->city->country->id] = array('name'=>$pair->city->country->name);
			$result['cities'][$pair->city_id] = array('name'=>$pair->city->name, 'country'=>$pair->city->country->id);

			if($pair->quote_reserv != null ){
				$reserv_quote = number_format($pair->quote_reserv->amount, $pair->quote_currency->round,'.','');
			}else{
				$reserv_quote = 0;
			}
			if($reserv_quote < 0)
			{
				$reserv_quote = 0;
			}

			if($pair->base_reserv != null ){
				$reserv_base = number_format($pair->base_reserv->amount, $pair->base_currency->round,'.','');
			}else{
				$reserv_base = 0;
			}
			if($reserv_base < 0)
			{
				$reserv_base = 0;
			}

			$need_verify = false;
			$is_cashe = false;

			if( (strpos($pair->symbol, 'CARD') !== false) || (strpos($pair->symbol, 'P24') !== false) || (strpos($pair->symbol, 'MONO') !== false) || (strpos($pair->symbol, 'SBER') !== false) || (strpos($pair->symbol, 'ACRUB') !== false) )
			{
				$need_verify = true;
			}
			if( (strpos($pair->symbol, 'CASH') !== false) )
			{
				$is_cashe = true;
			}
			if( $pair->buy_enable == 1)
			{
				$quote_corrency = $pair->quote_currency->code;
				if(
					(strpos($quote_corrency, 'CARD') !== false) ||
					(strpos($quote_corrency, 'P24') !== false) ||
					(strpos($quote_corrency, 'MONO') !== false) ||
					(strpos($quote_corrency, 'SBER') !== false) ||
					(strpos($quote_corrency, 'ACRUB') !== false) ||
					(strpos($quote_corrency, 'NTLR') !== false) ||
					(strpos($quote_corrency, 'SKL') !== false)
					)
					{
						$need_verify = true;
					}else{
						$need_verify = false;
					}

				$result['pairs'][$pair->base_currency_id][$pair->quote_currency_id][]=array(
					"id"=>$pair->id,
					"country"=>$pair->city->country->id,
					"city"=>$pair->city_id,
					"rate"=>number_format($final_rate['bid'], $pair->quote_currency->round,'.','')*1,
					"min"=>floatval($pair->base_min),
					"max"=>floatval($pair->base_max),
					"reserv"=>$reserv_quote*1,
					"base_currency"=>$pair->base_currency->code,
					"quote_currency"=>$pair->quote_currency->code,
					'need_verify'=>$need_verify,
					'is_cashe'=>$is_cashe
				);
			}
			if( $pair->sell_enable == 1)
			{
				$quote_corrency = $pair->base_currency->code;
				if( (strpos($quote_corrency, 'CARD') !== false) || (strpos($quote_corrency, 'P24') !== false) || (strpos($quote_corrency, 'MONO') !== false) || (strpos($quote_corrency, 'SBER') !== false) || (strpos($quote_corrency, 'ACRUB') !== false) )
				{
					$need_verify = true;
				}else{
					$need_verify = false;
				}
				$result['pairs'][$pair->quote_currency_id][$pair->base_currency_id][]=array(
					"id"=>$pair->id+1000000,
					"country"=>$pair->city->country->id,
					"city"=>$pair->city_id,
					"rate"=>number_format($final_rate['ask'], $pair->base_currency->round,'.','')*1,
					"min"=>floatval($pair->quote_min),
					"max"=>floatval($pair->quote_max),
					"reserv"=>$reserv_base*1,
					"base_currency"=>$pair->quote_currency->code,
					"quote_currency"=>$pair->base_currency->code,
					'need_verify'=>$need_verify,
				);
			}
			$result['currencies'][$pair->base_currency_id] = array('name'=>$pair->base_currency->name, 'code'=>$pair->base_currency->code);
			$result['currencies'][$pair->quote_currency_id] = array('name'=>$pair->quote_currency->name, 'code'=>$pair->quote_currency->code);

		}
//		usort($result['cities'], function($a, $b) {
//		    return $a['name'] <=> $b['name'];
//		});

		echo json_encode($result);
		exit();

	}


}
