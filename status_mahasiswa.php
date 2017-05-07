<?php
/*
Simple API data Status Mahasiswa Amikom
URL data: http://www.amikom.ac.id/index.php/status/

// please keep this credit
Credit by: Kang Sunu
FB 	: /SunuAzizRahayu
FB 	: /KangSunuID
Blog: www.kangsunu.web.id
*/

include 'curl.php';

if (isset($_GET['nim'])) {
	$nim = $_GET['nim'];

	if ($nim!='') {
		$login = login("http://www.amikom.ac.id/index.php/status/","nim=".$nim."&cek=Cek+Status");

		//cek apakah data ditemukan
		if (preg_match('/tidak ditemukan/', $login)) {
			$jsonMhs = false;
		}
		else{
			//--filter
			$pecah1 = explode('<div class="profil">', $login); //info mahasiswa
			$pecah2 = explode('-->', $pecah1['1']);
			$pecah = $pecah2[0];


			//getNama
			$nama1 = explode('<div class="nama">', $pecah);
			$nama2 = explode('</div>', $nama1[1]);
			$mhsNama = $nama2[0];
			
			//getNim
			$nim1 = explode('<div class="npm">', $pecah);
			$nim2 = explode('</div>', $nim1[1]);
			$mhsNIM = $nim2[0];

			//getJurusan
			$jurusan1 = explode('<div class="jurusan">', $pecah);
			$jurusan2 = explode('</div>', $jurusan1[1]);
			$mhsJurusan = $jurusan2[0];

			//getStatus Aktif / non-Aktif
			$status1 = explode('<div class="status">', $pecah);
			$status2 = explode('</div>', $status1[1]);
			$mhsStatus = $status2[0];

			//getFoto
			$foto1 = explode('<img class="foto" src="', $pecah1[0]);
			$foto2 = explode('"', $foto1[1]);
			$mhsFoto = $foto2[0];
			

			//buat json bila ada
			$jsonMhs = true;
		}
	}
	else{
		$jsonMhs = false;
	}



	//membuat data json
	switch ($jsonMhs) {
		case false:
			$jsonMhs = array(
				"success"	=> "false",
				"message"	=> "data not found"
			);
			break;
		case true:
			$jsonMhs = array(
					"success"	=> "true",
					"data"		=> array(
							"nim"		=> $mhsNIM,
							"nama"		=> $mhsNama,
							"jurusan"	=> $mhsJurusan,
							"status"	=> $mhsStatus,
							"foto"		=> $mhsFoto
						)
				);
			break;
	}


	//membuat output
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	$output = json_encode($jsonMhs, JSON_PRETTY_PRINT);
	echo $output;
}

//kode ini boleh dihapus || you can remove this code
else{
	$nim = "xx.xx.xxxx";
	echo "tambahkah kueri nim dan nilai nim <br/>Contoh: <a href=\"?nim=$nim\">?nim=$nim</a>";
}
?>