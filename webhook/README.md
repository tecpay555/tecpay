
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
- step 1:  compute hash (use md5 128 bit hashing algorithm to generate hash)
```sh
// Compute the payment hash locally In (PHP Example)
$local_hash = md5($order_id.$amount.$status.$secret_key);  
```
- step 2: verify hash (Compare hash given at request and local hash)
```sh 
if ($post_hash == $local_hash)
{
  // Mark the transaction as success & process the order
  // You can write code process the order herer
  $hash_status = "Hash Matched";
    
}
  else
  {
      // Verification failed
  }
```
Step 3 : Acknowledge Back payment gateway (You should  Acknowledge back payment gateway that you logged the status of payment , otherwise you will get multiple acknowledge)
```sh
$data['hash_status']=$hash_status; // Hash Matched or Hash Mismatch 
$data['acknowledge']=$acknowledge; // yes or no
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data); // output as a json file
```
