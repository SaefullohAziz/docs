<?php

use Illuminate\Database\Seeder;
use App\Island;
use App\Province;
use App\Regency;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = [
        	[
        		'island' => [
        			'name' => 'Jawa',
	        		'provinces' => [
	        			[
	        				'name' => 'Banten',
	        				'abbreviation' => 'Banten',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Pandeglang',
		        					'code' => 'PDG',
	        					],
	        					[
		        					'name' => 'Kabupaten Lebak',
		        					'code' => 'RKB',
	        					],
	        					[
		        					'name' => 'Kabupaten Tangerang',
		        					'code' => 'TGR',
	        					],
	        					[
		        					'name' => 'Kabupaten Serang',
		        					'code' => 'SRG',
	        					],
	        					[
		        					'name' => 'Kota Tangerang',
		        					'code' => 'TNG',
	        					],
	        					[
		        					'name' => 'Kota Cilegon',
		        					'code' => 'CLG',
	        					],
	        					[
		        					'name' => 'Kota Serang',
		        					'code' => 'SRG',
	        					],
	        					[
		        					'name' => 'Kota Tangerang Selatan',
		        					'code' => 'CPT',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'DKI Jakarta',
	        				'abbreviation' => 'JKT',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Kepulauan Seribu',
		        					'code' => 'KSU',
	        					],
	        					[
		        					'name' => 'Kota Jakarta Selatan',
		        					'code' => 'KYB',
	        					],
	        					[
		        					'name' => 'Kota Jakarta Timur',
		        					'code' => 'CKG',
	        					],
	        					[
		        					'name' => 'Kota Jakarta Pusat',
		        					'code' => 'TNA',
	        					],
	        					[
		        					'name' => 'Kota Jakarta Barat',
		        					'code' => 'GGP',
	        					],
	        					[
		        					'name' => 'Kota Jakarta Utara',
		        					'code' => 'TJP',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Jawa Barat',
	        				'abbreviation' => 'Jabar',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bogor',
		        					'code' => 'CBI',
	        					],
	        					[
		        					'name' => 'Kabupaten Sukabumi',
		        					'code' => 'SBM',
	        					],
	        					[
		        					'name' => 'Kabupaten Cianjur',
		        					'code' => 'CJR',
	        					],
	        					[
		        					'name' => 'Kabupaten Bandung',
		        					'code' => 'SOR',
	        					],
	        					[
		        					'name' => 'Kabupaten Garut',
		        					'code' => 'GRT',
	        					],
	        					[
		        					'name' => 'Kabupaten Tasikmalaya',
		        					'code' => 'SPA',
	        					],
	        					[
		        					'name' => 'Kabupaten Ciamis',
		        					'code' => 'CMS',
	        					],
	        					[
		        					'name' => 'Kabupaten Kuningan',
		        					'code' => 'KNG',
	        					],
	        					[
		        					'name' => 'Kabupaten Cirebon',
		        					'code' => 'SBR',
	        					],
	        					[
		        					'name' => 'Kabupaten Majalengka',
		        					'code' => 'MJL',
	        					],
	        					[
		        					'name' => 'Kabupaten Sumedang',
		        					'code' => 'SMD',
	        					],
	        					[
		        					'name' => 'Kabupaten Indramayu',
		        					'code' => 'IDM',
	        					],
	        					[
		        					'name' => 'Kabupaten Subang',
		        					'code' => 'SNG',
	        					],
	        					[
		        					'name' => 'Kabupaten Purwakarta',
		        					'code' => 'PWK',
	        					],
	        					[
		        					'name' => 'Kabupaten Karawang',
		        					'code' => 'KWG',
	        					],
	        					[
		        					'name' => 'Kabupaten Bekasi',
		        					'code' => 'CKR',
	        					],
	        					[
		        					'name' => 'Kabupaten Bandung Barat',
		        					'code' => 'NPH',
	        					],
	        					[
		        					'name' => 'Kabupaten Pangandaran',
		        					'code' => 'PRI',
	        					],
	        					[
		        					'name' => 'Kota Bogor',
		        					'code' => 'BGR',
	        					],
	        					[
		        					'name' => 'Kota Sukabumi',
		        					'code' => 'SKB',
	        					],
	        					[
		        					'name' => 'Kota Bandung',
		        					'code' => 'BDG',
	        					],
	        					[
		        					'name' => 'Kota Cirebon',
		        					'code' => 'CBN',
	        					],
	        					[
		        					'name' => 'Kota Bekasi',
		        					'code' => 'BKS',
	        					],
	        					[
		        					'name' => 'Kota Depok',
		        					'code' => 'DPK',
	        					],
	        					[
		        					'name' => 'Kota Cimahi',
		        					'code' => 'CMH',
	        					],
	        					[
		        					'name' => 'Kota Tasikmalaya',
		        					'code' => 'TSM',
	        					],
	        					[
		        					'name' => 'Kota Banjar',
		        					'code' => 'BJR',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Jawa Tengah',
	        				'abbreviation' => 'Jateng',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Cilacap',
		        					'code' => 'CLP',
	        					],
	        					[
		        					'name' => 'Kabupaten Banyumas',
		        					'code' => 'PWT',
	        					],
	        					[
		        					'name' => 'Kabupaten Purbalingga',
		        					'code' => 'PBG',
	        					],
	        					[
		        					'name' => 'Kabupaten Banjarnegara',
		        					'code' => 'BNR',
	        					],
	        					[
		        					'name' => 'Kabupaten Kebumen',
		        					'code' => 'KBM',
	        					],
	        					[
		        					'name' => 'Kabupaten Purworejo',
		        					'code' => 'PWR',
	        					],
	        					[
		        					'name' => 'Kabupaten Wonosobo',
		        					'code' => 'WSB',
	        					],
	        					[
		        					'name' => 'Kabupaten Magelang',
		        					'code' => 'MKD',
	        					],
	        					[
		        					'name' => 'Kabupaten Boyolali',
		        					'code' => 'BYL',
	        					],
	        					[
		        					'name' => 'Kabupaten Klaten',
		        					'code' => 'KLN',
	        					],
	        					[
		        					'name' => 'Kabupaten Sukoharjo',
		        					'code' => 'SKH',
	        					],
	        					[
		        					'name' => 'Kabupaten Wonogiri',
		        					'code' => 'WNG',
	        					],
	        					[
		        					'name' => 'Kabupaten Karanganyar',
		        					'code' => 'KRG',
	        					],
	        					[
		        					'name' => 'Kabupaten Sragen',
		        					'code' => 'SGN',
	        					],
	        					[
		        					'name' => 'Kabupaten Grobogan',
		        					'code' => 'PWD',
	        					],
	        					[
		        					'name' => 'Kabupaten Blora',
		        					'code' => 'BLA',
	        					],
								[
		        					'name' => 'Kabupaten Rembang',
		        					'code' => 'RBG',
	        					],
								[
		        					'name' => 'Kabupaten Pati',
		        					'code' => 'PTI',
	        					],
								[
		        					'name' => 'Kabupaten Kudus',
		        					'code' => 'KDS',
	        					],
								[
		        					'name' => 'Kabupaten Jepara',
		        					'code' => 'JPA',
	        					],
								[
		        					'name' => 'Kabupaten Demak',
		        					'code' => 'DMK',
	        					],
								[
		        					'name' => 'Kabupaten Semarang',
		        					'code' => 'UNR',
	        					],
								[
		        					'name' => 'Kabupaten Temanggung',
		        					'code' => 'TMG',
	        					],
								[
		        					'name' => 'Kabupaten Kendal',
		        					'code' => 'KDL',
	        					],
								[
		        					'name' => 'Kabupaten Batang',
		        					'code' => 'BTG',
	        					],
								[
		        					'name' => 'Kabupaten Pekalongan',
		        					'code' => 'KJN',
	        					],
								[
		        					'name' => 'Kabupaten Pemalang',
		        					'code' => 'PML',
	        					],
								[
		        					'name' => 'Kabupaten Tegal',
		        					'code' => 'SLW',
	        					],
								[
		        					'name' => 'Kabupaten Brebes',
		        					'code' => 'BBS',
	        					],
								[
		        					'name' => 'Kota Magelang',
		        					'code' => 'MGG',
	        					],
								[
		        					'name' => 'Kota Surakarta',
		        					'code' => 'SKT',
	        					],
								[
		        					'name' => 'Kota Salatiga',
		        					'code' => 'SLT',
	        					],
								[
		        					'name' => 'Kota Semarang',
		        					'code' => 'SMG',
	        					],
								[
		        					'name' => 'Kota Pekalongan',
		        					'code' => 'PKL',
	        					],
								[
		        					'name' => 'Kota Tegal',
		        					'code' => 'TGL',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Jawa Timur',
	        				'abbreviation' => 'Jatim',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Pacitan',
		        					'code' => 'PCT',
	        					],
								[
		        					'name' => 'Kabupaten Ponorogo',
		        					'code' => 'PNG',
	        					],
								[
		        					'name' => 'Kabupaten Trenggalek',
		        					'code' => 'TRK',
	        					],
								[
		        					'name' => 'Kabupaten Tulungagung',
		        					'code' => 'TLG',
	        					],
								[
		        					'name' => 'Kabupaten Blitar',
		        					'code' => 'KNR',
	        					],
								[
		        					'name' => 'Kabupaten Kediri',
		        					'code' => 'KDR',
	        					],
								[
		        					'name' => 'Kabupaten Malang',
		        					'code' => 'KPN',
	        					],
								[
		        					'name' => 'Kabupaten Lumajang',
		        					'code' => 'LMJ',
	        					],
								[
		        					'name' => 'Kabupaten Jember',
		        					'code' => 'JMR',
	        					],
								[
		        					'name' => 'Kabupaten Banyuwangi',
		        					'code' => 'BYW',
	        					],
								[
		        					'name' => 'Kabupaten Bondowoso',
		        					'code' => 'BDW',
	        					],
								[
		        					'name' => 'Kabupaten Situbondo',
		        					'code' => 'SIT',
	        					],
								[
		        					'name' => 'Kabupaten Probolinggo',
		        					'code' => 'KRS',
	        					],
								[
		        					'name' => 'Kabupaten Pasuruan',
		        					'code' => 'PSR',
	        					],
								[
		        					'name' => 'Kabupaten Sidoarjo',
		        					'code' => 'SDA',
	        					],
								[
		        					'name' => 'Kabupaten Mojokerto',
		        					'code' => 'MJK',
	        					],
								[
		        					'name' => 'Kabupaten Jombang',
		        					'code' => 'JBG',
	        					],
								[
		        					'name' => 'Kabupaten Nganjuk',
		        					'code' => 'NJK',
	        					],
								[
		        					'name' => 'Kabupaten Madiun',
		        					'code' => 'MJY',
	        					],
								[
		        					'name' => 'Kabupaten Magetan',
		        					'code' => 'MGT',
	        					],
								[
		        					'name' => 'Kabupaten Ngawi',
		        					'code' => 'NGW',
	        					],
								[
		        					'name' => 'Kabupaten Bojonegoro',
		        					'code' => 'BJN',
	        					],
								[
		        					'name' => 'Kabupaten Tuban',
		        					'code' => 'TBN',
	        					],
								[
		        					'name' => 'Kabupaten Lamongan',
		        					'code' => 'LMG',
	        					],
								[
		        					'name' => 'Kabupaten Gresik',
		        					'code' => 'GSK',
	        					],
								[
		        					'name' => 'Kabupaten Bangkalan',
		        					'code' => 'BKL',
	        					],
								[
		        					'name' => 'Kabupaten Sampang',
		        					'code' => 'SPG',
	        					],
								[
		        					'name' => 'Kabupaten Pamekasan',
		        					'code' => 'PMK',
	        					],
								[
		        					'name' => 'Kabupaten Sumenep',
		        					'code' => 'SMP',
	        					],
								[
		        					'name' => 'Kota Kediri',
		        					'code' => 'KDR',
	        					],
								[
		        					'name' => 'Kota Blitar',
		        					'code' => 'BLT',
	        					],
								[
		        					'name' => 'Kota Malang',
		        					'code' => 'MLG',
	        					],
								[
		        					'name' => 'Kota Probolinggo',
		        					'code' => 'PBL',
	        					],
								[
		        					'name' => 'Kota Pasuruan',
		        					'code' => 'PSN',
	        					],
								[
		        					'name' => 'Kota Mojokerto',
		        					'code' => 'MJK',
	        					],
								[
		        					'name' => 'Kota Madiun',
		        					'code' => 'MAD',
	        					],
								[
		        					'name' => 'Kota Surabaya',
		        					'code' => 'SBY',
	        					],
								[
		        					'name' => 'Kota Batu',
		        					'code' => 'BTU',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'DI Yogyakarta',
	        				'abbreviation' => 'DIY',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Kulon Progo',
		        					'code' => 'WAT',
	        					],
								[
		        					'name' => 'Kabupaten Bantul',
		        					'code' => 'BTL',
	        					],
								[
		        					'name' => 'Kabupaten Gunung Kidul',
		        					'code' => 'WNO',
	        					],
								[
		        					'name' => 'Kabupaten Sleman',
		        					'code' => 'SMN',
	        					],
								[
		        					'name' => 'Kota Yogyakarta',
		        					'code' => 'YYK',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Kalimantan',
	        		'provinces' => [
	        			[
	        				'name' => 'Kalimantan Barat',
	        				'abbreviation' => 'Kalbar',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Sambas',
		        					'code' => 'SBS',
	        					],
								[
		        					'name' => 'Kabupaten Bengkayang',
		        					'code' => 'BEK',
	        					],
								[
		        					'name' => 'Kabupaten Landak',
		        					'code' => 'NBA',
	        					],
								[
		        					'name' => 'Kabupaten Mempawah',
		        					'code' => 'MPW',
	        					],
								[
		        					'name' => 'Kabupaten Sanggau',
		        					'code' => 'SAG',
	        					],
								[
		        					'name' => 'Kabupaten Ketapang',
		        					'code' => 'KTP',
	        					],
								[
		        					'name' => 'Kabupaten Sintang',
		        					'code' => 'STG',
	        					],
								[
		        					'name' => 'Kabupaten Kapuas Hulu',
		        					'code' => 'PTS',
	        					],
								[
		        					'name' => 'Kabupaten Sekadau',
		        					'code' => 'SED',
	        					],
								[
		        					'name' => 'Kabupaten Melawi',
		        					'code' => 'NGP',
	        					],
								[
		        					'name' => 'Kabupaten Kayong Utara',
		        					'code' => 'SKD',
	        					],
								[
		        					'name' => 'Kabupaten Kubu Raya',
		        					'code' => 'SRY',
	        					],
								[
		        					'name' => 'Kota Pontianak',
		        					'code' => 'PTK',
	        					],
								[
		        					'name' => 'Kota Singkawang',
		        					'code' => 'SKW',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Kalimantan Selatan',
	        				'abbreviation' => 'Kalsel',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Tanah Laut',
		        					'code' => 'PLI',
	        					],
								[
		        					'name' => 'Kabupaten Kota Baru',
		        					'code' => 'KBR',
	        					],
								[
		        					'name' => 'Kabupaten Banjar',
		        					'code' => 'MTP',
	        					],
								[
		        					'name' => 'Kabupaten Barito Kuala',
		        					'code' => 'MRH',
	        					],
								[
		        					'name' => 'Kabupaten Tapin',
		        					'code' => 'RTA',
	        					],
								[
		        					'name' => 'Kabupaten Hulu Sungai Selatan',
		        					'code' => 'KGN',
	        					],
								[
		        					'name' => 'Kabupaten Hulu Sungai Tengah',
		        					'code' => 'BRB',
	        					],
								[
		        					'name' => 'Kabupaten Hulu Sungai Utara',
		        					'code' => 'AMT',
	        					],
								[
		        					'name' => 'Kabupaten Tabalong',
		        					'code' => 'TJG',
	        					],
								[
		        					'name' => 'Kabupaten Tanah Bumbu',
		        					'code' => 'BLN',
	        					],
								[
		        					'name' => 'Kabupaten Balangan',
		        					'code' => 'PRN',
	        					],
								[
		        					'name' => 'Kota Banjarmasin',
		        					'code' => 'BJM',
	        					],
								[
		        					'name' => 'Kota Banjar Baru',
		        					'code' => 'BJB',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Kalimantan Tengah',
	        				'abbreviation' => 'Kalteng',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Kotawaringin Barat',
		        					'code' => 'PBU',
	        					],
								[
		        					'name' => 'Kabupaten Kotawaringin Timur',
		        					'code' => 'SPT',
	        					],
								[
		        					'name' => 'Kabupaten Kapuas',
		        					'code' => 'KLK',
	        					],
								[
		        					'name' => 'Kabupaten Barito Selatan',
		        					'code' => 'BNT',
	        					],
								[
		        					'name' => 'Kabupaten Barito Utara',
		        					'code' => 'MTW',
	        					],
								[
		        					'name' => 'Kabupaten Sukamara',
		        					'code' => 'SKR',
	        					],
								[
		        					'name' => 'Kabupaten Lamandau',
		        					'code' => 'NGB',
	        					],
								[
		        					'name' => 'Kabupaten Seruyan',
		        					'code' => 'KLP',
	        					],
								[
		        					'name' => 'Kabupaten Katingan',
		        					'code' => 'KSN',
	        					],
								[
		        					'name' => 'Kabupaten Pulang Pisau',
		        					'code' => 'PPS',
	        					],
								[
		        					'name' => 'Kabupaten Gunung Mas',
		        					'code' => 'KKN',
	        					],
								[
		        					'name' => 'Kabupaten Barito Timur',
		        					'code' => 'TML',
	        					],
								[
		        					'name' => 'Kabupaten Murung Raya',
		        					'code' => 'PRC',
	        					],
								[
		        					'name' => 'Kota Palangka Raya',
		        					'code' => 'PLK',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Kalimantan Timur',
	        				'abbreviation' => 'Kaltim',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Paser',
		        					'code' => 'TGT',
	        					],
								[
		        					'name' => 'Kabupaten Kutai Barat',
		        					'code' => 'SDW',
	        					],
								[
		        					'name' => 'Kabupaten Kutai Kartanegara',
		        					'code' => 'TRG',
	        					],
								[
		        					'name' => 'Kabupaten Kutai Timur',
		        					'code' => 'SGT',
	        					],
								[
		        					'name' => 'Kabupaten Berau',
		        					'code' => 'TNR',
	        					],
								[
		        					'name' => 'Kabupaten Penajam Paser Utara',
		        					'code' => 'PNJ',
	        					],
								[
		        					'name' => 'Kabupaten Mahakam Hulu',
		        					'code' => 'UBL',
	        					],
								[
		        					'name' => 'Kota Balikpapan',
		        					'code' => 'BPP',
	        					],
								[
		        					'name' => 'Kota Samarinda',
		        					'code' => 'SMR',
	        					],
								[
		        					'name' => 'Kota Bontang',
		        					'code' => 'BON',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Kalimantan Utara',
	        				'abbreviation' => 'Kalut',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Malinau',
		        					'code' => 'MLN',
	        					],
								[
		        					'name' => 'Kabupaten Bulungan',
		        					'code' => 'TJS',
	        					],
								[
		        					'name' => 'Kabupaten Tana Tidung',
		        					'code' => 'TDP',
	        					],
								[
		        					'name' => 'Kabupaten Nunukan',
		        					'code' => 'NNK',
	        					],
								[
		        					'name' => 'Kota Tarakan',
		        					'code' => 'TAR',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Kepulauan Maluku',
	        		'provinces' => [
	        			[
	        				'name' => 'Maluku',
	        				'abbreviation' => 'Maluku',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Maluku Tenggara Barat',
		        					'code' => 'SML',
	        					],
								[
		        					'name' => 'Kabupaten Maluku Tenggara',
		        					'code' => 'TUL',
	        					],
								[
		        					'name' => 'Kabupaten Maluku Tengah',
		        					'code' => 'MSH',
	        					],
								[
		        					'name' => 'Kabupaten Buru',
		        					'code' => 'NLA',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Aru',
		        					'code' => 'DOB',
	        					],
								[
		        					'name' => 'Kabupaten Seram Bagian Barat',
		        					'code' => 'DRH',
	        					],
								[
		        					'name' => 'Kabupaten Seram Bagian Timur',
		        					'code' => 'DTH',
	        					],
								[
		        					'name' => 'Kabupaten Maluku Barat Daya',
		        					'code' => 'TKR',
	        					],
								[
		        					'name' => 'Kabupaten Buru Selatan',
		        					'code' => 'NMR',
	        					],
								[
		        					'name' => 'Kota Ambon',
		        					'code' => 'AMB',
	        					],
								[
		        					'name' => 'Kota Tual',
		        					'code' => 'TUL',
	        					],
	        				],
	        			],
	        			[
	        				'name' => 'Maluku Utara',
	        				'abbreviation' => 'Maluku Utara',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Halmahera Barat',
		        					'code' => 'JLL',
	        					],
								[
		        					'name' => 'Kabupaten Halmahera Tengah',
		        					'code' => 'WED',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Sula',
		        					'code' => 'SNN',
	        					],
								[
		        					'name' => 'Kabupaten Halmahera Selatan',
		        					'code' => 'LBA',
	        					],
								[
		        					'name' => 'Kabupaten Halmahera Utara',
		        					'code' => 'TOB',
	        					],
								[
		        					'name' => 'Kabupaten Halmahera Timur',
		        					'code' => 'MAB',
	        					],
								[
		        					'name' => 'Kabupaten Pulau Morotai',
		        					'code' => 'MTS',
	        					],
								[
		        					'name' => 'Kabupaten Pulau Taliabu',
		        					'code' => 'BBG',
	        					],
								[
		        					'name' => 'Kota Ternate',
		        					'code' => 'TTE',
	        					],
								[
		        					'name' => 'Kota Tidore Kepulauan',
		        					'code' => 'TDR',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Kepulauan Nusa Tenggara',
	        		'provinces' => [
	        			[
	        				'name' => 'Bali',
	        				'abbreviation' => 'Bali',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Badung',
		        					'code' => 'MGW',
	        					],
								[
		        					'name' => 'Kabupaten Bangli',
		        					'code' => 'BLI',
	        					],
								[
		        					'name' => 'Kabupaten Buleleng',
		        					'code' => 'SGR',
	        					],
								[
		        					'name' => 'Kabupaten Gianyar',
		        					'code' => 'GIN',
	        					],
								[
		        					'name' => 'Kabupaten Jembrana',
		        					'code' => 'NGA',
	        					],
								[
		        					'name' => 'Kabupaten Karang Asem',
		        					'code' => 'KRA',
	        					],
								[
		        					'name' => 'Kabupaten Klungkung',
		        					'code' => 'SRP',
	        					],
								[
		        					'name' => 'Kabupaten Tabanan',
		        					'code' => 'TAB',
	        					],
								[
		        					'name' => 'Kota Denpasar',
		        					'code' => 'DPR',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Nusa Tenggara Barat',
	        				'abbreviation' => 'NTB',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bima',
		        					'code' => 'WHO',
	        					],
								[
		        					'name' => 'Kabupaten Dompu',
		        					'code' => 'DPU',
	        					],
								[
		        					'name' => 'Kabupaten Lombok Barat',
		        					'code' => 'GRG',
	        					],
								[
		        					'name' => 'Kabupaten Lombok Tengah',
		        					'code' => 'PYA',
	        					],
								[
		        					'name' => 'Kabupaten Lombok Timur',
		        					'code' => 'SEL',
	        					],
								[
		        					'name' => 'Kabupaten Lombok Utara',
		        					'code' => 'TJN',
	        					],
								[
		        					'name' => 'Kabupaten Sumbawa',
		        					'code' => 'SBW',
	        					],
								[
		        					'name' => 'Kabupaten Sumbawa Barat',
		        					'code' => 'TLW',
	        					],
								[
		        					'name' => 'Kota Bima',
		        					'code' => 'BIM',
	        					],
								[
		        					'name' => 'Kota Mataram',
		        					'code' => 'MTR',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Nusa Tenggara Timur',
	        				'abbreviation' => 'NTT',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Alor',
		        					'code' => 'KLB',
	        					],
								[
		        					'name' => 'Kabupaten Belu',
		        					'code' => 'ATB',
	        					],
								[
		        					'name' => 'Kabupaten Ende',
		        					'code' => 'END',
	        					],
								[
		        					'name' => 'Kabupaten Flores Timur',
		        					'code' => 'LRT',
	        					],
								[
		        					'name' => 'Kabupaten Kupang',
		        					'code' => 'KPG',
	        					],
								[
		        					'name' => 'Kabupaten Lembata',
		        					'code' => 'LWL',
	        					],
								[
		        					'name' => 'Kabupaten Malaka',
		        					'code' => 'BTN',
	        					],
								[
		        					'name' => 'Kabupaten Manggarai',
		        					'code' => 'RTG',
	        					],
								[
		        					'name' => 'Kabupaten Manggarai Barat',
		        					'code' => 'LBJ',
	        					],
								[
		        					'name' => 'Kabupaten Manggarai Timur',
		        					'code' => 'BRG',
	        					],
								[
		        					'name' => 'Kabupaten Nagekeo',
		        					'code' => 'MBY',
	        					],
								[
		        					'name' => 'Kabupaten Ngada',
		        					'code' => 'BJW',
	        					],
								[
		        					'name' => 'Kabupaten Rote Ndao',
		        					'code' => 'BAA',
	        					],
								[
		        					'name' => 'Kabupaten Sabu Raijua',
		        					'code' => 'SBB',
	        					],
								[
		        					'name' => 'Kabupaten Sikka',
		        					'code' => 'MME',
	        					],
								[
		        					'name' => 'Kabupaten Sumba Barat',
		        					'code' => 'WKB',
	        					],
								[
		        					'name' => 'Kabupaten Sumba Barat Daya',
		        					'code' => 'TAM',
	        					],
								[
		        					'name' => 'Kabupaten Sumba Tengah',
		        					'code' => 'WBL',
	        					],
								[
		        					'name' => 'Kabupaten Sumba Timur',
		        					'code' => 'WGP',
	        					],
								[
		        					'name' => 'Kabupaten Timor Tengah Selatan',
		        					'code' => 'SOE',
	        					],
								[
		        					'name' => 'Kabupaten Timor Tengah Utara',
		        					'code' => 'KFM',
	        					],
								[
		        					'name' => 'Kota Kupang',
		        					'code' => 'KPG',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Papua',
	        		'provinces' => [
	        			[
	        				'name' => 'Papua',
	        				'abbreviation' => 'Papua',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Asmat',
		        					'code' => 'AGT',
	        					],
								[
		        					'name' => 'Kabupaten Biak Numfor',
		        					'code' => 'BIK',
	        					],
								[
		        					'name' => 'Kabupaten Boven Digoel',
		        					'code' => 'TMR',
	        					],
								[
		        					'name' => 'Kabupaten Deiyai',
		        					'code' => 'TIG',
	        					],
								[
		        					'name' => 'Kabupaten Dogiyai',
		        					'code' => 'KGM',
	        					],
								[
		        					'name' => 'Kabupaten Intan Jaya',
		        					'code' => 'SGP',
	        					],
								[
		        					'name' => 'Kabupaten Jayapura',
		        					'code' => 'JAP',
	        					],
								[
		        					'name' => 'Kabupaten Jayawijaya',
		        					'code' => 'WAM',
	        					],
								[
		        					'name' => 'Kabupaten Keerom',
		        					'code' => 'WRS',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Yapen',
		        					'code' => 'SRU',
	        					],
								[
		        					'name' => 'Kabupaten Lanny Jaya',
		        					'code' => 'TOM',
	        					],
								[
		        					'name' => 'Kabupaten Mamberamo Raya',
		        					'code' => 'BRM',
	        					],
								[
		        					'name' => 'Kabupaten Mamberamo Tengah',
		        					'code' => 'KBK',
	        					],
								[
		        					'name' => 'Kabupaten Mappi',
		        					'code' => 'KEP',
	        					],
								[
		        					'name' => 'Kabupaten Merauke',
		        					'code' => 'MRK',
	        					],
								[
		        					'name' => 'Kabupaten Mimika',
		        					'code' => 'TIM',
	        					],
								[
		        					'name' => 'Kabupaten Nabire',
		        					'code' => 'NAB',
	        					],
								[
		        					'name' => 'Kabupaten Nduga',
		        					'code' => 'KYM',
	        					],
								[
		        					'name' => 'Kabupaten Paniai',
		        					'code' => 'ERT',
	        					],
								[
		        					'name' => 'Kabupaten Pegunungan Bintang',
		        					'code' => 'OSB',
	        					],
								[
		        					'name' => 'Kabupaten Puncak',
		        					'code' => 'ILG',
	        					],
								[
		        					'name' => 'Kabupaten Puncak Jaya',
		        					'code' => 'MUL',
	        					],
								[
		        					'name' => 'Kabupaten Sarmi',
		        					'code' => 'SMI',
	        					],
								[
		        					'name' => 'Kabupaten Supiori',
		        					'code' => 'SRW',
	        					],
								[
		        					'name' => 'Kabupaten Tolikara',
		        					'code' => 'KBG',
	        					],
								[
		        					'name' => 'Kabupaten Waropen',
		        					'code' => 'BTW',
	        					],
								[
		        					'name' => 'Kabupaten Yahukimo',
		        					'code' => 'SMH',
	        					],
								[
		        					'name' => 'Kabupaten Yalimo',
		        					'code' => 'ELL',
	        					],
								[
		        					'name' => 'Kota Jayapura',
		        					'code' => 'JAP',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Papua Barat',
	        				'abbreviation' => 'Papua Barat',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Fakfak',
		        					'code' => 'FFK',
	        					],
								[
		        					'name' => 'Kabupaten Kaimana',
		        					'code' => 'KMN',
	        					],
								[
		        					'name' => 'Kabupaten Manokwari',
		        					'code' => 'MNK',
	        					],
								[
		        					'name' => 'Kabupaten Manokwari Selatan',
		        					'code' => 'RSK',
	        					],
								[
		        					'name' => 'Kabupaten Maybrat',
		        					'code' => 'AFT',
	        					],
								[
		        					'name' => 'Kabupaten Pegunungan Arfak',
		        					'code' => 'ANG',
	        					],
								[
		        					'name' => 'Kabupaten Raja Ampat',
		        					'code' => 'WAS',
	        					],
								[
		        					'name' => 'Kabupaten Sorong',
		        					'code' => 'AMS',
	        					],
								[
		        					'name' => 'Kabupaten Sorong Selatan',
		        					'code' => 'TMB',
	        					],
								[
		        					'name' => 'Kabupaten Tambrauw',
		        					'code' => 'FEF',
	        					],
								[
		        					'name' => 'Kabupaten Teluk Bintuni',
		        					'code' => 'BTI',
	        					],
								[
		        					'name' => 'Kabupaten Teluk Wondama',
		        					'code' => 'RAS',
	        					],
								[
		        					'name' => 'Kota Sorong',
		        					'code' => 'SON',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Sulawesi',
	        		'provinces' => [
	        			[
	        				'name' => 'Gorontalo',
	        				'abbreviation' => 'Gorontalo',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Boalemo',
		        					'code' => 'TMT',
	        					],
	        					[
		        					'name' => 'Kabupaten Bone Bolango',
		        					'code' => 'SWW',
	        					],
	        					[
		        					'name' => 'Kabupaten Gorontalo',
		        					'code' => 'GTO',
	        					],
	        					[
		        					'name' => 'Kabupaten Gorontalo Utara',
		        					'code' => 'KWD',
	        					],
	        					[
		        					'name' => 'Kabupaten Pohuwato',
		        					'code' => 'MAR',
	        					],
	        					[
		        					'name' => 'Kota Gorontalo',
		        					'code' => 'GTO',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sulawesi Barat',
	        				'abbreviation' => 'Sulbar',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Majene',
		        					'code' => 'MJN',
	        					],
								[
		        					'name' => 'Kabupaten Mamasa',
		        					'code' => 'MMS',
	        					],
								[
		        					'name' => 'Kabupaten Mamuju',
		        					'code' => 'MAM',
	        					],
								[
		        					'name' => 'Kabupaten Mamuju Tengah',
		        					'code' => 'TBD',
	        					],
								[
		        					'name' => 'Kabupaten Mamuju Utara',
		        					'code' => 'PKY',
	        					],
								[
		        					'name' => 'Kabupaten Polewali Mandar',
		        					'code' => 'PLW',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sulawesi Selatan',
	        				'abbreviation' => 'Sulsel',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bantaeng',
		        					'code' => 'BAN',
	        					],
								[
		        					'name' => 'Kabupaten Barru',
		        					'code' => 'BAR',
	        					],
								[
		        					'name' => 'Kabupaten Bone',
		        					'code' => 'WTP',
	        					],
								[
		        					'name' => 'Kabupaten Bulukumba',
		        					'code' => 'BLK',
	        					],
								[
		        					'name' => 'Kabupaten Enrekang',
		        					'code' => 'ENR',
	        					],
								[
		        					'name' => 'Kabupaten Gowa',
		        					'code' => 'SGM',
	        					],
								[
		        					'name' => 'Kabupaten Jeneponto',
		        					'code' => 'JNP',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Selayar',
		        					'code' => 'BEN',
	        					],
								[
		        					'name' => 'Kabupaten Luwu',
		        					'code' => 'PLP',
	        					],
								[
		        					'name' => 'Kabupaten Luwu Timur',
		        					'code' => 'MLL',
	        					],
								[
		        					'name' => 'Kabupaten Luwu Utara',
		        					'code' => 'MSB',
	        					],
								[
		        					'name' => 'Kabupaten Maros',
		        					'code' => 'MRS',
	        					],
								[
		        					'name' => 'Kabupaten Pangkajene Dan Kepulauan',
		        					'code' => 'PKJ',
	        					],
								[
		        					'name' => 'Kabupaten Pinrang',
		        					'code' => 'PIN',
	        					],
								[
		        					'name' => 'Kabupaten Sidenreng Rappang',
		        					'code' => 'SDR',
	        					],
								[
		        					'name' => 'Kabupaten Sinjai',
		        					'code' => 'SNJ',
	        					],
								[
		        					'name' => 'Kabupaten Soppeng',
		        					'code' => 'WNS',
	        					],
								[
		        					'name' => 'Kabupaten Takalar',
		        					'code' => 'TKA',
	        					],
								[
		        					'name' => 'Kabupaten Tana Toraja',
		        					'code' => 'MAK',
	        					],
								[
		        					'name' => 'Kabupaten Toraja Utara',
		        					'code' => 'RTP',
	        					],
								[
		        					'name' => 'Kabupaten Wajo',
		        					'code' => 'SKG',
	        					],
								[
		        					'name' => 'Kota Makassar',
		        					'code' => 'MKS',
	        					],
								[
		        					'name' => 'Kota Palopo',
		        					'code' => 'PLP',
	        					],
								[
		        					'name' => 'Kota Parepare',
		        					'code' => 'PRE',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sulawesi Tengah',
	        				'abbreviation' => 'Sulteng',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Banggai',
		        					'code' => 'LWK',
	        					],
								[
		        					'name' => 'Kabupaten Banggai Kepulauan',
		        					'code' => 'SKN',
	        					],
								[
		        					'name' => 'Kabupaten Banggai Laut',
		        					'code' => 'BGI',
	        					],
								[
		        					'name' => 'Kabupaten Buol',
		        					'code' => 'BUL',
	        					],
								[
		        					'name' => 'Kabupaten Donggala',
		        					'code' => 'DGL',
	        					],
								[
		        					'name' => 'Kabupaten Morowali',
		        					'code' => 'BGK',
	        					],
								[
		        					'name' => 'Kabupaten Morowali Utara',
		        					'code' => 'KLD',
	        					],
								[
		        					'name' => 'Kabupaten Parigi Moutong',
		        					'code' => 'PRG',
	        					],
								[
		        					'name' => 'Kabupaten Poso',
		        					'code' => 'PSO',
	        					],
								[
		        					'name' => 'Kabupaten Sigi',
		        					'code' => 'SGB',
	        					],
								[
		        					'name' => 'Kabupaten Tojo Una-Una',
		        					'code' => 'APN',
	        					],
								[
		        					'name' => 'Kabupaten Toli-Toli',
		        					'code' => 'TLI',
	        					],
								[
		        					'name' => 'Kota Palu',
		        					'code' => 'PAL',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sulawesi Tenggara',
	        				'abbreviation' => 'Sultra',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bombana',
		        					'code' => 'RMB',
	        					],
								[
		        					'name' => 'Kabupaten Buton',
		        					'code' => 'PSW',
	        					],
								[
		        					'name' => 'Kabupaten Buton Selatan',
		        					'code' => 'BGA',
	        					],
								[
		        					'name' => 'Kabupaten Buton Tengah',
		        					'code' => 'LBK',
	        					],
								[
		        					'name' => 'Kabupaten Buton Utara',
		        					'code' => 'BNG',
	        					],
								[
		        					'name' => 'Kabupaten Kolaka',
		        					'code' => 'KKA',
	        					],
								[
		        					'name' => 'Kabupaten Kolaka Timur',
		        					'code' => 'TWT',
	        					],
								[
		        					'name' => 'Kabupaten Kolaka Utara',
		        					'code' => 'LSS',
	        					],
								[
		        					'name' => 'Kabupaten Konawe',
		        					'code' => 'UNH',
	        					],
								[
		        					'name' => 'Kabupaten Konawe Kepulauan',
		        					'code' => 'LGR',
	        					],
								[
		        					'name' => 'Kabupaten Konawe Selatan',
		        					'code' => 'ADL',
	        					],
								[
		        					'name' => 'Kabupaten Konawe Utara',
		        					'code' => 'WGD',
	        					],
								[
		        					'name' => 'Kabupaten Muna',
		        					'code' => 'RAH',
	        					],
								[
		        					'name' => 'Kabupaten Muna Barat',
		        					'code' => 'LWR',
	        					],
								[
		        					'name' => 'Kabupaten Wakatobi',
		        					'code' => 'WGW',
	        					],
								[
		        					'name' => 'Kota Baubau',
		        					'code' => 'BAU',
	        					],
								[
		        					'name' => 'Kota Kendari',
		        					'code' => 'KDI',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sulawesi Utara',
	        				'abbreviation' => 'Sulut',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bolaang Mongondow',
		        					'code' => 'LLK',
	        					],
								[
		        					'name' => 'Kabupaten Bolaang Mongondow Selatan',
		        					'code' => 'BLU',
	        					],
								[
		        					'name' => 'Kabupaten Bolaang Mongondow Timur',
		        					'code' => 'TTY',
	        					],
								[
		        					'name' => 'Kabupaten Bolaang Mongondow Utara',
		        					'code' => 'BRK',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Sangihe',
		        					'code' => 'THN',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Talaud',
		        					'code' => 'MGN',
	        					],
								[
		        					'name' => 'Kabupaten Minahasa',
		        					'code' => 'TNN',
	        					],
								[
		        					'name' => 'Kabupaten Minahasa Selatan',
		        					'code' => 'AMR',
	        					],
								[
		        					'name' => 'Kabupaten Minahasa Tenggara',
		        					'code' => 'RTN',
	        					],
								[
		        					'name' => 'Kabupaten Minahasa Utara',
		        					'code' => 'ARM',
	        					],
								[
		        					'name' => 'Kabupaten Siau Tagulandang Biaro',
		        					'code' => 'ODS',
	        					],
								[
		        					'name' => 'Kota Bitung',
		        					'code' => 'BIT',
	        					],
								[
		        					'name' => 'Kota Kotamobagu',
		        					'code' => 'KTG',
	        					],
								[
		        					'name' => 'Kota Manado',
		        					'code' => 'MND',
	        					],
								[
		        					'name' => 'Kota Tomohon',
		        					'code' => 'TMH',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        	[
        		'island' => [
        			'name' => 'Sumatera',
	        		'provinces' => [
	        			[
	        				'name' => 'Aceh',
	        				'abbreviation' => 'Aceh',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Aceh Barat',
		        					'code' => 'MBO',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Barat Daya',
		        					'code' => 'BPD',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Besar',
		        					'code' => 'JTH',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Jaya',
		        					'code' => 'CAG',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Selatan',
		        					'code' => 'TTN',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Singkil',
		        					'code' => 'SKL',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Tamiang',
		        					'code' => 'KRB',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Tengah',
		        					'code' => 'TKN',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Tenggara',
		        					'code' => 'KTN',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Timur',
		        					'code' => 'LGS',
	        					],
								[
		        					'name' => 'Kabupaten Aceh Utara',
		        					'code' => 'LSK',
	        					],
								[
		        					'name' => 'Kabupaten Bener Meriah',
		        					'code' => 'STR',
	        					],
								[
		        					'name' => 'Kabupaten Bireuen',
		        					'code' => 'BIR',
	        					],
								[
		        					'name' => 'Kabupaten Gayo Lues',
		        					'code' => 'BKJ',
	        					],
								[
		        					'name' => 'Kabupaten Nagan Raya',
		        					'code' => 'SKM',
	        					],
								[
		        					'name' => 'Kabupaten Pidie',
		        					'code' => 'SGI',
	        					],
								[
		        					'name' => 'Kabupaten Pidie Jaya',
		        					'code' => 'MRN',
	        					],
								[
		        					'name' => 'Kabupaten Simeulue',
		        					'code' => 'SNB',
	        					],
								[
		        					'name' => 'Kota Banda Aceh',
		        					'code' => 'BNA',
	        					],
								[
		        					'name' => 'Kota Langsa',
		        					'code' => 'LGS',
	        					],
								[
		        					'name' => 'Kota Lhokseumawe',
		        					'code' => 'LSM',
	        					],
								[
		        					'name' => 'Kota Sabang',
		        					'code' => 'SAB',
	        					],
								[
		        					'name' => 'Kota Subulussalam',
		        					'code' => 'SUS',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Bengkulu',
	        				'abbreviation' => 'Bengkulu',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bengkulu Selatan',
		        					'code' => 'MNA',
	        					],
								[
		        					'name' => 'Kabupaten Bengkulu Tengah',
		        					'code' => 'KRT',
	        					],
								[
		        					'name' => 'Kabupaten Bengkulu Utara',
		        					'code' => 'AGM',
	        					],
								[
		        					'name' => 'Kabupaten Kaur',
		        					'code' => 'BHN',
	        					],
								[
		        					'name' => 'Kabupaten Kepahiang',
		        					'code' => 'KPH',
	        					],
								[
		        					'name' => 'Kabupaten Lebong',
		        					'code' => 'TUB',
	        					],
								[
		        					'name' => 'Kabupaten Mukomuko',
		        					'code' => 'MKM',
	        					],
								[
		        					'name' => 'Kabupaten Rejang Lebong',
		        					'code' => 'CRP',
	        					],
								[
		        					'name' => 'Kabupaten Seluma',
		        					'code' => 'TAS',
	        					],
								[
		        					'name' => 'Kota Bengkulu',
		        					'code' => 'BGL',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Jambi',
	        				'abbreviation' => 'Jambi',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Batang Hari',
		        					'code' => 'MBN',
	        					],
								[
		        					'name' => 'Kabupaten Bungo',
		        					'code' => 'MRB',
	        					],
								[
		        					'name' => 'Kabupaten Kerinci',
		        					'code' => 'SPN',
	        					],
								[
		        					'name' => 'Kabupaten Merangin',
		        					'code' => 'BKO',
	        					],
								[
		        					'name' => 'Kabupaten Muaro Jambi',
		        					'code' => 'SNT',
	        					],
								[
		        					'name' => 'Kabupaten Sarolangun',
		        					'code' => 'SRL',
	        					],
								[
		        					'name' => 'Kabupaten Tanjung Jabung Barat',
		        					'code' => 'KLT',
	        					],
								[
		        					'name' => 'Kabupaten Tanjung Jabung Timur',
		        					'code' => 'MSK',
	        					],
								[
		        					'name' => 'Kabupaten Tebo',
		        					'code' => 'MRT',
	        					],
								[
		        					'name' => 'Kota Jambi',
		        					'code' => 'JMB',
	        					],
								[
		        					'name' => 'Kota Sungai Penuh',
		        					'code' => 'SPN',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Kepulauan Bangka Belitung',
	        				'abbreviation' => 'B. Belitung',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bangka',
		        					'code' => 'SGL',
	        					],
								[
		        					'name' => 'Kabupaten Bangka Barat',
		        					'code' => 'MTK',
	        					],
								[
		        					'name' => 'Kabupaten Bangka Selatan',
		        					'code' => 'TBL',
	        					],
								[
		        					'name' => 'Kabupaten Bangka Tengah',
		        					'code' => 'KBA',
	        					],
								[
		        					'name' => 'Kabupaten Belitung',
		        					'code' => 'TDN',
	        					],
								[
		        					'name' => 'Kabupaten Belitung Timur',
		        					'code' => 'MGR',
	        					],
								[
		        					'name' => 'Kota Pangkal Pinang',
		        					'code' => 'PGP',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Kepulauan Riau',
	        				'abbreviation' => 'Kp. Riau',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bintan',
		        					'code' => 'BSB',
	        					],
	        					[
		        					'name' => 'Kabupaten Karimun',
		        					'code' => 'TBK',
	        					],
	        					[
		        					'name' => 'Kabupaten Kepulauan Anambas',
		        					'code' => 'TRP',
	        					],
	        					[
		        					'name' => 'Kabupaten Lingga',
		        					'code' => 'DKL',
	        					],
	        					[
		        					'name' => 'Kabupaten Natuna',
		        					'code' => 'RAN',
	        					],
	        					[
		        					'name' => 'Kota Batam',
		        					'code' => 'BTM',
	        					],
	        					[
		        					'name' => 'Kota Tanjung Pinang',
		        					'code' => 'TPG',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Lampung',
	        				'abbreviation' => 'Lampung',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Lampung Barat',
		        					'code' => 'LIW',
	        					],
								[
		        					'name' => 'Kabupaten Lampung Selatan',
		        					'code' => 'KLA',
	        					],
								[
		        					'name' => 'Kabupaten Lampung Tengah',
		        					'code' => 'GNS',
	        					],
								[
		        					'name' => 'Kabupaten Lampung Timur',
		        					'code' => 'SDN',
	        					],
								[
		        					'name' => 'Kabupaten Lampung Utara',
		        					'code' => 'KTB',
	        					],
								[
		        					'name' => 'Kabupaten Mesuji',
		        					'code' => 'MSJ',
	        					],
								[
		        					'name' => 'Kabupaten Pesawaran',
		        					'code' => 'GDT',
	        					],
								[
		        					'name' => 'Kabupaten Pesisir Barat',
		        					'code' => 'KRU',
	        					],
								[
		        					'name' => 'Kabupaten Pringsewu',
		        					'code' => 'PRW',
	        					],
								[
		        					'name' => 'Kabupaten Tanggamus',
		        					'code' => 'KOT',
	        					],
								[
		        					'name' => 'Kabupaten Tulang Bawang Barat',
		        					'code' => 'TWG',
	        					],
								[
		        					'name' => 'Kabupaten Tulangbawang',
		        					'code' => 'MGL',
	        					],
								[
		        					'name' => 'Kabupaten Way Kanan',
		        					'code' => 'BBU',
	        					],
								[
		        					'name' => 'Kota Bandar Lampung',
		        					'code' => 'BDL',
	        					],
								[
		        					'name' => 'Kota Metro',
		        					'code' => 'MET',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Riau',
	        				'abbreviation' => 'Riau',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Bengkalis',
		        					'code' => 'BLS',
	        					],
								[
		        					'name' => 'Kabupaten Indragiri Hilir',
		        					'code' => 'TBH',
	        					],
								[
		        					'name' => 'Kabupaten Indragiri Hulu',
		        					'code' => 'RGT',
	        					],
								[
		        					'name' => 'Kabupaten Kampar',
		        					'code' => 'BKN',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Meranti',
		        					'code' => 'TTG',
	        					],
								[
		        					'name' => 'Kabupaten Kuantan Singingi',
		        					'code' => 'TLK',
	        					],
								[
		        					'name' => 'Kabupaten Pelalawan',
		        					'code' => 'PKK',
	        					],
								[
		        					'name' => 'Kabupaten Rokan Hilir',
		        					'code' => 'UJT',
	        					],
								[
		        					'name' => 'Kabupaten Rokan Hulu',
		        					'code' => 'PRP',
	        					],
								[
		        					'name' => 'Kabupaten Siak',
		        					'code' => 'SAK',
	        					],
								[
		        					'name' => 'Kota Dumai',
		        					'code' => 'DUM',
	        					],
								[
		        					'name' => 'Kota Pekanbaru',
		        					'code' => 'PBR',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sumatera Barat',
	        				'abbreviation' => 'Sumbar',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Agam',
		        					'code' => 'LBB',
	        					],
								[
		        					'name' => 'Kabupaten Dharmasraya',
		        					'code' => 'PLJ',
	        					],
								[
		        					'name' => 'Kabupaten Kepulauan Mentawai',
		        					'code' => 'TPT',
	        					],
								[
		        					'name' => 'Kabupaten Lima Puluh Kota',
		        					'code' => 'SRK',
	        					],
								[
		        					'name' => 'Kabupaten Padang Pariaman',
		        					'code' => 'NPM',
	        					],
								[
		        					'name' => 'Kabupaten Pasaman',
		        					'code' => 'LBS',
	        					],
								[
		        					'name' => 'Kabupaten Pasaman Barat',
		        					'code' => 'SPE',
	        					],
								[
		        					'name' => 'Kabupaten Pesisir Selatan',
		        					'code' => 'PNN',
	        					],
								[
		        					'name' => 'Kabupaten Sijunjung',
		        					'code' => 'MRJ',
	        					],
								[
		        					'name' => 'Kabupaten Solok',
		        					'code' => 'ARS',
	        					],
								[
		        					'name' => 'Kabupaten Solok Selatan',
		        					'code' => 'PDA',
	        					],
								[
		        					'name' => 'Kabupaten Tanah Datar',
		        					'code' => 'BSK',
	        					],
								[
		        					'name' => 'Kota Bukittinggi',
		        					'code' => 'BKT',
	        					],
								[
		        					'name' => 'Kota Padang',
		        					'code' => 'PAD',
	        					],
								[
		        					'name' => 'Kota Padang Panjang',
		        					'code' => 'PDP',
	        					],
								[
		        					'name' => 'Kota Pariaman',
		        					'code' => 'PMN',
	        					],
								[
		        					'name' => 'Kota Payakumbuh',
		        					'code' => 'PYH',
	        					],
								[
		        					'name' => 'Kota Sawah Lunto',
		        					'code' => 'SWL',
	        					],
								[
		        					'name' => 'Kota Solok',
		        					'code' => 'SLK',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sumatera Selatan',
	        				'abbreviation' => 'Sumsel',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Banyu Asin',
		        					'code' => 'PKB',
	        					],
								[
		        					'name' => 'Kabupaten Empat Lawang',
		        					'code' => 'TBG',
	        					],
								[
		        					'name' => 'Kabupaten Lahat',
		        					'code' => 'LHT',
	        					],
								[
		        					'name' => 'Kabupaten Muara Enim',
		        					'code' => 'MRE',
	        					],
								[
		        					'name' => 'Kabupaten Musi Banyuasin',
		        					'code' => 'SKY',
	        					],
								[
		        					'name' => 'Kabupaten Musi Rawas',
		        					'code' => 'MBL',
	        					],
								[
		        					'name' => 'Kabupaten Musi Rawas Utara',
		        					'code' => 'RUP',
	        					],
								[
		        					'name' => 'Kabupaten Ogan Ilir',
		        					'code' => 'IDL',
	        					],
								[
		        					'name' => 'Kabupaten Ogan Komering Ilir',
		        					'code' => 'KAG',
	        					],
								[
		        					'name' => 'Kabupaten Ogan Komering Ulu',
		        					'code' => 'BTA',
	        					],
								[
		        					'name' => 'Kabupaten Ogan Komering Ulu Selatan',
		        					'code' => 'MRD',
	        					],
								[
		        					'name' => 'Kabupaten Ogan Komering Ulu Timur',
		        					'code' => 'MPR',
	        					],
								[
		        					'name' => 'Kabupaten Penukal Abab Lematang Ilir',
		        					'code' => 'TBI',
	        					],
								[
		        					'name' => 'Kota Lubuklinggau',
		        					'code' => 'LLG',
	        					],
								[
		        					'name' => 'Kota Pagar Alam',
		        					'code' => 'PGA',
	        					],
								[
		        					'name' => 'Kota Palembang',
		        					'code' => 'PLG',
	        					],
								[
		        					'name' => 'Kota Prabumulih',
		        					'code' => 'PBM',
	        					],
	        				],
	        			],
						[
	        				'name' => 'Sumatera Utara',
	        				'abbreviation' => 'Sumut',
	        				'regencies' => [
	        					[
		        					'name' => 'Kabupaten Asahan',
		        					'code' => 'KIS',
	        					],
								[
		        					'name' => 'Kabupaten Batu Bara',
		        					'code' => 'LMP',
	        					],
								[
		        					'name' => 'Kabupaten Dairi',
		        					'code' => 'SDK',
	        					],
								[
		        					'name' => 'Kabupaten Deli Serdang',
		        					'code' => 'LBP',
	        					],
								[
		        					'name' => 'Kabupaten Humbang Hasundutan',
		        					'code' => 'DLS',
	        					],
								[
		        					'name' => 'Kabupaten Karo',
		        					'code' => 'KBJ',
	        					],
								[
		        					'name' => 'Kabupaten Labuhan Batu',
		        					'code' => 'RAP',
	        					],
								[
		        					'name' => 'Kabupaten Labuhan Batu Selatan',
		        					'code' => 'KPI',
	        					],
								[
		        					'name' => 'Kabupaten Labuhan Batu Utara',
		        					'code' => 'AKK',
	        					],
								[
		        					'name' => 'Kabupaten Langkat',
		        					'code' => 'STB',
	        					],
								[
		        					'name' => 'Kabupaten Mandailing Natal',
		        					'code' => 'PYB',
	        					],
								[
		        					'name' => 'Kabupaten Nias',
		        					'code' => 'GST',
	        					],
								[
		        					'name' => 'Kabupaten Nias Barat',
		        					'code' => 'LHM',
	        					],
								[
		        					'name' => 'Kabupaten Nias Selatan',
		        					'code' => 'TLD',
	        					],
								[
		        					'name' => 'Kabupaten Nias Utara',
		        					'code' => 'LTU',
	        					],
								[
		        					'name' => 'Kabupaten Padang Lawas',
		        					'code' => 'SBH',
	        					],
								[
		        					'name' => 'Kabupaten Padang Lawas Utara',
		        					'code' => 'GNT',
	        					],
								[
		        					'name' => 'Kabupaten Pakpak Bharat',
		        					'code' => 'SAL',
	        					],
								[
		        					'name' => 'Kabupaten Samosir',
		        					'code' => 'PRR',
	        					],
								[
		        					'name' => 'Kabupaten Serdang Bedagai',
		        					'code' => 'SRH',
	        					],
								[
		        					'name' => 'Kabupaten Simalungun',
		        					'code' => 'PMS',
	        					],
								[
		        					'name' => 'Kabupaten Tapanuli Selatan',
		        					'code' => 'PSP',
	        					],
								[
		        					'name' => 'Kabupaten Tapanuli Tengah',
		        					'code' => 'SBG',
	        					],
								[
		        					'name' => 'Kabupaten Tapanuli Utara',
		        					'code' => 'TRT',
	        					],
								[
		        					'name' => 'Kabupaten Toba Samosir',
		        					'code' => 'BLG',
	        					],
								[
		        					'name' => 'Kota Binjai',
		        					'code' => 'BNJ',
	        					],
								[
		        					'name' => 'Kota Gunungsitoli',
		        					'code' => 'GST',
	        					],
								[
		        					'name' => 'Kota Medan',
		        					'code' => 'MDN',
	        					],
								[
		        					'name' => 'Kota Padangsidimpuan',
		        					'code' => 'PSP',
	        					],
								[
		        					'name' => 'Kota Pematang Siantar',
		        					'code' => 'PMS',
	        					],
								[
		        					'name' => 'Kota Sibolga',
		        					'code' => 'SBG',
	        					],
								[
		        					'name' => 'Kota Tanjung Balai',
		        					'code' => 'TJB',
	        					],
								[
		        					'name' => 'Kota Tebing Tinggi',
		        					'code' => 'TBT',
	        					],
	        				],
	        			],
	        		]
        		],
        	],
        ];

        foreach ($areas as $area) {
        	$island = Island::firstOrCreate(['name' => $area['island']['name']]);
        	foreach ($area['island']['provinces'] as $provinces) {
        		$province = $island->province()->firstOrCreate([
        			'name' => $provinces['name'],
        			'abbreviation' => $provinces['abbreviation']
        		]);
        		foreach ($provinces['regencies'] as $regency) {
        			$province->regency()->firstOrCreate([
        				'name' => $regency['name'],
        				'code' => $regency['code']
        			]);
        		}
        	}
        }
    }
}
