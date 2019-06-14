# API Amikom.ac.id 
Un-Official API Universitas AMIKOM Yogyakarta

## API List
- Get Status Mahasiswa
- Get Biodata Mahasiswa (next)

## Instalasi
Tambahkan kode ini di file **.php**
```
require_once 'Amikom.php';
$amikom = new Amikom();
```

## Penggunaan
### Get Status Mahasiswa
```
echo $amikom->getStatusMahasiswa('xx.xx.xxxx');
```