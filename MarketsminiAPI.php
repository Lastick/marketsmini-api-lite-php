<?php

// Copyright (c) 2016-2018, Karbo developers (Lastick)
//
// All rights reserved.
// 
// Redistribution and use in source and binary forms, with or without modification, are
// permitted provided that the following conditions are met:
// 
// 1. Redistributions of source code must retain the above copyright notice, this list of
//    conditions and the following disclaimer.
// 
// 2. Redistributions in binary form must reproduce the above copyright notice, this list
//    of conditions and the following disclaimer in the documentation and/or other
//    materials provided with the distribution.
// 
// 3. Neither the name of the copyright holder nor the names of its contributors may be
//    used to endorse or promote products derived from this software without specific
//    prior written permission.
// 
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
// EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
// MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
// THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
// PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
// INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
// STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF
// THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

class Marketsmini {

  const RPC_TIMER = 30000;

  public function __construct(){
  }

  private function apiCall(){
    static $ch = null;
    $url = 'https://karbo-labs.pp.ua/services/markets/api/ticker.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    usleep(self::RPC_TIMER);
    $res = curl_exec($ch);
    if(curl_errno($ch) > 0){
      curl_close($ch);
      return false;
      } else {
      curl_close($ch);
      $result = json_decode($res, true);
      if($result != NULL){
        return $result;
      }
      return false;
    }
  }

  public function getTicker(){
    $data = $this->apiCall();
    $result = array();
    $result['status'] = false;
    if (!$data === false){
      if (isset($data['status'])){
        if ($data['status'] == true){
          //$result['amount'] = round(floatval($data['ticker']['ccys']['USD']['price']), 6);
          $result['amount'] = round(floatval(($data['ticker']['ccys']['USD']['buy'] + $data['ticker']['ccys']['USD']['sell']) / 2), 6);
          $result['status'] = true;
          return $result;
        }
      }
    }
    return false;
  }

}

?>