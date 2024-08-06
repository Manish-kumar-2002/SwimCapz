<?php

namespace App\Models\Admin;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
class Ttf extends Model
{
    use HasFactory;
    public $appends=['preview_path', 'ttf_path'];
    protected $fillable=[
        'title', 'preview' , 'ttf'
    ];
    public function getPreviewPathAttribute()
    {
        if ($this->preview) {
            return url('storage/ttf/preview/'. $this->preview);
        }

        return null;
    }

    public function getTtfPathAttribute()
    {
        if ($this->ttf) {
            return url('storage/'. $this->ttf);
        }

        return null;
    }
    public function generatePreview($request)
    {
        $text = $request->title;
        $fontSize = 100; // Font size

        $fontPath = public_path('storage/'.$this->ttf); // Ensure the font file is placed in the 'public/fonts' directory
        
        /* temp */
        $tempImage = Image::canvas(1, 1);

        $tempImage->text($text, 0, 0, function($font) use ($fontPath, $fontSize) {
            $font->file($fontPath);
            $font->size($fontSize);
        });
        /* end temp */

        // Get text dimensions
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);

        $width = abs($box[4] - $box[0]);
        $height = abs($box[5] - $box[1]);


        // Create a new transparent canvas
        $img = Image::canvas($width, $height);
        // Add text to the image
        $img->text($text, $width/2, $height/2, function($font) use ($fontPath, $fontSize) {
          $font->file($fontPath);
          $font->size($fontSize);
          $font->color([0, 0, 0, 1]); // RGBA, 1 means fully opaque text, set alpha to 0 for fully transparent text
          $font->align('center');
          $font->valign('middle');
      });

      $path=public_path('storage/ttf/preview');
      Helper::manageFolder($path);
      // Save the image
      $file=time().'-ttf-image.png';
      $outputPath = $path.'/'.$file;
      $img->save($outputPath);

      /* update preview */
      $this->update([
          'preview' => $file
      ]);
    }
}
