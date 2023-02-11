### Refund Integration 

This API is for refund request for a particular transaction

#### Required Fields
```sh
secret_key = given secret key;
url_of_refund_api ( You wil get it from us)
```
In the POST JSON body request you will pass following datails
```sh 
pid  // Merchand ID
order_id  // Unique order id which created while passing payment request
post_hash  // we will explain how to generate this
amount // [optional field] required refund amount , 
```

### step 1 :> create hash from data
- Create a md5 hash by appending values of order_id,pid,secret_key
- Which help us to get signature of data

```sh
#PHP Example:
 $local_hash = md5($order_id . $pid  . $row['secret_key']);
```

### step 2 :> Encrypt Hash 
- You need to encrypt hash using secret key
- We want to confirm Integrity, Signature of data

```sh
#PHP Example:

$encrypted_hash=encrypt($local_hash,  $row['secret_key']);

 // function for encryption
 function encrypt($plaintext, $password) {
    $method = "AES-256-CBC";
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

    return $iv . $hash . $ciphertext;
}
```


### step 3 :>  base64 Encode local hash
-  Local hash is now encrypted hash, for safe delivery we use base64 encoding
-  We use function base64_encode in PHP
```sh
// Compute the payment hash locally In (PHP Example)
$encoded_hash=base64_encode($encrypted_hash);   
```
### step 4:. send a Post request with given url

method: GET
Api: given refund api
Body : JSON data

``sh
#example request
{
  "order_id":  "asas63e1fe596ed8",
  "pid":"5345f345345",
  "post_hash":"kvDFE0f/iUuVQ4bZKufsjnUNxs4CN8Hqn6yvApqmoZQZ+h+HUidxTRvv6UxKVBnYwyNA3GamOwGFrtLslvQf20GOcFUz73wqHkvMSZdmUIXRKdbTOWm8YRzsxxXAJqpr",
  "amount":100
}
``
 
  
```
### Step 5 :> Refund folowup
 You can check refund status by using status polling api by following this instructions https://github.com/tecpay555/tecpay/tree/main/Status_Polling
 
 
 ``sh
 #example status polling response
 {
    "order_id": "63e1fe596ed8",
    "upi_id": "fsdfs",
    "amount": "2000",
    "webhook_acknowledged": "0",
    "status": "Refund Initiated",
    "post_hash": "N1xxowl7aIamQQYCzfJ7lX7t8Q9GJUzn1XAQa01XHvXF4Qfym1drCTUAk4uqw1AeWFB6OEqG5ttJy9PsVunu0rBaDrIChF7m8Qhp1Rp3GyO74d9E3+QxGl9sdQDsdf55opo",
    "refund_info": {
        "refunded_upi": "",
        "refund_amount": "100",
        "refund_initiated_time": "February 11, 2023, 4:34 pm",
        "refund_completed_time": "",
        "refund_status": "Refund Initiated",
        "refund_notes": ""
    }
}
 
 ``
