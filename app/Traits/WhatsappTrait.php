<?php

namespace App\Traits;

use DB;
use Illuminate\Support\Facades\Http;
use Log;

trait WhatsappTrait
{
    public function kirimPesanWhatsapp($noWa, $message, $fileUrl = null)
    {
        $url = $fileUrl ? 'https://wa.smartappscare.com/send-media' : 'https://wa.smartappscare.com/send-message';

        $api = 'TIXJobMR5VY9QrEGDKSofUnkRNdasW';
        $sender = '6283167627589';

        $data = [
            'api_key' => $api,
            'sender' => $sender,
            'number' => $noWa
        ];

        if ($fileUrl) {
            $data['media_type'] = 'pdf';
            $data['caption'] = $message;
            $data['url'] = $fileUrl;
        } else {
            $data['message'] = $message;
        }

        Log::info('Mengirim pesan WhatsApp ke: ' . $noWa);
        Log::info('File URL: ' . $fileUrl);
        Log::info('API URL: ' . $url);
        Log::info('Data yang dikirim: ' . json_encode($data));

        try {
            $response = Http::timeout(60)->post($url, $data);

            if ($response->successful()) {
                Log::info('Pesan WhatsApp berhasil dikirim ke ' . $noWa);
                return true;
            } else {
                $errorMessage = 'WhatsApp API Error: ' . $response->body();
                Log::error($errorMessage);
                throw new \Exception($errorMessage); // Lemparkan exception jika gagal
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error saat mengirim pesan WhatsApp: ' . $e->getMessage();
            Log::error($errorMessage);
            throw new \Exception($errorMessage); // Lemparkan exception untuk pengelolaan lebih lanjut
        }
    }
}

