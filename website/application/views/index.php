<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SITE_NAME . (isset($title) ? " :: " . $title : '') ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo CSS ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS ?>bootstrap-theme.css" rel="stylesheet">
    <link href="<?php echo CSS ?>style.css" rel="stylesheet">
    <script src="<?php echo JS ?>jquery-2.1.1.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body role="document">
    <?php $this->load->view('header'); ?>

    <div class="container theme-showcase" role="main">
      <div class="jumbotron">
        <?php $this->load->view($content, $page_data) ?>
      </div>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo JS ?>bootstrap.min.js"></script>
  </body>
</html>
