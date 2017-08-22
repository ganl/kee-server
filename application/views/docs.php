<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/api/docs/swagger-ui.css'); ?>">
    <link rel="icon" type="image/png" href="<?php echo base_url('public/favicon.ico') ?>" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo base_url('public/favicon.ico') ?>" sizes="16x16"/>
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>
</head>

<body onload="loadSwagger()">

<div id="swagger-ui-container" class="swagger-ui-wrap"></div>

<script src="<?php echo base_url('public/api/docs/swagger-ui-bundle.js') ?>"></script>
<script src="<?php echo base_url('public/api/docs/swagger-ui-standalone-preset.js') ?>"></script>
<script type="text/javascript">

    var url = window.location.search.match(/url=([^&]+)/);
    if (url && url.length > 1) {
        url = decodeURIComponent(url[1]);
    } else {
        // Reference: http://petstore.swagger.io/v2/swagger.json
        url = "<?php echo site_url('swagger'); ?>";
    }

    function loadSwagger() {

        function log() {
            if ('console' in window) {
                console.log.apply(console, arguments);
            }
        }

        const ui = SwaggerUIBundle({
            url: url,
            dom_id: "#swagger-ui-container",
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });

        window.ui = ui;

    }
</script>
</body>
</html>