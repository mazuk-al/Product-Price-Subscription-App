<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            height: 450px;
            margin: 2px;
            padding: 2px;
            font-family: Helvetica, Arial, sans-serif;
        }

        #box{
            width: 850px;
            margin: 0 auto;
            height:100%;
        }

        #header{
            height: 50px;
            width: 100%;
            position: relative;
            display: block;
            border-bottom: 1px solid #504597;
        }

        #main {
            width: 100%;
            height: 350px;
            padding: 0px;
        }

        .text-div{
            font-size: 18px;
            margin-bottom: 3px;
        }

        p, pre
        {
            font-size: 18px;
            line-height: 1.4;
        }

        .heading{
            color: #504597;
            font-size: 24px;
        }

    </style>
</head>
<body>
<div id="box">
    <div id="header">
        <h1>Product Price Subscription App</h1>
    </div>
    <div class="spacing"></div>
    <div id ="main">
        <h1 class="heading">
            <div>Hi {{$name}} ,</div>
        </h1>
        <p>The price of the product to which you subscribed has changed:</p>
        <p>Product ID: {{$product_id}}</p>
        <p>Product Title: {{$product_title}}</p>
        <h2>Price: &#8376;{{$old_price}} &rarr; &#8376;{{$new_price}}</h2>
        <div class="text-div">Thanks,</div>
        <div class="text-div">Product Price Subscription App Team.</div>
    </div>
</div>
</body>
</html>
