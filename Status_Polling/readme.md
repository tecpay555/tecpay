 
### Status Polling Integration 

WebHook has to integrate on your server at some secret location, where our api confirm you the status of payment
#### Required Fields
```sh
secret_key = given secret key;
```
In the POST request you will pass following datails
```sh 
pid  // Merchand ID
order_id  // Unique order id which created while passing payment request
post_hash  // we will explain how to generate this
```

- step 1: create hash from info
create md5 by appending values of order_id,pid,secret_key
```sh
#PHP Example:
 $local_hash = md5($order_id . $pid  . $row['secret_key']);
```

- step 2: Encrypt Hash 
you need to encrypt hash using secret key


```sh
#PHP Example:

$encrypted_hash=encrypt($local_hash,  $row['secret_key']);

 function encrypt($plaintext, $password) {
    $method = "AES-256-CBC";
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

    return $iv . $hash . $ciphertext;
}
```


- step 3:  base64 Encode on encrypted hash for safe delivery
- 
```sh
// Compute the payment hash locally In (PHP Example)
$encoded_hash=base64_encode($encrypted_hash);   
```
- step 4: send a Post request with given url

send a post request which contain  
pid,order_id,post_hash ( as normal key value post form request) 
you will get response after validating data
```sh 
 
```
Step 5 : Process response
you will get a json response which is having following fields
order_id // order id 
upi_id   // collection upi id
amount   // amount
webhook_acknowledged  // web acknowledge status 0 or 1
status  // payment approval status either Approved,Declined,Pending
post_hash // payload verification encrypted hash

                
```sh
                
                $data = file_get_contents("php://input");
                $row1=json_decode($foo, true);
                $row1['order_id'];
                $row1['upi_id'];
                $row1['amount'];
                $row1['webhook_acknowledged'];
                $row1['status'];
                
                
                $encrypted_hash=base64_decode($row1['post_hash']);
                $remote_hash = decrypt($encrypted_hash,$row['secret_key']);
                $local_hash = md5($order_id . $data['amount'] . $data['status'] . $row['secret_key']); 
```
Step 5 : Verifiy response
if $local_hash equal to $remote_hash then the data is verified
```sh
if($remote_hash==$local_hash)
{
    // validated status
}else
{
 // invalid status   
}

```
