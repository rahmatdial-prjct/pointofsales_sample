<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Branch;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get Cabang Blok A (ID = 1)
        $branch = Branch::find(1);
        
        if (!$branch) {
            $this->command->error('Cabang Blok A tidak ditemukan!');
            return;
        }

        $this->command->info('Membuat kategori dan produk untuk ' . $branch->name . '...');

        // Create Categories for Muslim Women's Clothing Store
        $categories = [
            [
                'name' => 'Hijab & Kerudung',
                'description' => 'Berbagai model hijab dan kerudung untuk muslimah'
            ],
            [
                'name' => 'Gamis & Dress',
                'description' => 'Gamis dan dress muslimah untuk berbagai acara'
            ],
            [
                'name' => 'Tunik & Blouse',
                'description' => 'Tunik dan blouse muslimah casual dan formal'
            ],
            [
                'name' => 'Rok & Celana',
                'description' => 'Rok dan celana muslimah yang syari dan modis'
            ],
            [
                'name' => 'Outer & Cardigan',
                'description' => 'Outer, cardigan, dan jaket untuk muslimah'
            ],
            [
                'name' => 'Aksesoris Muslim',
                'description' => 'Aksesoris pelengkap busana muslimah'
            ]
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'slug' => Str::slug($categoryData['name']),
                    'description' => $categoryData['description'],
                    'is_active' => true
                ]
            );
            $createdCategories[] = $category;
            $this->command->info('✓ Kategori: ' . $category->name);
        }

        // Create Products for each category
        $this->createHijabProducts($branch, $createdCategories[0]);
        $this->createGamisProducts($branch, $createdCategories[1]);
        $this->createTunikProducts($branch, $createdCategories[2]);
        $this->createRokCelanaProducts($branch, $createdCategories[3]);
        $this->createOuterProducts($branch, $createdCategories[4]);
        $this->createAccessoriesProducts($branch, $createdCategories[5]);

        $this->command->info('✅ Selesai! Total produk berhasil dibuat untuk ' . $branch->name);
    }

    private function createHijabProducts($branch, $category)
    {
        $products = [
            ['name' => 'Hijab Segi Empat Voal', 'price' => 35000, 'stock' => 50, 'desc' => 'Hijab segi empat bahan voal premium, lembut dan adem'],
            ['name' => 'Pashmina Ceruti Babydoll', 'price' => 45000, 'stock' => 40, 'desc' => 'Pashmina ceruti babydoll dengan tekstur halus'],
            ['name' => 'Hijab Instan Bergo', 'price' => 25000, 'stock' => 60, 'desc' => 'Hijab instan bergo praktis untuk sehari-hari'],
            ['name' => 'Kerudung Syari Khimar', 'price' => 85000, 'stock' => 30, 'desc' => 'Kerudung syari khimar panjang untuk sholat'],
            ['name' => 'Hijab Segi Empat Katun', 'price' => 28000, 'stock' => 45, 'desc' => 'Hijab segi empat bahan katun yang nyaman'],
            ['name' => 'Pashmina Plisket', 'price' => 38000, 'stock' => 35, 'desc' => 'Pashmina plisket dengan motif elegan'],
            ['name' => 'Hijab Sport Instan', 'price' => 32000, 'stock' => 25, 'desc' => 'Hijab sport instan untuk aktivitas olahraga'],
            ['name' => 'Kerudung Bergo Tali', 'price' => 42000, 'stock' => 38, 'desc' => 'Kerudung bergo dengan tali samping'],
            ['name' => 'Hijab Segi Empat Silk', 'price' => 55000, 'stock' => 20, 'desc' => 'Hijab segi empat bahan silk premium'],
            ['name' => 'Pashmina Rawis Polos', 'price' => 48000, 'stock' => 32, 'desc' => 'Pashmina rawis polos dengan kualitas terbaik'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createGamisProducts($branch, $category)
    {
        $products = [
            ['name' => 'Gamis Syari Polos', 'price' => 185000, 'stock' => 25, 'desc' => 'Gamis syari polos dengan bahan wolfis premium'],
            ['name' => 'Gamis Motif Bunga', 'price' => 165000, 'stock' => 30, 'desc' => 'Gamis dengan motif bunga elegan untuk acara formal'],
            ['name' => 'Dress Muslimah Casual', 'price' => 125000, 'stock' => 35, 'desc' => 'Dress muslimah casual untuk sehari-hari'],
            ['name' => 'Gamis Umbrella Cut', 'price' => 195000, 'stock' => 20, 'desc' => 'Gamis umbrella cut dengan model mengembang'],
            ['name' => 'Maxi Dress Kombinasi', 'price' => 145000, 'stock' => 28, 'desc' => 'Maxi dress dengan kombinasi warna cantik'],
            ['name' => 'Gamis Set Khimar', 'price' => 225000, 'stock' => 18, 'desc' => 'Gamis set lengkap dengan khimar matching'],
            ['name' => 'Dress Brukat Muslimah', 'price' => 175000, 'stock' => 22, 'desc' => 'Dress brukat muslimah untuk acara spesial'],
            ['name' => 'Gamis Terbaru 2024', 'price' => 205000, 'stock' => 15, 'desc' => 'Gamis model terbaru 2024 dengan cutting modern'],
            ['name' => 'Dress Kondangan Muslimah', 'price' => 235000, 'stock' => 12, 'desc' => 'Dress kondangan muslimah mewah dan elegan'],
            ['name' => 'Gamis Lebaran Premium', 'price' => 285000, 'stock' => 10, 'desc' => 'Gamis lebaran premium dengan detail bordir'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createTunikProducts($branch, $category)
    {
        $products = [
            ['name' => 'Tunik Katun Polos', 'price' => 85000, 'stock' => 40, 'desc' => 'Tunik katun polos nyaman untuk sehari-hari'],
            ['name' => 'Blouse Muslimah Formal', 'price' => 95000, 'stock' => 35, 'desc' => 'Blouse muslimah formal untuk kerja'],
            ['name' => 'Tunik Motif Abstrak', 'price' => 75000, 'stock' => 45, 'desc' => 'Tunik dengan motif abstrak modern'],
            ['name' => 'Atasan Tunik Panjang', 'price' => 68000, 'stock' => 50, 'desc' => 'Atasan tunik panjang menutupi pinggul'],
            ['name' => 'Blouse Kerja Muslimah', 'price' => 105000, 'stock' => 30, 'desc' => 'Blouse kerja muslimah dengan model formal'],
            ['name' => 'Tunik Casual Santai', 'price' => 58000, 'stock' => 55, 'desc' => 'Tunik casual untuk santai di rumah'],
            ['name' => 'Atasan Tunik Kombinasi', 'price' => 88000, 'stock' => 38, 'desc' => 'Atasan tunik dengan kombinasi warna'],
            ['name' => 'Blouse Pesta Muslimah', 'price' => 125000, 'stock' => 25, 'desc' => 'Blouse pesta muslimah dengan detail mewah'],
            ['name' => 'Tunik Denim Muslimah', 'price' => 95000, 'stock' => 32, 'desc' => 'Tunik denim muslimah trendy dan stylish'],
            ['name' => 'Atasan Tunik Premium', 'price' => 115000, 'stock' => 28, 'desc' => 'Atasan tunik premium dengan bahan berkualitas'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createRokCelanaProducts($branch, $category)
    {
        $products = [
            ['name' => 'Rok Plisket Panjang', 'price' => 75000, 'stock' => 35, 'desc' => 'Rok plisket panjang syari dan elegan'],
            ['name' => 'Celana Kulot Muslimah', 'price' => 85000, 'stock' => 40, 'desc' => 'Celana kulot muslimah longgar dan nyaman'],
            ['name' => 'Rok A-Line Polos', 'price' => 65000, 'stock' => 45, 'desc' => 'Rok A-line polos dengan model klasik'],
            ['name' => 'Celana Palazzo Syari', 'price' => 95000, 'stock' => 30, 'desc' => 'Celana palazzo syari untuk muslimah'],
            ['name' => 'Rok Span Panjang', 'price' => 58000, 'stock' => 50, 'desc' => 'Rok span panjang elastis dan nyaman'],
            ['name' => 'Celana Kerja Muslimah', 'price' => 105000, 'stock' => 25, 'desc' => 'Celana kerja muslimah formal dan rapi'],
            ['name' => 'Rok Denim Muslimah', 'price' => 88000, 'stock' => 32, 'desc' => 'Rok denim muslimah casual dan trendy'],
            ['name' => 'Celana Training Muslimah', 'price' => 68000, 'stock' => 38, 'desc' => 'Celana training muslimah untuk olahraga'],
            ['name' => 'Rok Umbrella Syari', 'price' => 125000, 'stock' => 20, 'desc' => 'Rok umbrella syari dengan model mengembang'],
            ['name' => 'Celana Cutbray Muslimah', 'price' => 115000, 'stock' => 28, 'desc' => 'Celana cutbray muslimah model terbaru'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createOuterProducts($branch, $category)
    {
        $products = [
            ['name' => 'Cardigan Rajut Muslimah', 'price' => 125000, 'stock' => 30, 'desc' => 'Cardigan rajut muslimah hangat dan stylish'],
            ['name' => 'Blazer Muslimah Formal', 'price' => 165000, 'stock' => 25, 'desc' => 'Blazer muslimah formal untuk kerja'],
            ['name' => 'Outer Kimono Muslimah', 'price' => 95000, 'stock' => 35, 'desc' => 'Outer kimono muslimah dengan model flowing'],
            ['name' => 'Jaket Denim Muslimah', 'price' => 145000, 'stock' => 28, 'desc' => 'Jaket denim muslimah casual dan trendy'],
            ['name' => 'Cardigan Panjang Syari', 'price' => 135000, 'stock' => 32, 'desc' => 'Cardigan panjang syari menutupi aurat'],
            ['name' => 'Outer Vest Muslimah', 'price' => 85000, 'stock' => 40, 'desc' => 'Outer vest muslimah praktis dan modis'],
            ['name' => 'Blazer Casual Muslimah', 'price' => 115000, 'stock' => 35, 'desc' => 'Blazer casual muslimah untuk sehari-hari'],
            ['name' => 'Cardigan Motif Bunga', 'price' => 105000, 'stock' => 38, 'desc' => 'Cardigan dengan motif bunga cantik'],
            ['name' => 'Outer Bolero Muslimah', 'price' => 75000, 'stock' => 42, 'desc' => 'Outer bolero muslimah pendek dan manis'],
            ['name' => 'Jaket Hoodie Muslimah', 'price' => 155000, 'stock' => 22, 'desc' => 'Jaket hoodie muslimah dengan tudung'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createAccessoriesProducts($branch, $category)
    {
        $products = [
            ['name' => 'Bros Hijab Cantik', 'price' => 15000, 'stock' => 60, 'desc' => 'Bros hijab cantik untuk mempercantik hijab'],
            ['name' => 'Tas Selempang Muslimah', 'price' => 85000, 'stock' => 35, 'desc' => 'Tas selempang muslimah praktis dan stylish'],
            ['name' => 'Kaos Kaki Muslimah', 'price' => 12000, 'stock' => 80, 'desc' => 'Kaos kaki muslimah panjang dan nyaman'],
            ['name' => 'Handsock Muslimah', 'price' => 18000, 'stock' => 70, 'desc' => 'Handsock muslimah untuk menutupi tangan'],
            ['name' => 'Ciput Rajut Anti Pusing', 'price' => 25000, 'stock' => 55, 'desc' => 'Ciput rajut anti pusing untuk dalaman hijab'],
            ['name' => 'Tas Tote Bag Muslimah', 'price' => 65000, 'stock' => 40, 'desc' => 'Tas tote bag muslimah untuk sehari-hari'],
            ['name' => 'Gelang Tasbih Cantik', 'price' => 35000, 'stock' => 45, 'desc' => 'Gelang tasbih cantik untuk muslimah'],
            ['name' => 'Sandal Muslimah Tertutup', 'price' => 95000, 'stock' => 30, 'desc' => 'Sandal muslimah tertutup dan nyaman'],
            ['name' => 'Dompet Muslimah Lucu', 'price' => 45000, 'stock' => 50, 'desc' => 'Dompet muslimah dengan desain lucu'],
            ['name' => 'Sepatu Flat Muslimah', 'price' => 125000, 'stock' => 25, 'desc' => 'Sepatu flat muslimah tertutup dan elegan'],
        ];

        foreach ($products as $productData) {
            $this->createProduct($branch, $category, $productData);
        }
    }

    private function createProduct($branch, $category, $data)
    {
        // Generate SKU
        $categoryPrefix = strtoupper(substr($category->name, 0, 3));
        $randomNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $sku = $categoryPrefix . '-' . $randomNumber;

        // Ensure SKU is unique
        while (Product::where('sku', $sku)->exists()) {
            $randomNumber = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $sku = $categoryPrefix . '-' . $randomNumber;
        }

        $product = Product::create([
            'branch_id' => $branch->id,
            'name' => $data['name'],
            'sku' => $sku,
            'category_id' => $category->id,
            'description' => $data['desc'],
            'base_price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => true,
            'image' => null // Placeholder, bisa ditambahkan nanti
        ]);

        $this->command->info('  ✓ ' . $product->name . ' (SKU: ' . $product->sku . ')');
    }
}