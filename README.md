# API Amikom.ac.id 
Un-Official API Universitas AMIKOM Yogyakarta

## API List
- [Get Status Mahasiswa](#get-status-mahasiswa)
- [Get Biodata Mahasiswa](#get-biodata-mahasiswa)

## Installation
Add this code to your **.php** file.
```
require_once 'Amikom.php';
$amikom = new Amikom();
```

## Usage
### Get Status Mahasiswa
```
echo $amikom->getStatusMahasiswa('xx.xx.xxxx');
```

### Get Biodata Mahasiswa
```
echo $amikom->getBiodataMahasiswa('YOUR_NIM', 'YOUR_PASSWORD');
```