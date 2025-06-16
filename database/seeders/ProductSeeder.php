<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan direktori 'products' tersedia dalam storage
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        // Daftar produk yang akan ditambahkan
        $products = [
            [
                'name' => 'Laptop ASUS ROG Strix',
                'description' => 'Laptop gaming dengan spesifikasi tinggi. Dilengkapi dengan prosesor Intel Core i7, RAM 16GB, SSD 1TB, dan kartu grafis NVIDIA RTX 3060.',
                'price' => 16500000, // Dalam Rupiah
                'stock' => 15,
                'image' => 'products/laptop-asus.jpg'
            ],
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'description' => 'Smartphone flagship dengan kamera 108MP, layar AMOLED 6.8 inch, RAM 12GB, dan penyimpanan internal 256GB.',
                'price' => 12999000, // Dalam Rupiah
                'stock' => 24,
                'image' => 'products/samsung-s23.jpg'
            ],
            [
                'name' => 'Apple MacBook Pro M2',
                'description' => 'Laptop premium dengan chip Apple M2, RAM 16GB, SSD 512GB, dan layar Retina 14 inch yang jernih.',
                'price' => 24500000, // Dalam Rupiah
                'stock' => 8,
                'image' => 'products/macbook-pro.jpg'
            ],
            [
                'name' => 'Headphone Sony WH-1000XM5',
                'description' => 'Headphone nirkabel premium dengan fitur noise cancelling terbaik di kelasnya, baterai tahan hingga 30 jam.',
                'price' => 4800000, // Dalam Rupiah
                'stock' => 30,
                'image' => 'products/sony-headphone.jpg'
            ],
            [
                'name' => 'Monitor LG UltraWide 34"',
                'description' => 'Monitor ultrawide 34 inci dengan resolusi QHD, refresh rate 144Hz, dan teknologi IPS untuk sudut pandang yang luas.',
                'price' => 7500000, // Dalam Rupiah
                'stock' => 12,
                'image' => 'products/lg-monitor.jpg'
            ],
            [
                'name' => 'Kamera Canon EOS R6',
                'description' => 'Kamera mirrorless full-frame 20MP dengan kemampuan merekam video 4K dan sistem autofocus yang cepat dan akurat.',
                'price' => 32000000, // Dalam Rupiah
                'stock' => 5,
                'image' => 'products/canon-eos.jpg'
            ],
            [
                'name' => 'Smartwatch Garmin Fenix 7',
                'description' => 'Smartwatch premium untuk aktivitas luar ruangan dengan GPS, monitor detak jantung, dan baterai yang tahan hingga 18 hari.',
                'price' => 9999000, // Dalam Rupiah
                'stock' => 18,
                'image' => 'products/garmin-watch.jpg'
            ],
            [
                'name' => 'SSD Samsung 980 Pro 2TB',
                'description' => 'SSD NVMe dengan kecepatan baca hingga 7000MB/s dan tulis 5000MB/s. Cocok untuk gaming dan pekerjaan profesional.',
                'price' => 3500000, // Dalam Rupiah
                'stock' => 40,
                'image' => 'products/samsung-ssd.jpg'
            ],
            [
                'name' => 'Printer Epson L3250',
                'description' => 'Printer multifungsi dengan sistem tangki tinta yang ekonomis. Dapat mencetak, memindai, dan menyalin dokumen.',
                'price' => 2800000, // Dalam Rupiah
                'stock' => 22,
                'image' => 'products/epson-printer.jpg'
            ],
            [
                'name' => 'Router Wireless TP-Link Archer AX73',
                'description' => 'Router Wi-Fi 6 dengan kecepatan hingga 5400Mbps, cocok untuk streaming 4K dan gaming online tanpa lag.',
                'price' => 1500000, // Dalam Rupiah
                'stock' => 35,
                'image' => 'products/tplink-router.jpg'
            ]
        ];

        // Masukkan data produk ke database
        foreach ($products as $productData) {
            // Buat produk tanpa gambar terlebih dahulu
            $product = Product::create([
                'name' => $productData['name'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                // Gambar akan ditambahkan nanti
            ]);

            // Proses gambar placeholder
            $this->generatePlaceholderImage($productData['image'], $productData['name']);

            // Update produk dengan path gambar
            $product->update(['image' => $productData['image']]);

            $this->command->info('Produk ditambahkan: ' . $productData['name']);
        }

        $this->command->info('Semua produk berhasil ditambahkan!');
    }

    /**
     * Generate placeholder image with product name
     */
    private function generatePlaceholderImage($path, $name)
    {
        // Extract filename from path
        $filename = basename($path);
        
        // Create directory if it doesn't exist
        Storage::disk('public')->makeDirectory(dirname($path), 0755, true, true);
        
        // Create a simple image
        $width = 800;
        $height = 600;
        $img = \imagecreatetruecolor($width, $height);
        
        // Generate a random background color
        $r = rand(100, 200);
        $g = rand(100, 200);
        $b = rand(100, 200);
        $bg = \imagecolorallocate($img, $r, $g, $b);
        $textColor = \imagecolorallocate($img, 255, 255, 255);
        
        // Fill the background
        \imagefilledrectangle($img, 0, 0, $width, $height, $bg);
        
        // Create a simple icon/shape
        $centerX = $width / 2;
        $centerY = $height / 2 - 50;
        $size = 100;
        
        // Generate a colored rectangle for the product icon
        $iconColor = \imagecolorallocate($img, 255, 255, 255);
        \imagefilledrectangle($img, $centerX - $size/2, $centerY - $size/2, $centerX + $size/2, $centerY + $size/2, $iconColor);
        
        // Add a smaller rectangle inside with the background color for a frame effect
        \imagefilledrectangle($img, 
            $centerX - $size/2 + 10, 
            $centerY - $size/2 + 10, 
            $centerX + $size/2 - 10, 
            $centerY + $size/2 - 10, 
            $bg
        );
        
        // Shorten the text if it's too long
        $displayName = (strlen($name) > 30) ? substr($name, 0, 27) . '...' : $name;
        
        // Get text dimensions for centering
        $fontSize = 5; // Largest built-in font size
        $textWidth = \imagefontwidth($fontSize) * \strlen($displayName);
        
        // Draw product name
        $textX = ($width - $textWidth) / 2;
        $textY = $centerY + $size/2 + 30;
        \imagestring($img, $fontSize, $textX, $textY, $displayName, $textColor);
        
        // Draw category text based on product name
        $categoryText = "";
        if (stripos($name, 'laptop') !== false || stripos($name, 'macbook') !== false) {
            $categoryText = "Kategori: Laptop";
        } elseif (stripos($name, 'phone') !== false || stripos($name, 'smartphone') !== false) {
            $categoryText = "Kategori: Smartphone"; 
        } elseif (stripos($name, 'headphone') !== false) {
            $categoryText = "Kategori: Audio";
        } elseif (stripos($name, 'monitor') !== false) {
            $categoryText = "Kategori: Monitor";
        } elseif (stripos($name, 'kamera') !== false || stripos($name, 'canon') !== false) {
            $categoryText = "Kategori: Kamera";
        } elseif (stripos($name, 'watch') !== false || stripos($name, 'garmin') !== false) {
            $categoryText = "Kategori: Wearable";
        } elseif (stripos($name, 'ssd') !== false) {
            $categoryText = "Kategori: Storage";
        } elseif (stripos($name, 'printer') !== false) {
            $categoryText = "Kategori: Printer";
        } elseif (stripos($name, 'router') !== false) {
            $categoryText = "Kategori: Networking";
        } else {
            $categoryText = "Kategori: Gadget";
        }
        
        $catWidth = \imagefontwidth(3) * \strlen($categoryText);
        $catX = ($width - $catWidth) / 2;
        \imagestring($img, 3, $catX, $textY + 30, $categoryText, $textColor);
        
        // Draw "Product Placeholder" text
        $placeholderText = "Product Placeholder";
        $placeholderWidth = \imagefontwidth(3) * \strlen($placeholderText);
        $placeholderX = ($width - $placeholderWidth) / 2;
        \imagestring($img, 3, $placeholderX, $height - 30, $placeholderText, $textColor);
        
        // Save the image
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        \imagejpeg($img, $tempPath, 90);
        \imagedestroy($img);
        
        // Store the image in public storage
        $fileContent = file_get_contents($tempPath);
        Storage::disk('public')->put($path, $fileContent);
        
        // Clean up temp file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }
}
