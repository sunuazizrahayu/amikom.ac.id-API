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

	// STATUS MAHASISWA
	public function getStatusMahasiswa($nim='')
	{
		$html = self::htmlStatusMahasiswa($nim);

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



	/*
	 * GRABBING HTML LIST
	 */
	// STATUS MAHASISWA
	public function htmlStatusMahasiswa($nim)
	{
		$params = array(
			'nim'=>$nim,
			'cek'=>'cek'
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => self::OLD_AMIKOM.'/index.php/status/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($params),
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

	/*
	 * FUNCTION LIST
	 */
	// CREATE OUPUT JSON
	private function render_json($data=array())
	{
		header('Content-Type: application/json');
		return json_encode($data, JSON_PRETTY_PRINT);
	}
}

/* End of file Amikom.php */
?>