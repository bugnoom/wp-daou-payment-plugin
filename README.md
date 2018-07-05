## Description
   <p> This is a plugin support a DAOU Payment from Korean, it integate with woocommerce and this duplicate code from Direct bank transfer on woocommerce
<br/><br/>>
    Support Amount only Korean Currency (Won)
<br/><br/>>
    <strong>This code is not success you will edit some code for support your information</strong>
<br>
    <stong>your must install This plugin <a href="https://th.wordpress.org/plugins/jwt-authentication-for-wp-rest-api/"> JWT_Authentication plugin </a></stong>
</p>

## Code Need Edit
<p> <strong>Server side</strong>
        - Edit file /class/config.php to set some information for your server (username, password, ck_key, cs_key)
 <br/><br/><strong>On plugin </strong>
        - Edit file daou_payment.php row number : <br/><br/>
        <code>#219 'CPID' => "YOU KEY FROM DAOU PROVIDER", //input you key from DAOU Payment</code>
       <br/> <code>#234  'redirect' => 'https://Your_WEB_URL/ENDPOIN_FOLDERNAME/endpoint.php?'.$param</code>
</p>

## Installation
<p>
    Before install you will contact DAOU Payment to get CPID by you will send endpoin to them you will do this step
        - Copy folder Server_endpoint to your server
        - Test on browser for test file can run in correct :
         <code>http://your_website/server_endpoint/endpoint.php</code>
<br/><br/>
  <b>On Wordpress</b>
    - Copy folder Daou_payment to Wordpress plugins folder
    - In Admin control panel click menu plugin and active a plugin Daou Payment

</p>
