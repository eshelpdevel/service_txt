<?php 
###############################################################################################################
# Date          |    Type    |   Version                                                                      # 
############################################################################################################### 
# 02-05-2025    |   Create   |  1.0205.2025                                                                   #
############################################################################################################### 

	include "sosmed_configuration.php";
	function blass_fileparse_tonode($params){
		$body = json_encode($params);
		$token = '123';
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL, "https://devcrm.wom.co.id:8766/blashparswa");
		curl_setopt($ch, CURLOPT_URL, "http://10.0.5.231:8766/blashparswa");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'token: '.$token
		));
		$exec = curl_exec($ch);
		curl_close($ch);
		return $exec;

	}
	function blast_sync2($conn){

		$namafile = "blast_report".date('YmdHis').".txt";
		$directory = '/var/www/html/Blast';
		$files = scandir($directory, SCANDIR_SORT_ASCENDING);
		$totalFile = count($files);

		$myfile = fopen("/var/www/html/activity/_runwablast_afterservice.txt", "w") or die("Unable to open file!");
		$txt = date('Y-m-d H:i:s')."\n".$totalFile." Datascan";
		fwrite($myfile, $txt);
		fclose($myfile);

		$files = array_diff($files, array('.', '..',$namafile,"blast_report".date('YmdHis').".txt"));
		$files = array_slice($files, 0, 20);

		$jumfile = 0;
		//puter ngirim
		foreach($files as $file) {
			$params = array(
	     		'path' => $directory."/".$file
			);
			$sfile[$jumfile] = $directory."/".$file;

			$jumfile++;
	     	$response = blass_fileparse_tonode($params);
		}


		//cek selese ga
		while ($jumfile > 0) {
			$adaIlang = 0;
		  	for ($i=0; $i <count($sfile) ; $i++) { 
		  		if (file_exists($sfile[$i])) {
		  			//abaikan karna file masih ada
		  		}else{
		  			unset($sfile[$i]);
		  			$adaIlang++;
		  			$jumfile--;
		  		}
		  	}
		  	if ($adaIlang != 0) {
				$sfile = array_values($sfile);
		  	}
		}
		

	}

blast_sync2($conn);		
	
?>