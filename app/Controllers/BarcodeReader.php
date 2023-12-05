<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Renderer\Image\Png;
use Imagick;

class BarcodeReader extends Controller
{
    public function readBarcode()
    {
        // Get the uploaded image path or provide the path to your image
        $imagePath = WRITEPATH . '"C:\Users\kaany\Downloads\tickets.pdf"';

        // Create an Imagick object
        $imagick = new Imagick($imagePath);

        // Use BaconQrCode to decode the barcode
        $renderer = new Png();
        $result = QrCode::decode($imagick, $renderer);

        // Output the result
        echo "Decoded Barcode: " . $result;
    }
}
