<!DOCTYPE html>
<html>
    <head>
        <meta name="robots" content="noodp, noydir"/>
        <meta name="description" content="This is a demo of Google Document List API."/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>static/css/theme.css"/>
        <script src="<?php echo base_url();?>static/js/common.js"></script>
        <link rel="stylesheet" href="<?php echo base_url();?>static/css/jquery-ui-1.9.2.custom.min.css"/>
        <title>Google Docs</title>
        <script type="text/javascript">
        $(document).ready(function(){
            $("#access_button").click(function(){
                $target = $(this).data("destination");
                window.open($target);
            });
        });
        </script>
    </head>
    <body>
        <?php echo $content;?>
    </body>
</html>
