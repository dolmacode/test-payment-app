# Примеры запросов к API

> **POST** /calculate-price
> 
> curl -X POST http://localhost/calculate-price \ \
> -d "product=1" \ \
> -d "taxNumber=DE2312312312312312" \ \
> -d "couponCode=P20"


> **POST** /purchase
> 
> curl -X POST http://localhost/purchase \ \
> -d "product=3" \ \
> -d "taxNumber=FR12312313123123" \ \
> -d "couponCode=A10" \ \
> -d "paymentProcessor=example_payment_processor"


> **GET** /countries
> 
> curl -X GET http://testpayapp/countries

> **GET** /catalog
> 
> curl -X GET http://testpayapp/catalog

> **GET** /product/{product_id}
> 
> curl -X GET http://testpayapp/product/1