<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Data menu sementara (nanti akan diambil dari database)
        $menuCategories = [
            'snack' => [
                'name' => 'Snack',
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'Keripik Singkong',
                        'description' => 'Keripik singkong renyah dengan bumbu tradisional',
                        'price' => 15000,
                        'image' => 'keripik-singkong.jpg'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Lumpia Semarang',
                        'description' => 'Lumpia segar dengan isi rebung dan udang',
                        'price' => 25000,
                        'image' => 'lumpia-semarang.jpg'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Pisang Goreng',
                        'description' => 'Pisang goreng crispy dengan gula aren',
                        'price' => 12000,
                        'image' => 'pisang-goreng.jpg'
                    ]
                ]
            ],
            'makanan-utama' => [
                'name' => 'Makanan Utama',
                'items' => [
                    [
                        'id' => 4,
                        'name' => 'Nasi Goreng',
                        'description' => 'Nasi goreng spesial dengan telur dan kerupuk',
                        'price' => 35000,
                        'image' => 'nasi-goreng.jpg'
                    ],
                    [
                        'id' => 5,
                        'name' => 'Sate Kelapa',
                        'description' => 'Sate ayam dengan bumbu kelapa bakar',
                        'price' => 30000,
                        'image' => 'sate-kelapa.jpg'
                    ],
                    [
                        'id' => 6,
                        'name' => 'Ayam Bakar',
                        'description' => 'Ayam bakar bumbu kecap dengan lalapan',
                        'price' => 45000,
                        'image' => 'ayam-bakar.jpg'
                    ],
                    [
                        'id' => 7,
                        'name' => 'Ikan Bakar',
                        'description' => 'Ikan bakar segar dengan sambal kecombrang',
                        'price' => 40000,
                        'image' => 'ikan-bakar.jpg'
                    ],
                    [
                        'id' => 8,
                        'name' => 'Mie Aceh',
                        'description' => 'Mie kuah pedas khas Aceh dengan daging',
                        'price' => 38000,
                        'image' => 'mie-aceh.jpg'
                    ],
                    [
                        'id' => 9,
                        'name' => 'Udang Saus Padang',
                        'description' => 'Udang besar dengan saus pedas khas Padang',
                        'price' => 50000,
                        'image' => 'udang-saus-padang.jpg'
                    ]
                ]
            ],
            'minuman' => [
                'name' => 'Minuman',
                'items' => [
                    [
                        'id' => 10,
                        'name' => 'Es Teh Manis',
                        'description' => 'Teh manis segar dengan es batu',
                        'price' => 8000,
                        'image' => 'es-teh-manis.jpg'
                    ],
                    [
                        'id' => 11,
                        'name' => 'Es Jeruk',
                        'description' => 'Jeruk peras segar dengan gula aren',
                        'price' => 12000,
                        'image' => 'es-jeruk.jpg'
                    ],
                    [
                        'id' => 12,
                        'name' => 'Kopi Tubruk',
                        'description' => 'Kopi tradisional Indonesia yang kental',
                        'price' => 10000,
                        'image' => 'kopi-tubruk.jpg'
                    ],
                    [
                        'id' => 13,
                        'name' => 'Es Kelapa Muda',
                        'description' => 'Air kelapa muda segar langsung dari buah',
                        'price' => 15000,
                        'image' => 'es-kelapa-muda.jpg'
                    ]
                ]
            ],
            'dessert' => [
                'name' => 'Dessert',
                'items' => [
                    [
                        'id' => 14,
                        'name' => 'Es Cendol',
                        'description' => 'Cendol dengan santan dan gula merah',
                        'price' => 18000,
                        'image' => 'es-cendol.jpg'
                    ],
                    [
                        'id' => 15,
                        'name' => 'Klepon',
                        'description' => 'Kue klepon dengan gula merah dan kelapa',
                        'price' => 15000,
                        'image' => 'klepon.jpg'
                    ],
                    [
                        'id' => 16,
                        'name' => 'Kolak Pisang',
                        'description' => 'Kolak pisang dengan santan dan gula aren',
                        'price' => 20000,
                        'image' => 'kolak-pisang.jpg'
                    ]
                ]
            ]
        ];

        return view('customer.menu', compact('menuCategories'));
    }
}
