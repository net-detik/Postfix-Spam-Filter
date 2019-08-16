<?php
define("PLATFORM_ROOT", __DIR__ ."/");

class spamAlgoritma{
	
	//@--
	//fungsi untuk membuat directory
	public function cmkdir($params){
		if (!is_dir($params['folderFull'])) mkdir($params['folderFull'], 0775, true);	
	}
	
	//@--
	//fungsi untuk membuat cache query mysql
	public function createCache($params){
		//create dir 
		$fileContent	=$params['fileContent'];
		$folderFull		=$params['folderFull'];
		$fileName		=$params['fileName'];
		$mkdir			=self::cmkdir(array('folderFull'=>$folderFull));
		
		if($params['append']=='Y'){
			file_put_contents($folderFull."".$fileName, $fileContent,FILE_APPEND | LOCK_EX);
		}else{
			file_put_contents($folderFull."".$fileName, $fileContent);
		}
		$callback['pesan']='sukses';
		return $callback;
	}
	
	//@--
	//menambah email ke daftar blacklist
	public function emailBlacklist($params){
		//add email/domain yang akan diblacklist
		$blacklistData=$params['emailDomain'];
		//masukan ke dalam file blacklist target
		$blacklist=self::createCache(array(
			'fileContent'	=>$blacklistData,
			'folderFull'	=>PLATFORM_ROOT."source/",
			'fileName'		=>"blacklist.txt",
		));
	}
	
	//untuk menghitung jumlah email yang dikirm oleh satu akun email
	//jika jumlah email yang dikirim oleh email tertentu melebihi jumlah normal
	//maka masukan kedalam daftar blacklist
	public function spamCount($params){
		//get file mailq for analis
		$fiMailq=PLATFORM_ROOT.'mailq.txt';
		//echo $fiMailq;
		$mailq= file($fiMailq, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$i=0;
		foreach($mailq as $r){
			$findSpam=explode(' ',$r);
			$j=0;
			foreach($findSpam as $key=>$val){
				if($val==''){ }else{
					$f[$i][$j]=$val;
				$j++;
				}
			}
			$findSpam2[]=$f[$i];
		$i++;
		}
		
	return $findSpam2;		
	}
	
	public function findSpam($params){
		$mailq		=self::spamCount($params);
		//anda dapat menambahkan daftar putih, artinya yg masuk didaftar putih tidak akan pernah dianggap spam,meskipun trafik tinggi
    $whiteList	=array('root@domain.com',
		'MAILER-DAEMON',
		'double-bounce@domain.com',
		'getmail',
		'root',
		'getmail@domain.com');
		
		foreach($mailq as $r){
			if(in_array($r[1],$whiteList)){ }else{
				if($r[0]>=15){
					//add to blacklist email yang melebihi batas wajar
					self::emailBlacklist(array('emailDomain'=>$r[1]));
				}else{}
			}
		}
	return $mailq;	
	}
	
	//self::emailBlacklist(array('emailDomain'=>$fr[1]));
} 


