<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
<div style="padding-left: 35px;padding-top: 0; px;position: relative;display: inline-block;margin: 0 auto;border-radius: 13px;-webkit-background-size: cover;background-size: cover;transition: .5s;margin-top:-5px;">
    <img src="{{array_get($data,'backgroundImageUrl',asset('assets\images\background\virtual-card-bg.jpg'))}}" alt="Card Image"
         style="width: 432px;height:275px;margin-left: -30px;">
</div>
<div style="margin-left: -592px; margin-top: -158px;">

    <label id="_name"
           style="{{array_get($data,'nameLabelStyle')}}">
        Member Name
    </label>
    <label id="_subscription"
           style="{{array_get($data,'subscriptionLabelStyle')}}">
        Subscription</label>
    <label id="_number"
           style="{{array_get($data,'memberNumberLabelStyle')}}">
        Member #</label>
    <label id="_expiry"
           style="{{array_get($data,'expiryLabelStyle')}}">Expiry
    </label>
</div>
<div style="margin-left: -409px;margin-top: -120px;">
    <form>
        <input type="text" name="membername" id="_name2" value="{{array_get($data, 'full_name')}}" maxlength="30"
               style="{{array_get($data,'nameStyle')}}"
               readonly="true">
        <input type="text" name="membersubscription" id="_subscription2" value="{{array_get($data, 'subscription')}}" maxlength="30"
               style="{{array_get($data,'subscriptionStyle')}}"
               readonly="true">
        <input type="text" name="membernumber" id="_number2" value="{{array_get($data, 'member_id')}}"
               style="{{array_get($data,'memberNumberStyle')}}"
               readonly>
        <input type="text" name="memberexpiry" id="_expiry2" value="{{array_get($data, 'expiry_date')}}"
               style="{{array_get($data,'expiryStyle')}}"
               readonly>
    </form>
</div>
<div style="margin-left: -595px;margin-top: 59px;">
    <img id="_image" src="{{(array_get($data,'code'))??asset('assets/images/background/code.png')}}"
         style="{{array_get($data,'codeStyle','transform:translate(0px,0px);visibility:hidden; width: 80px;height: 80px;    ')}}">
</div>
<div style="margin-left: -497px;margin-top: -100px;padding: 10px 25px 10px 25px;-webkit-border-radius: 7px;-moz-border-radius: 7px;border-radius: 7px;">
    <img id="_image2" src="{{ (array_get($data,'identity')) ?? asset('assets/images/users/5.jpg')}}" alt="user" class="img-responsive profile-img"
         style="{{array_get($data,'profileImageStyle' , 'border-radius:7px;width:100px;height:125px;visibility:visible;transform:translate(803px, -139px);')}}">
</div>
</body>

</html>