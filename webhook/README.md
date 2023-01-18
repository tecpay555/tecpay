
### WebHook Integration 

WebHook has to integrate on your server at some secret location, where our api confirm you the status of payment
#### Required Fields
```sh
secret_key = given secret key;
```
In the POST request you will get following values
```sh 
order_id
amount
status
post_hash
```

- step 1: base64 decode post_hash
```sh
#PHP Example:
 $encrypted_hash=base64_decode($_POST['post_hash']);
```

- step 2: Decrypt Hash 
```sh
#PHP Example:

 function decrypt($ivHashCiphertext, $password) {
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;
    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}

$remote_hash=decrypt($encrypted_hash,$secret_key);
```


- step 3:  compute hash (use md5 128 bit hashing algorithm to generate hash)
```sh
// Compute the payment hash locally In (PHP Example)
$local_hash = md5($order_id.$amount.$status.$secret_key);  
```
- step 4: verify hash (Compare hash given at request and local hash)
```sh 
if ($remote_hash == $local_hash)
{
  // Mark the transaction as success & process the order
  // You can write code process the order herer
  // Update your db with payment success
  $hash_status = "Hash Matched";
    
}
  else
  {
      // Verification failed
       $hash_status = "Hash Mismatch";
  }
```
Step 5 : Acknowledge Back payment gateway (You should  Acknowledge back payment gateway that you logged the status of payment , otherwise you will get multiple acknowledge polling )
```sh
$data['hash_status']=$hash_status; // 'Hash Matched' or 'Hash Mismatch' 
$data['acknowledge']=$acknowledge; // 'yes' or 'no'
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data); // output as a json file
```

        From the webHook you will get following status
        Approved	:> Payment is Approved by our system
        Declined :>	Payment is declined by our system
        No Matching Payment for UTR	:> system waited till timeout but no payment/matching UTR received against the payment 
        Pending	:> User session in active waiting to finish payment
        User Timed Out :>	User did'nt finished payment within the session period


![image](https://user-images.githubusercontent.com/30625676/213218551-7deef0e1-2812-421c-8845-767a5207fef6.png)

