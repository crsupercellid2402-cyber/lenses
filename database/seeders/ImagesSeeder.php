<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ImagesSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->get();

        // Цвета для разных продуктов
        $colors = [
            ['bg' => [52, 152, 219], 'text' => [255, 255, 255]],   // синий
            ['bg' => [46, 204, 113], 'text' => [255, 255, 255]],   // зеленый
            ['bg' => [155, 89, 182], 'text' => [255, 255, 255]],   // фиолетовый
            ['bg' => [241, 196, 15], 'text' => [0, 0, 0]],         // желтый
            ['bg' => [231, 76, 60], 'text' => [255, 255, 255]],    // красный
            ['bg' => [26, 188, 156], 'text' => [255, 255, 255]],   // бирюзовый
        ];

        foreach ($products as $index => $product) {
            $colorScheme = $colors[$index % count($colors)];
            
            // Создаем 2 изображения для каждого продукта
            for ($i = 1; $i <= 2; $i++) {
                $imagePath = $this->createProductImage($product, $i, $colorScheme);
                
                Image::query()->create([
                    'url'            => $imagePath,
                    'imageable_type' => Product::class,
                    'imageable_id'   => $product->id,
                ]);
            }
        }
    }

    private function createProductImage(Product $product, int $number, array $colorScheme): string
    {
        $width = 800;
        $height = 800;
        
        // Создаем изображение
        $image = imagecreatetruecolor($width, $height);
        
        // Выделяем цвета
        $bgColor = imagecolorallocate($image, ...$colorScheme['bg']);
        $textColor = imagecolorallocate($image, ...$colorScheme['text']);
        
        // Заливаем фон
        imagefill($image, 0, 0, $bgColor);
        
        // Добавляем текст
        $text = $product->name;
        $fontSize = 24;
        
        // Рисуем название продукта по центру (грубо)
        $lines = $this->wrapText($text, 30);
        $y = ($height / 2) - (count($lines) * 40 / 2);
        
        foreach ($lines as $line) {
            $bbox = imagettfbbox($fontSize, 0, $this->getDefaultFont(), $line);
            $x = ($width - ($bbox[2] - $bbox[0])) / 2;
            imagettftext($image, $fontSize, 0, (int)$x, (int)$y, $textColor, $this->getDefaultFont(), $line);
            $y += 40;
        }
        
        // Добавляем номер изображения
        imagettftext($image, 18, 0, $width - 60, $height - 20, $textColor, $this->getDefaultFont(), "#{$number}");
        
        // Сохраняем
        $filename = $product->slug . "-{$number}.jpg";
        $path = "products_photos/{$filename}";
        $fullPath = storage_path('app/public/' . $path);
        
        // Создаем директорию если не существует
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        
        imagejpeg($image, $fullPath, 90);
        imagedestroy($image);
        
        return $path;
    }

    private function wrapText(string $text, int $maxChars): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        
        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxChars) {
                $currentLine .= ($currentLine ? ' ' : '') . $word;
            } else {
                if ($currentLine) {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }
        
        if ($currentLine) {
            $lines[] = $currentLine;
        }
        
        return $lines ?: [$text];
    }

    private function getDefaultFont(): string
    {
        // Проверяем разные пути к шрифтам
        $possibleFonts = [
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/Arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];
        
        foreach ($possibleFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }
        
        // Если шрифт не найден, создаем простое изображение без текста TTF
        return '';
    }
}
