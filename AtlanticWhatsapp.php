<?php
/*
 OFFICIAL WHATSAPP CLASS SCRIPT
 Creator:
    - DHIFOAKSA | FOUNDER OF ATLANTIC-GROUP
    - SHENNBOKU | CHIEF DEVELOPER OF ATLANTIC-GROUP
 */
class WhatsATL
{
    private $apiid;
    private $apikey;
    private $base_url = 'https://atlantic-group.id/api/v1/whatsapp';
    
    public function __construct($uid, $ukey) {
        $this->apiid = $uid;
        $this->apikey = $ukey;
    }
    
    private function connect($sid,$x,$n = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $x['key'] = $this->apikey;
        $x['sid'] = $sid;
        $x['sign'] = md5($this->apiid.$this->apikey);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($x));
        curl_setopt($ch, CURLOPT_URL, $this->base_url.$n);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
    
    public function sendMessage($sid,$phone,$msg) {
        return $this->connect([
            'type' => 'message',
            'phone' => $phone,
            'message' => $msg
        ]);
    }

    public function sendFiles($sid,$phone,$mime,$source,$filename) {
        return $this->connect([
            'type' => 'file',
            'phone' => $phone,
            'filetype' => $mime,
            'source' => base64_encode(file_get_contents($source)),
            'message' => $filename
        ]);
    }

    public function sendLocation($sid,$phone,$lat,$long,$locname) {
        return $this->connect([
            'type' => 'file',
            'phone' => $phone,
            'latitude' => $lat,
            'longtitude' => $long,
            'message' => $filename
        ]);
    }

    public function addUser($sid,$group,$phone,$msg = '-') {
        return $this->connect([
            'type' => 'add_user',
            'phone' => $group,
            'message' => $msg,
            'users' => $phone
        ]);
    }

    public function removeUser($sid,$group,$phone,$msg = '-') {
        return $this->connect([
            'type' => 'remove_user',
            'phone' => $group,
            'message' => '',
            'users' => explode(',', $phone)[0]
        ]);
    }

    public function updateGroupName($sid,$group,$name) {
        return $this->connect([
            'type' => 'update_subject',
            'phone' => $group,
            'message' => $name
        ]);
    }

    public function updateGroupDesc($sid,$group,$desc) {
        return $this->connect([
            'type' => 'update_description',
            'phone' => $group,
            'message' => $desc
        ]);
    }
}

$WATL = new WhatsATL('YOUR API ID', 'YOUR API KEY');
