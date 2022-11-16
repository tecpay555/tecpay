# Tecpay integration 
## Request Payment from Android/IOS APP 

Please follow the steps to integrate payment request from your app

## Required Fields:
-  gateway endpoint : https://tecpay.in/connect.php
-  order_id: your order id (it should be unique and atleast 10 character)
-  pid: given merchant id
-  amt: amount to send
##  Optional Fields:
-  purpose: purpose of payment (any string)
-  email: payer email id
-  phone: payer phone number

## Procedure

-  Step 1: append required field except gateway endpoint as arguent string
```sh
##Example string: 
order_id=your_order_id&pid=given_merchant_id&purpose=any_purpose&amt=your_amount&email=youremail@example.com&phone=phone_no
```
-   Step 2: base64 encode the argumet string
```sh
 ##Example encrypted string: 
 b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t
 ```
 
 ```sh
 ## Dart Sample Code
 String param="order_id=${order_id}&pid=${pid}&purpose=${denomination}&amt=${value.toString()}&email=${email}";
String base64Str = base64.encode(utf8.encode(param));
 ```
-   Step 3: Append encoded arguments into tecpay end point
```sh
##example url: 
https://tecpay.in/checkout.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t 
```
-    step 4: Launch this url from your app as LaunchMode.externalApplication
```sh
##Kotlin Example: 
val webIntent: Intent = Uri.parse('https://tecpay.in/checkout.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t').let { webpage ->Intent(Intent.ACTION_VIEW, webpage)}
```
```sh    
##Java Example: 
Uri webpage = Uri.parse('https://tecpay.in/checkout.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t');
Intent webIntent = new Intent(Intent.ACTION_VIEW,webpage);
```
```sh 
##Flutter Example: 
canLaunchUrl(Uri.parse('https://tecpay.in/checkout.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t')).then((result) => {   
if(result==true)  
{ launchUrl(Uri.parse(url),mode: LaunchMode.externalApplication) }
else  
{   throw "Could not launch $url"  }   
    
});
```

- Step 5 : Its done  
 
