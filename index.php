<?php
if(isset($_REQUEST['term']) && !empty($_REQUEST['term'])) {
    header("Content-type: application/json");
    $url = 'http://www.moneycontrol.com/mccode/common/autosuggesion.php?query='.urlencode($_REQUEST['term']).'&type=1&format=json&callback=suggest1';
    $proxy = 'mckcache.mck.experian.com:9090';
    //$proxyauth = 'user:password';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);

    echo substr($curl_scraped_page,9,strlen($curl_scraped_page)-10);
    exit(0);
}
elseif(isset($_REQUEST['detail']) && !empty($_REQUEST['detail'])) {
    header("Content-type: text/html");
    $url = $_REQUEST['detail'];
    $proxy = 'mckcache.mck.experian.com:9090';
    //$proxyauth = 'user:password';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);
    $slen = strpos($curl_scraped_page,'<div class="stockDtl PB30" id="content_full">');
    echo substr($curl_scraped_page,$slen,
        strpos($curl_scraped_page,'<!-- IIFL messaging starts here -->')-$slen);
    exit(0);
}
?>
<html>
<head>
<title>MarketMate</title>
<script type="text/javascript" src="includes/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="includes/jquery-ui/jquery-ui.min.css" />
<link rel="stylesheet" href="http://stat.moneycontrol.co.in/mccss/pricechart/style_v1.css?v=7.5" />
<link rel="stylesheet" href="http://stat.moneycontrol.co.in/mccss/tradenow/tradenow.css?v=1.1" />

</head>
<body>
<!--https://www.indiainfoline.com/personal-finance/request-handler/get-search-names-->
<!--http://www.moneycontrol.com/mccode/common/autosuggesion.php?query=HDF&type=1&format=json&callback=suggest1-->
<div class="maincontent">
Search Company <input type="text" id="company_search" />
<div id="results"></div>
</div>
<div id="nChrtPrc" title="Details" style="display:none;width:1000px;">
  <p></p>
</div>
<script type="text/javascript">
function suggest1(resp) {
    
}
$(document).ready(function() {
    
    $('#company_search').keyup(function(e){
        if($(this).val().length<3)
            return true;
        $.ajax({
            url: 'index.php?term='+$(this).val(),
            method:'post',
            dataType: 'json',
            success: function(resp) {
                    $('#results').html('');
                for(var i=0; i<resp.length; i++) {
                    $('#results').append('<p><a href="#" id="detaillink" datauri="'+encodeURIComponent(resp[i].link_src)+'">'+resp[i].pdt_dis_nm+'</a></p>');
                }
            }
        });
    });

    $('#results').delegate('#detaillink','click', function(e){
        $.ajax({
            url: 'index.php?detail=' + $(this).attr('datauri'),
            method: 'GET',
            dataType: 'html',
            success: function(resp) {
               
                $('#nChrtPrc').html(resp).show();
            }
        });
    });

});
</script>
</body>
</html>