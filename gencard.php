<?php
      //Set the Content Type
      header('Content-type: image/jpeg');

      // Create Template Image From Existing File
      $template_image = imagecreatefromjpeg("template.jpg");
      $template_width = imagesx($template_image);
      $template_height = imagesy($template_image);

      // Input User Data
      $data_img = "";
      $data_code = "SW-01B7";
      $data_name = "คุณผึ้ง";
      $data_localtion = "กทม. โชคชัย4";
      $data_facebook = "Phannaporn Niamsuwan";
      $data_mobile = "061-6165592";
      $data_line = "peung-siriporn";

      // Initial Text Configuration
      $font_path = 'THSarabunNewBold.ttf';
      $text_poiter = 205;
      $font_size = 35; //Default
      $font_stroke_size = 3;
      // Allocate A Color For The Text
      $font_color = imagecolorallocate($template_image, 255, 127, 127);
      $font_color_stroke = imagecolorallocate($template_image, 250, 250, 250);

      //Calculate Pointer for data_code
      $text_height = 288;
      $pointer = imagettfbbox($font_size, 0, $font_path, $data_code);
      $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_code, $font_stroke_size);

      //Calculate Pointer for data_name
      $text_height = 394;
      $pointer = imagettfbbox($font_size, 0, $font_path, $data_name);
      $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_name, $font_stroke_size);

      //Calculate Pointer for data_localtion
      $text_height = 502;
      $font_auto = 0;
      do {
        $pointer = imagettfbbox($font_size + $font_auto, 0, $font_path, $data_localtion);
        $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
        $font_auto -= 1;
      } while ($text_width > 270);
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size + $font_auto, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_localtion, $font_stroke_size);

      //Calculate Pointer for data_facebook
      $text_height = 610;
      $font_auto = 0;
      do {
        $pointer = imagettfbbox($font_size + $font_auto, 0, $font_path, $data_facebook);
        $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
        $font_auto -= 1;
      } while ($text_width > 270);
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size + $font_auto, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_facebook, $font_stroke_size);

      //Calculate Pointer for data_mobile
      $text_height = 720;
      $pointer = imagettfbbox($font_size, 0, $font_path, $data_mobile);
      $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_mobile, $font_stroke_size);

      //Calculate Pointer for data_line
      $text_height = 825;
      $pointer = imagettfbbox($font_size, 0, $font_path, $data_line);
      $text_width = (abs($pointer[4] - $pointer[0]) * 3) / 4;
      $text_pointer = $text_poiter + ($template_width / 2) - ($text_width / 2);
      imagettfstroketext($template_image, $font_size, 0, $text_pointer, $text_height, $font_color, $font_color_stroke, $font_path, $data_line, $font_stroke_size);

      // Send Image to Browser
      imagejpeg($template_image);
      
      // Clear Memory
      imagedestroy($template_image);

      function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
          for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
              for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++) {
                  imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
                }
         return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
      }
?>
