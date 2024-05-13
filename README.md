# IDP[ LAST]
Test: Postman 

API->LARAVEL -> AUTHENTICATION -> TOKE (JWT)

requets get data: send token via header ( authorization: bearer(token) )
serve -> check valid -> decode paload -> query db and response 

b1: install follow jwt-auth.doc 

## security token 
access token => IF BỊ đánh cắp -> khai thác dựa vào token 
-> giải pháp: hạ thấp thời gian sống của access token-> gây phiền phức cho người dùng
-> bổ sung: refreshToToken -> thời gian sống lâu hơn -> dùng để cấp lại acesstojen khi accesstoken cũ khi hết hạn
-> khi logout -> theem token vào blacklist -> khi authorzation -> check token trong blacklist 
-tinh hop le
- thoi gian song 
- co trong blacklist hay khong 
