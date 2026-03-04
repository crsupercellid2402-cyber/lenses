<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\LandingCarousel;
use Illuminate\Database\Seeder;

class LandingCarouselsSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();

        $banners = [
            [
                'title' => 'Акция на линзы',
                'category_id' => $category?->id,
                'sort_order' => 1,
                'color' => [231, 76, 60], // красный
            ],
            [
                'title' => 'Новая коллекция очков',
                'category_id' => null,
                'sort_order' => 2,
                'color' => [52, 152, 219], // синий
            ],
            [
                'title' => 'Скидка 20% на все',
                'category_id' => null,
                'sort_order' => 3,
                'color' => [46, 204, 113], // зеленый
            ],
        ];

        foreach ($banners as $index => $banner) {
            $imagePath = $this->createBannerImage($banner['title'], $banner['color'], $index + 1);
            
            LandingCarousel::query()->create([
                'image_path'  => $imagePath,
                'category_id' => $banner['category_id'],
                'sort_order'  => $banner['sort_order'],
                'title'       => $banner['title'],
            ]);
        }
    }

    private function createBannerImage(string $title, array $color, int $number): string
    {
        $width = 1200;
        $height = 400;
        
        // Создаем изображение
        $image = imagecreatetruecolor($width, $height);
        
        // Выделяем цвета
        $bgColor = imagecolorallocate($image, ...$color);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        $overlayColor = imagecolorallocatealpha($image, 0, 0, 0, 50);
        
        // Заливаем фон
        imagefill($image, 0, 0, $bgColor);
        
        // Добавляем градиентный эффект (темнее справа)
        for ($x = 0; $x < $width; $x++) {
            $alpha = (int)($x / $width * 60);
            $darkColor = imagecolorallocatealpha($image, 0, 0, 0, $alpha);
            imageline($image, $x, 0, $x, $height, $darkColor);
        }
        
        // Добавляем текст
        $fontSize = 48;
        $font = $this->getDefaultFont();
        
        if ($font) {
            $bbox = imagettfbbox($fontSize, 0, $font, $title);
            $x = ($width - ($bbox[2] - $bbox[0])) / 2;
            $y = ($height - ($bbox[1] - $bbox[7])) / 2;
            
            // Тень текста
            imagettftext($image, $fontSize, 0, (int)$x + 3, (int)$y + 3, imagecolorallocate($image, 0, 0, 0), $font, $title);
            // Основной текст
            imagettftext($image, $fontSize, 0, (int)$x, (int)$y, $textColor, $font, $title);
        }
        
        // Сохраняем
        $filename = "banner{$number}.jpg";
        $path = "carousel/{$filename}";
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

    private function getDefaultFont(): string
    {
        // Проверяем разные пути к шрифтам
        $possibleFonts = [
            'C:/Windows/Fonts/arial.ttf',
            'C:/Windows/Fonts/Arial.ttf',
            'C:/Windows/Fonts/arialbd.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
        ];
        
        foreach ($possibleFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }
        
        return '';
    }
}
