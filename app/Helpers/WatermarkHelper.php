<?php
namespace App\Helpers;

use App\Models\Setting;

class WatermarkHelper
{
    private static $settingsModel = null;
    
    /**
     * Get watermark settings from database
     *
     * @return array
     */
    public static function getWatermarkSettings()
    {
        if (self::$settingsModel === null) {
            self::$settingsModel = new Setting();
        }
        
        return self::$settingsModel->getWatermarkSettings();
    }
    
    /**
     * Apply watermark to an image
     *
     * @param string $sourceImagePath Path to the source image
     * @param string $outputImagePath Path where the watermarked image should be saved
     * @return bool
     */
    public static function applyWatermarkToImage($sourceImagePath, $outputImagePath)
    {
        $settings = self::getWatermarkSettings();
        
        // If no watermark is configured, just copy the image
        if ($settings['type'] === 'none') {
            return copy($sourceImagePath, $outputImagePath);
        }
        
        // Check if source image exists
        if (!file_exists($sourceImagePath)) {
            return false;
        }
        
        // Get image information
        $imageInfo = getimagesize($sourceImagePath);
        if (!$imageInfo) {
            return false;
        }
        
        // Create image resource based on image type
        $image = false;
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($sourceImagePath);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($sourceImagePath);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($sourceImagePath);
                break;
            default:
                return false;
        }
        
        if (!$image) {
            return false;
        }
        
        // Get image dimensions
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        
        // Create watermark based on settings
        $watermark = self::createWatermark($settings, $imageWidth, $imageHeight);
        if (!$watermark) {
            imagedestroy($image);
            return false;
        }
        
        // Get watermark dimensions
        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);
        
        // Calculate position
        $position = self::calculateWatermarkPosition(
            $settings['position'], 
            $imageWidth, 
            $imageHeight, 
            $watermarkWidth, 
            $watermarkHeight
        );
        
        // Apply transparency to watermark
        $transparency = max(0, min(127, (100 - $settings['transparency']) * 1.27));
        self::applyTransparencyToImage($watermark, $transparency);
        
        // Copy watermark onto image
        imagecopy(
            $image, 
            $watermark, 
            $position['x'], 
            $position['y'], 
            0, 
            0, 
            $watermarkWidth, 
            $watermarkHeight
        );
        
        // Save the watermarked image
        $result = false;
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($image, $outputImagePath, 90);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($image, $outputImagePath);
                break;
            case IMAGETYPE_GIF:
                $result = imagegif($image, $outputImagePath);
                break;
        }
        
        // Clean up resources
        imagedestroy($image);
        imagedestroy($watermark);
        
        return $result;
    }
    
    /**
     * Create watermark based on settings
     *
     * @param array $settings
     * @param int $maxWidth
     * @param int $maxHeight
     * @return resource|false
     */
    private static function createWatermark($settings, $maxWidth, $maxHeight)
    {
        // Limit watermark size to 50% of the image
        $maxWatermarkWidth = $maxWidth * 0.5;
        $maxWatermarkHeight = $maxHeight * 0.5;
        
        if ($settings['type'] === 'logo' || $settings['type'] === 'both') {
            // Try to use school logo
            $logoPath = self::getSchoolLogoPath();
            if ($logoPath && file_exists($logoPath)) {
                $watermark = imagecreatefromstring(file_get_contents($logoPath));
                if ($watermark) {
                    // Resize watermark if needed
                    $watermark = self::resizeImage($watermark, $maxWatermarkWidth, $maxWatermarkHeight);
                    return $watermark;
                }
            }
        }
        
        if ($settings['type'] === 'name' || $settings['type'] === 'both') {
            // Create text watermark
            return self::createTextWatermark($settings, $maxWatermarkWidth, $maxWatermarkHeight);
        }
        
        return false;
    }
    
    /**
     * Get school logo path
     *
     * @return string|false
     */
    private static function getSchoolLogoPath()
    {
        if (self::$settingsModel === null) {
            self::$settingsModel = new Setting();
        }
        
        $settings = self::$settingsModel->getSettings();
        if (!empty($settings['school_logo'])) {
            $logoPath = ROOT_PATH . $settings['school_logo'];
            if (file_exists($logoPath)) {
                return $logoPath;
            }
        }
        
        return false;
    }
    
    /**
     * Create text watermark
     *
     * @param array $settings
     * @param int $maxWidth
     * @param int $maxHeight
     * @return resource
     */
    private static function createTextWatermark($settings, $maxWidth, $maxHeight)
    {
        // Get school name
        if (self::$settingsModel === null) {
            self::$settingsModel = new Setting();
        }
        
        $schoolSettings = self::$settingsModel->getSettings();
        $text = $schoolSettings['school_name'] ?? 'School';
        
        // Create image for text
        $fontSize = min(24, max(12, $maxWidth / 10)); // Adjust font size based on available space
        $fontFile = ROOT_PATH . '/resources/fonts/arial.ttf'; // You may need to adjust this path
        
        // If font file doesn't exist, use a basic approach
        if (!file_exists($fontFile)) {
            // Fallback to basic text creation
            $width = min($maxWidth, 300);
            $height = min($maxHeight, 100);
            $watermark = imagecreate($width, $height);
            
            // Set colors
            $white = imagecolorallocate($watermark, 255, 255, 255);
            $black = imagecolorallocate($watermark, 0, 0, 0);
            imagefilledrectangle($watermark, 0, 0, $width, $height, $white);
            
            // Add text
            $textBox = imageftbbox($fontSize, 0, $fontFile, $text);
            if ($textBox) {
                $textWidth = $textBox[2] - $textBox[0];
                $textHeight = $textBox[1] - $textBox[7];
                $x = ($width - $textWidth) / 2;
                $y = ($height - $textHeight) / 2 + $textHeight;
                imagefttext($watermark, $fontSize, 0, $x, $y, $black, $fontFile, $text);
            } else {
                // Fallback if font functions fail
                imagestring($watermark, 5, 10, 30, $text, $black);
            }
            
            return $watermark;
        }
        
        // Calculate text dimensions
        $textBox = imageftbbox($fontSize, 0, $fontFile, $text);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[1] - $textBox[7];
        
        // Create watermark image
        $width = min($maxWidth, $textWidth + 20);
        $height = min($maxHeight, $textHeight + 20);
        $watermark = imagecreate($width, $height);
        
        // Set colors
        $white = imagecolorallocate($watermark, 255, 255, 255);
        $black = imagecolorallocate($watermark, 0, 0, 0);
        imagefilledrectangle($watermark, 0, 0, $width, $height, $white);
        
        // Add text
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2 + $textHeight;
        imagefttext($watermark, $fontSize, 0, $x, $y, $black, $fontFile, $text);
        
        return $watermark;
    }
    
    /**
     * Calculate watermark position
     *
     * @param string $position
     * @param int $imageWidth
     * @param int $imageHeight
     * @param int $watermarkWidth
     * @param int $watermarkHeight
     * @return array
     */
    private static function calculateWatermarkPosition($position, $imageWidth, $imageHeight, $watermarkWidth, $watermarkHeight)
    {
        $margin = 20; // Margin from edges
        
        switch ($position) {
            case 'top-left':
                return ['x' => $margin, 'y' => $margin];
            case 'top-center':
                return ['x' => ($imageWidth - $watermarkWidth) / 2, 'y' => $margin];
            case 'top-right':
                return ['x' => $imageWidth - $watermarkWidth - $margin, 'y' => $margin];
            case 'middle-left':
                return ['x' => $margin, 'y' => ($imageHeight - $watermarkHeight) / 2];
            case 'center':
                return ['x' => ($imageWidth - $watermarkWidth) / 2, 'y' => ($imageHeight - $watermarkHeight) / 2];
            case 'middle-right':
                return ['x' => $imageWidth - $watermarkWidth - $margin, 'y' => ($imageHeight - $watermarkHeight) / 2];
            case 'bottom-left':
                return ['x' => $margin, 'y' => $imageHeight - $watermarkHeight - $margin];
            case 'bottom-center':
                return ['x' => ($imageWidth - $watermarkWidth) / 2, 'y' => $imageHeight - $watermarkHeight - $margin];
            case 'bottom-right':
                return ['x' => $imageWidth - $watermarkWidth - $margin, 'y' => $imageHeight - $watermarkHeight - $margin];
            default:
                return ['x' => ($imageWidth - $watermarkWidth) / 2, 'y' => ($imageHeight - $watermarkHeight) / 2];
        }
    }
    
    /**
     * Apply transparency to an image
     *
     * @param resource $image
     * @param int $transparency
     * @return void
     */
    private static function applyTransparencyToImage($image, $transparency)
    {
        // For PNG images, we can set alpha blending
        imagealphablending($image, false);
        imagesavealpha($image, true);
        
        // Apply transparency by adjusting alpha channel
        $width = imagesx($image);
        $height = imagesy($image);
        
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                $newAlpha = min(127, $alpha + $transparency);
                $newColor = ($newAlpha << 24) | ($color & 0xFFFFFF);
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
    }
    
    /**
     * Resize image while maintaining aspect ratio
     *
     * @param resource $image
     * @param int $maxWidth
     * @param int $maxHeight
     * @return resource
     */
    private static function resizeImage($image, $maxWidth, $maxHeight)
    {
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Calculate new dimensions while maintaining aspect ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG images
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        
        // Resize image
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        return $newImage;
    }
}