<?php
declare(strict_types=1);
function invoice(array $input): array {
 if(PHP_INT_SIZE<8)throw new InvalidArgumentException('64-bit PHP required');
 if(!isset($input['id'],$input['currency'],$input['items'],$input['tax_bps'])||!is_string($input['id'])||trim($input['id'])===''||!preg_match('/^[A-Z]{3}$/D',$input['currency'])||!is_array($input['items'])||count($input['items'])<1||count($input['items'])>1000)throw new InvalidArgumentException('Need id, three-letter currency, items, and tax_bps');
 $tax=$input['tax_bps'];if(!is_int($tax)||$tax<0||$tax>10000)throw new InvalidArgumentException('tax_bps must be 0–10000');
 $subtotal=0;$lines=[];foreach($input['items']as $item){$q=$item['quantity']??null;$price=$item['unit_cents']??null;$name=$item['description']??'';if(!is_int($q)||$q<1||$q>100000||!is_int($price)||$price<0||$price>1000000000||!is_string($name)||trim($name)==='')throw new InvalidArgumentException('Invalid item');$total=$q*$price;if($total>1000000000000||$subtotal+$total>10000000000000)throw new InvalidArgumentException('Invoice amount exceeds supported range');$subtotal+=$total;$lines[]=['description'=>$name,'quantity'=>$q,'unit_cents'=>$price,'line_cents'=>$total];}
 $discount=$input['discount_cents']??0;if(!is_int($discount)||$discount<0||$discount>$subtotal)throw new InvalidArgumentException('Invalid discount');$net=$subtotal-$discount;$taxCents=intdiv($net*$tax+5000,10000);
 return ['id'=>$input['id'],'currency'=>$input['currency'],'items'=>$lines,'subtotal_cents'=>$subtotal,'discount_cents'=>$discount,'tax_bps'=>$tax,'tax_cents'=>$taxCents,'total_cents'=>$net+$taxCents];
}
try{
 if(($argv[1]??'')==='--self-test'){$r=invoice(['id'=>'T1','currency'=>'USD','items'=>[['description'=>'Test','quantity'=>2,'unit_cents'=>1250]],'tax_bps'=>825]);if($r['total_cents']!==2706||$r['tax_cents']!==206)throw new Exception('Integer rounding failed');try{invoice(['id'=>'bad']);throw new Exception('Invalid accepted');}catch(InvalidArgumentException $e){}echo "Integer cents, tax rounding and invalid-input checks passed\n";exit;}
 if($argc!==2&&$argc!==4)throw new InvalidArgumentException('Usage: php app.php invoice.json [--ledger history.jsonl]');
 $input=json_decode(file_get_contents($argv[1]),true,512,JSON_THROW_ON_ERROR);$result=invoice($input);$json=json_encode($result,JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);
 if($argc===4){if($argv[2]!=='--ledger')throw new InvalidArgumentException('Unknown option');$file=fopen($argv[3],'c+');if(!$file||!flock($file,LOCK_EX))throw new RuntimeException('Cannot lock ledger');while(($line=fgets($file))!==false){$old=json_decode($line,true,512,JSON_THROW_ON_ERROR);if(($old['id']??null)===$result['id'])throw new RuntimeException('Invoice ID already in ledger');}fseek($file,0,SEEK_END);$line=json_encode($result,JSON_THROW_ON_ERROR)."\n";if(fwrite($file,$line)!==strlen($line))throw new RuntimeException('Ledger write failed');fflush($file);flock($file,LOCK_UN);fclose($file);}
 echo $json."\n";
}catch(Throwable $e){fwrite(STDERR,$e->getMessage()."\n");exit(1);}
