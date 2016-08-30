<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>MyCrud | PHP CRUD library for Codeigniter</title>

    <!-- Bootstrap -->
    <link href="<?=base_url()?>assets/main/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="<?=base_url()?>assets/main/syntaxhighlighter/scripts/shCore.js"></script>
	  <script type="text/javascript" src="<?=base_url()?>assets/main/syntaxhighlighter/scripts/shBrushJScript.js"></script>
	  <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/main/syntaxhighlighter/styles/shCoreDefault.css"/>
	  <script type="text/javascript">SyntaxHighlighter.all();</script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    .main-table td, .main-table th {
      text-align: center;
    }
    </style>
  </head>

  <body>
    <div class="container">


      <div class="row">
        <div class="col-md-5">
          <h1>MYCRUD <small>Beta</small></h1>
          <h4>Open Source PHP CRUD Generator Library</h4>
        </div>
        <div class="col-md-7">
          <br/><br/>
          <nav class="navbar navbar-default">
              <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="<?=base_url()?>main">Home</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li><a href="<?=base_url()?>main/features">Features</a></li>
                    <li><a href="<?=base_url()?>examples">Example <span class="sr-only">(current)</span></a></li>
                    <li><a href="https://github.com/novanweb/mycrud/releases">Download Free</a></li>
                    <li><a href="<?=base_url()?>main/documentation">Discussion</a></li>
                    <li><a href="<?=base_url()?>main/contact">Contact Us</a></li>

                  </ul>

                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
        </div>
      </div>

<hr/>
