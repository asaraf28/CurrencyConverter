<!DOCTYPE html> 
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php echo $sf_response->getTitle() ?></title>
    <link rel="shortcut icon" href="/~slacey/icalify/favicon.ico"/>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="wrapper">
      <header>
        <h1><?php echo $sf_response->getTitle() ?></h1>
      </header>
      <article>
        <?php echo $sf_content ?>
      </article>
      <footer>
      </footer>
    </div>
  </body>
</html>