<?php
header('content-type: application/json');
require 'AtlanticWhatsapp.php';

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('whatsapp.txt', '['.date('Y-m-d H:i:s')."]\n".json_encode($data)."\n\n", FILE_APPEND);
$message = $data['data'];
$type = $data['type'];

if($type=='chat'){
    if(strtolower($message['message']['pesan']) == 'hai'){
        $result[] = [
            'type' => 'message',
            'data' => [
                'mode' => 'reply',
                'pesan' => 'Hai juga'
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 5) == 'push ') {
        $tujuan = explode('desc ', $message['message']['pesan'])[1];
        $data = explode($tujuan.' ', $message['message']['pesan']);
        $result[] = [
            'type' => 'push_message',
            'data' => [
                'to' => $data[1],
                'pesan' => $data[2]
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'hallo') {
        $result[] = [
            'type' => 'message',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Hai juga'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'gambar') {
        $source = base64_encode(file_get_contents('https://pngimg.com/uploads/whatsapp/whatsapp_PNG21.png'));
        $result[] = [
            'type' => 'file',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Ini Caption',
                'filetype' => 'image/png',
                'source' => $source,
                'name' => 'LogoWA'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'video') {
        $source = base64_encode(file_get_contents('https://scontent-sin6-2.cdninstagram.com/v/t50.2886-16/90322937_2645086652380269_7961807878183959579_n.mp4?_nc_ht=scontent-sin6-2.cdninstagram.com&_nc_cat=109&_nc_ohc=tg4bhl6rFA0AX_c33PV&oe=5E82C5F2&oh=df3faa580a6d07522221832831524e9b'));
        $result[] = [
            'type' => 'file',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Ini Caption',
                'filetype' => 'video/mp4',
                'source' => $source,
                'name' => 'LogoWA'
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 5) == 'desc ') {
        $desc = explode('desc ', $message['message']['pesan'])[1];
        $result[] = [
            'type' => 'desc_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berasil Update Deskripsi',
                'pesan_error' => 'Gagal Update,Hanya Bisa di GROUP!',
                'description' => $desc
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 8) == 'subject ') {
        $sub = explode('subject ', $message['message']['pesan'])[1];
        $result[] = [
            'type' => 'subject_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berasil Update Deskripsi',
                'pesan_error' => 'Gagal Update,Hanya Bisa di GROUP!/Subjek lebih dari 25 char',
                'subject' => $sub
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 5) == 'join ') {
        $inv = explode('join ', $message['message']['pesan'])[1];
        $result[] = [
            'type' => 'join_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berhasil Join ke Dalam Group',
                'pesan_error' => 'Gagal Update,Hanya Bisa di GROUP!',
                'inviteCode' => $inv
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 7) == 'remove ') {
        // Remove hanya bisa 1 user saja, bisa multi user, hanya saja resiko Terblockir oleh WA sangat besar
        $user = explode('remove ', $message['message']['pesan'])[1];
        $result[] = [
            'type' => 'remove_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berhasil Remove User',
                'pesan_error' => 'Gagal Remove,Hanya Bisa di GROUP!',
                'participants' => [$user]
        ]]; 
    } else if(substr(strtolower($message['message']['pesan']), 0, 4) == 'add ') {
        $user = explode('add ', $message['message']['pesan'])[1];
        $nomer = explode(' ',$user);
        if(count($nomer) > 1) {
            for($i = 0; $i < count($nomer)-1; $i++) {
                $users[] = $nomer[$i];
            }
        } else {
            $users = [$user];
        }

        $result[] = [
            'type' => 'add_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berhasil Add User',
                'pesan_error' => 'Gagal Add,Hanya Bisa di GROUP!',
                'participants' => $users
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'get invite code') {
        $result[] = [
            'type' => 'invite_code_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berikut Link Group ini [code]',
                'pesan_error' => 'Gagal Add,Mengambil Invite Code!'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'revoke invite code') {
        $result[] = [
            'type' => 'revoke_invite_code_group',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Berhasil Generate Baru ',
                'pesan_error' => 'Gagal Generate!'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'left') {
        $result[] = [
            'type' => 'left_group',
            'data' => [
                'mode' => 'chat',
                'pesan_error' => 'Gagal Left Group!'
        ]]; 
        //bot keluar delay 5 detik setelah mengirim pesan diatas
    }
} else if($type == 'revoke') {
    if(strtolower($message['message']['pesan']) == 'hai') {
        $result[] = [
            'type' => 'message',
            'data' => [
                'mode' => 'reply',
                'pesan' => 'Hai juga'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'hallo') {
        $result[] = [
            'type' => 'message',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Hai juga'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'gambar') {
        $source = base64_encode(file_get_contents('https://pngimg.com/uploads/whatsapp/whatsapp_PNG21.png'));
        $result[] = [
            'type' => 'file',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Ini Caption',
                'filetype' => 'image/png',
                'source' => $source,
                'name' => 'Logo WA'
        ]]; 
    } else if(strtolower($message['message']['pesan']) == 'video') {
        $source = base64_encode(file_get_contents('https://scontent-sin6-2.cdninstagram.com/v/t50.2886-16/90322937_2645086652380269_7961807878183959579_n.mp4?_nc_ht=scontent-sin6-2.cdninstagram.com&_nc_cat=109&_nc_ohc=tg4bhl6rFA0AX_c33PV&oe=5E82C5F2&oh=df3faa580a6d07522221832831524e9b'));
        $result[] = [
            'type' => 'file',
            'data' => [
                'mode' => 'chat',
                'pesan' => 'Ini Caption',
                'filetype' => 'video/mp4',
                'source' => $source,
                'name' => 'Logo WA'
        ]]; 
    }
}

print json_encode($result); 