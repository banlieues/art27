<?php include_once("inc/config.php"); ?>
<?php include_once("inc/mysqli.php"); ?>
<?php
/* SET DESTINATION DIRECTORY */
if (!file_exists($path)) {mkdir($path, 0777, true);}
$html = '<!doctype html><html><head></head><body></body></html>';
file_put_contents($path.'index.html', $html, LOCK_EX);

$result = mysqli_query($db, "SELECT COUNT(*) AS total FROM $tbname WHERE  (contentByte <>'' OR contentByte <>' ' OR contentByte IS NULL)");
$row = mysqli_fetch_assoc($result);
mysqli_free_result($result);

$php_version = phpversion();
$php_memory_limit = ini_get('memory_limit');
$php_memory_limit = str_replace('M', '', $php_memory_limit);
$php_time_limit = ini_get('max_execution_time');

/*$apache_version = apache_get_version();
$apache_version = explode(' ', $apache_version);
$apache_version = explode('/', $apache_version[0] ?? 0);
$apache_version = $apache_version[1] ?? 'Unknow';*/ $apache_version="666";

$mysql_version = mysqli_get_server_info($db);
$mysql_version = explode('-', $mysql_version);
$mysql_version = $mysql_version[0] ?? 'Unknow';

$total = (int)$row['total'];
$steps = ceil($total / $chunk);
?>

<!doctype html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
    <title><?php echo $title.' v'.$version; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $title.' v'.$version; ?>">
    <meta name="author" content="Philippe Lemaire (djphil)">
    <link rel="icon" href="<?=base_url()?>mass_64/img/favicon.ico">
    <link rel="stylesheet" href="<?=base_url()?>mass_64/css/w3.css">
    <link rel="stylesheet" href="<?=base_url()?>mass_64/css/style.css">
</head>

<body>
<div class="w3-container w3-<?php echo $color; ?>">
    <h3><?php echo $title.' v'.$version; ?> by djphil (CC-BY-NC-SA 4.0)</h3>
</div>

<div class="w3-container">
    <div class="w3-card-4 w3-container w3-padding-16 w3-center">
        <div class="w3-responsive">
            <table id="stats" class="w3-table w3-border w3-bordered w3-custom-border w3-margin-bottom w3-light-gray">
                <thead>
                    <tr>
                        <th class="w3-center">
                            PHP Version<br><span class="<?php echo $tags_class; ?>"><?php echo $php_version; ?></span>
                        </th>
                        <th class="w3-center">
                            Mysql Version<br><span class="<?php echo $tags_class; ?>"><?php echo $mysql_version; ?></span>
                        </th>
                        <th class="w3-center">
                            Apache Version<br><span class="<?php echo $tags_class; ?>"><?php echo $apache_version; ?></span>
                        </th>
                        <th class="w3-center">
                            Memory Limit<br><span class="<?php echo $tags_class; ?>"><?php echo $php_memory_limit; ?> Mb</span>
                        </th>
                        <th class="w3-center">
                            Time Limit<br><span class="<?php echo $tags_class; ?>"><?php echo $php_time_limit; ?> Sec</span>
                        </th>
                    </tr>
                    <tr>
                        <th class="w3-center">
                            Total Files<br><span class="<?php echo $tags_class; ?> total"><?php echo $total; ?></span>
                        </th>
                        <th class="w3-center">
                            Current Files<br><span class="<?php echo $tags_class; ?> current">0</span>
                        </th>
                        <th class="w3-center">
                            Chunk Size<br><span class="<?php echo $tags_class; ?> total"><?php echo $chunk; ?></span>
                        </th>
                        <th class="w3-center">
                            Total Steps<br><span class="<?php echo $tags_class; ?>"><?php echo $steps; ?></span>
                        </th>
                        <th class="w3-center">
                            Current Step<br><span class="<?php echo $tags_class; ?> step">0</span>
                        </th>
                    </tr>
                    <tr>
                        <th class="w3-center">
                            Estimated Time<br><span class="<?php echo $tags_class; ?> estimated_time">0.00 Sec</span>
                        </th>
                        <th class="w3-center">
                            Execution Time<br><span class="<?php echo $tags_class; ?> execution_time">0.00 Sec</span>
                        </th>
                        <th class="w3-center">
                            Full Byte<br><span class="<?php echo $tags_class; ?> yes">0</span>
                        </th>
                        <th class="w3-center">
                            Empty Byte<br><span class="<?php echo $tags_class; ?> no">0</span>
                        </th>
                        <th class="w3-center">
                            Total Bytes<br><span class="<?php echo $tags_class; ?> bytes">0</span>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

        <div id="result"><p>We have procced <span class="current">0</span> of <span id="total"><?php echo $total; ?></span> files.</p></div>
        <div class="w3-light-gray"><div id="progress" class="w3-green w3-center"></div></div>
        <button id="button" class="w3-button w3-margin-top w3-green w3-block">START BATCH PROCESS</button>

        <div class="w3-responsive w3-margin-top ">
            <div class="w3-responsive">
                <table id="table" class="w3-table w3-table-all w3-hoverable w3-transition" border="1">
                    <thead>
                        <tr class="w3-<?php echo $color; ?>">
                            <th>#</th>
                            <th>id</th>
                            <th>name</th>
                            <!--<th>type</th>-->
                            <th>byte</th>
                            <th class="w3-right-align">action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td>null</td>
                            <td>null</td>
                            <td>null</td>
                            <!--<td>null</td>-->
                            <td>null</td>
                            <td class="w3-right-align">null</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>
</div>

<div class="w3-container w3-<?php echo $color; ?>">
    <p><?php echo $title.' v'.$version; ?> by djphil (CC-BY-NC-SA 4.0)</p>
</div>

<button id="btn" class="w3-button w3-light-gray w3-border w3-round" onclick="scrollToTop()">⯅</button>

<script src="<?=base_url()?>mass_64/js/jquery.js"></script>
<script>
$("#button").click(function() {
    var count = 0;
    var tempo = 6000;
    var total = <?php echo $total; ?>;
    var steps = <?php echo $steps; ?>;
    var path = "<?php echo $path; ?>";
    var start = new Date().getTime();
    var yes = 0;
    var no = 0;

    $("#table > tbody").html('');
    $("#progress").attr('style', 'width: 0%');
    $("#result").html('<p>We have procced <span class="current">0</span> of <span id="total">'+total+'</span> files.</p>');
    $(".estimated_time").text(((tempo * steps) / 1000 * 2).toFixed(2) + ' Sec' );

    var button = document.getElementById("button");
    button.disabled = true;
    
    for (let step = 0; step < steps; step++) {
        setTimeout(function timer() {
            $(".step").text((step + 1));
            $.ajax({
                method: "POST",
                url: "<?=base_url()?>base64/post",
                // crossDomain: true,
                // timeout: 60000,
                cache: false,
                data: {
                    name: "batchprocess", 
                    size: <?php echo $chunk; ?>,
                    step: step,
                },
                // headers: {
                    // "accept": "application/json",
                    // "Access-Control-Allow-Origin":"*"
                // }
                // success: function (datas) {var json = $.parseJSON(datas);}
                // error: function (datas) {var json = $.parseJSON(datas);}
            })
            .done(function(datas) {
                var json = $.parseJSON(datas);
                $.each(json, function(i, item) {
                    setTimeout(function timer() {
                        $(".current").text(++count);
                        let html = "<td>" + count + "</td>";
                        html += "<td>" + item.id + "</td>";

                        if (item.byte == "yes") {
                            html += '<td><a href="'+path+item.name+'" class="w3-link w3-text-blue" target="_blank">'+item.name+'</a></td>';
                            html += '<td><span class="w3-text-green">'+item.byte+'</span></td>';
                            html += '<td class="w3-right-align"><a href="'+path+item.name+'" class="w3-tag w3-link w3-round w3-green w3-hover-dark-gray" download>download</a></td>';
                            $(".yes").text(++yes);
                        }

                        if (item.byte == "no") {
                            html += "<td>"+item.name+"</td>";
                            html += '<td><span class="w3-text-red">'+item.byte+'</span></td>';
                            html += '<td class="w3-right-align"><div class="w3-tag w3-links w3-round w3-dark-gray">download</div></td>';
                            $(".no").text(++no);
                        }

                        $('<tr>').html(html).appendTo('#tbody');
                        $(".bytes").text(yes + no);

                        let percent = ((count * 100) / total).toFixed(2);
                        $(".percent").text(percent + '%');
                        $("#progress").text(percent + '%');
                        $('#progress').attr('style', 'width: '+ percent + '%');
                        $(".execution_time").text(((new Date().getTime() - start) / 1000).toFixed(2) + ' Sec' );

                        if (percent >= 100) {
                            button.disabled = false;
                            $("#result").addClass("w3-text-green w3-animate-opacity");
                            $("#result").html("<p>Successfully converted "+total+" files!</p>");
                        }
                    }, i * 10);
                });
            })
            .fail(function (jqXHR, status, error) {
                $("#result").addClass("w3-text-red w3-animate-opacity");
                $("#result").text("Status: " + status + " " + error);
            });
        }, step * tempo);
    }
});
</script>

<script>
var btn = document.getElementById("btn");
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {btn.style.display = "block";}
    else {btn.style.display = "none";}
}

function scrollToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>

</body>
</html>

