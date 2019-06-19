<?php
/**
 * ======================================================================================
 * AMIKOM.AC.ID API
 * ======================================================================================
 * Please Keep This Credit
 * --------------------------------------------------------------------------------------
 * Created by   : Kang Sunu
 * Repository   : https://github.com/sunuazizrahayu/amikom.ac.id-API
 * Contact      :
 * -- LinkedIn  : https://www.linkedin.com/in/sunuazizrahayu/
 * -- GitHUB    : https://github.com/sunuazizrahayu/
 *
 * ======================================================================================
 */
class Amikom
{
	const OLD_AMIKOM = 'http://old.amikom.ac.id/';


	//MAIN FUNCTION
	function login($url,$data){
		$fp = fopen("cookie.txt", "w");
		fclose($fp);
		$login = curl_init();
		curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
		curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
		curl_setopt($login, CURLOPT_TIMEOUT, 40000);
		curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($login, CURLOPT_URL, $url);
		curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($login, CURLOPT_POST, TRUE);
		curl_setopt($login, CURLOPT_POSTFIELDS, $data);
		ob_start();
		return curl_exec ($login);
		ob_end_clean();
		curl_close ($login);
		unset($login);
	}
	function grab_page($site){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
		curl_setopt($ch, CURLOPT_URL, $site);
		ob_start();
		return curl_exec ($ch);
		ob_end_clean();
		curl_close ($ch);
	}
	function post_data($site,$data){
		$datapost = curl_init();
		$headers = array("Expect:");
		curl_setopt($datapost, CURLOPT_URL, $site);
		curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
		curl_setopt($datapost, CURLOPT_HEADER, TRUE);
		curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($datapost, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($datapost, CURLOPT_POST, TRUE);
		curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
		curl_setopt($datapost, CURLOPT_COOKIEFILE, "cookie.txt");
		curl_setopt($datapost, CURLOPT_RETURNTRANSFER, true);
		ob_start();
		return curl_exec ($datapost);
		ob_end_clean();
		curl_close ($datapost);
		unset($datapost);


		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_TIMEOUT => 40000,
			CURLOPT_HEADER => TRUE,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_COOKIEFILE => "cookie.txt",
			CURLOPT_RETURNTRANSFER => TRUE,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return $response;
		}
	}


	// STATUS MAHASISWA
	public function getStatusMahasiswa($nim='')
	{
		// $html = self::htmlStatusMahasiswa($nim);
		$params = array(
			'nim'=>$nim,
			'cek'=>'cek'
		);
		$html = self::post_data(self::OLD_AMIKOM.'/index.php/status/',$params);

		//cek apakah data ditemukan
		if (preg_match('/tidak ditemukan/', $html)) {
			$jsonMhs = false;
		}
		else{
			//--filter
			$pecah1 = explode('<div class="profil">', $html); //info mahasiswa
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


		//create json data
		switch ($jsonMhs) {
			case true:
				$jsonMhs = array(
					"status_code" => 200,
					"success"	=> true,
					"data"		=> array(
						"nim"		=> $mhsNIM,
						"nama"		=> $mhsNama,
						"jurusan"	=> $mhsJurusan,
						"status"	=> $mhsStatus,
						"foto"		=> $mhsFoto
						)
				);
				break;
			default:
				$jsonMhs = array(
					"status_code" => 404,
					"success"	=> false,
					"message"	=> "Data Not Found"
				);
				break;
		}

		//JSON DATA
		return self::render_json($jsonMhs);
	}

	// BIODATA MAHASISWA
	public function getBiodataMahasiswa($nim='', $password='')
	{
		//get cookies
		$credential = [
			'npm_in' => $nim,
			'pwd_in' => $password,
			'submit' =>'Login'
		];
		$login = self::login(self::OLD_AMIKOM.'index.php/login/mhs_amikom', $credential);

		// get html page
		$html = self::grab_page(self::OLD_AMIKOM.'index.php/mhs/biodata');
		$nim1 = explode('Nomor Induk Mahasiswa</td><td>', $html);
		$nim2 = explode(' </td>', $nim1[1]);
		$nim = $nim2[0];

		$name1 = explode('<td>Nama</td><td>', $html);
		$name2 = explode(' </td>', $name1[1]);
		$name = $name2[0];

		$jurusan1 = explode('<td>Jurusan</td><td>', $html);
		$jurusan2 = explode(' </td>', $jurusan1[1]);
		$jurusan = $jurusan2[0];

		$email_amikom = explode('<td>Email</td>', $html);
		$email_amikom = explode('<td>', $email_amikom[1]);
		$email_amikom = explode('</td>', $email_amikom[1]);
		$email_amikom = $email_amikom[0];

		$dosen_wali1 = explode('<td>Dosen Wali</td><td>', $html);
		$dosen_wali2 = explode(' </td>', $dosen_wali1[1]);
		$dosen_wali = $dosen_wali2[0];

		$nohp = explode('name="mhs_nohp" value="', $html);
		$nohp = explode('"', $nohp[1]);
		$nohp = $nohp[0];

		$address = explode('<textarea cols="60" name="mhs_alamat">', $html);
		$address = explode('</textarea>', $address[1]);
		$address = $address[0];

		$wn = explode('<select name="mhs_kewarganegaraan">', $html);
		$wn = explode('</select>', $wn[1]);
		$wn = explode(' selected >', $wn[0]);
		$wn = explode('</option>', $wn[1]);
		$wn = $wn[0];

		$agama = explode('<select name="mhs_agama">', $html);
		$agama = explode('</select>', $agama[1]);
		$agama = explode(' selected >', $agama[0]);
		$agama = explode('</option>', $agama[1]);
		$agama = $agama[0];

		$kodepos = explode('name="mhs_kode_pos" value="', $html);
		$kodepos = explode('"', $kodepos[1]);
		$kodepos = $kodepos[0];

		$tl = explode('name="mhs_tempat_lahir" value="', $html);
		$tl = explode('"', $tl[1]);
		$tl = $tl[0];

		$dateborn = explode('<td>Tanggal Lahir</td><td>', $html);
		$dateborn = explode(' <sup> ', $dateborn[1]);
		$dateborn = $dateborn[0];

		//create json
		$jsondata = [
			'nim' => $nim,
			'nama' => $name,
			'jurusan' => $jurusan,
			'email' => $email_amikom,
			'telepon' => $nohp,
			'alamat' => $address,
			'kewarganegaraan' => $wn,
			'agama' => $agama,
			'kodepos' => $kodepos,
			'tempat_lahir' => $tl,
			'tanggal_lahir' => $dateborn,
			'dosen_wali' => $dosen_wali,
		];


		if (!empty($html)) {
			$draw_json = array(
				'status_code' => 200,
				'success' => true,
				'data' => $jsondata
			);
		}
		else{
			$draw_json = array(
				'status_code' => 404,
				'success' => false,
				'message' => 'User not Found'
			);
		}

		return self::render_json($draw_json);
	}



	/*
	 * FUNCTION LIST
	 */
	// CREATE OUPUT JSON
	public function render_json($data=array())
	{
		header('Content-Type: application/json');
		return json_encode($data, JSON_PRETTY_PRINT);
	}
}

/* End of file Amikom.php */
?>