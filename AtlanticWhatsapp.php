<?php
/*
 OFFICIAL WHATSAPP CLASS SCRIPT
 Creator:
    - DHIFOAKSA | FOUNDER OF ATLANTIC-GROUP
    - SHENNBOKU | CHIEF DEVELOPER OF ATLANTIC-GROUP
 */
class WhatsATL
{
    private $token;
    private $base_url = 'https://api.atlantic-group.id/wa';
    
    public function __construct($token) {
        $this->token = $token;
    }
    
    private function connect($x,$n = '/') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $x['key'] = $this->token;
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($x));
        curl_setopt($ch, CURLOPT_URL, $this->base_url.$n);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    public function getContact() {
        return $this->connect([],'/contact');
    }
    
    public function sendMessage($phone,$msg) {
        return $this->connect([
            'type' => 'message',
            'phone' => $phone,
            'message' => $msg
        ]);
    }

    public function sendFiles($phone,$mime,$source,$filename) {
        return $this->connect([
            'type' => 'file',
            'phone' => $phone,
            'filetype' => $mime,
            'source' => base64_encode(file_get_contents($source)),
            'message' => $filename
        ]);
    }

    public function sendLocation($phone,$lat,$long,$locname) {
        return $this->connect([
            'type' => 'file',
            'phone' => $phone,
            'latitude' => $lat,
            'longtitude' => $long,
            'message' => $filename
        ]);
    }
}

$WATL = new WhatsATL('YOUR API KEY');
