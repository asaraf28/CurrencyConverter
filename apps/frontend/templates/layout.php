<!DOCTYPE html> 
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php echo $sf_params->get('module') != 'home' && has_slot('title') ? get_slot('title').' | '.$sf_response->getTitle() : $sf_response->getTitle() ?></title>
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
        <div>
          <?php echo $sf_content ?>
        </div>
      </article>
      <footer>
        <div>
          <a href="http://www.symfony-project.org" title="Built in Symfony"><img src="/~slacey/conv/images/symfony.png" alt="Built in Symfony"/></a>
          <a href="http://www.rackspacecloud.com" title="Powered by the Rackspace Cloud"><img src="/~slacey/conv/images/rackspace-cloud.png" alt="Powered by the Rackspace Cloud"/></a>
          <a href="http://www.twitter.com/stevelacey" class="twitter" title="An app by Steve Lacey">@stevelacey</a>
        </div>
      </footer>
    </div>
  </body>
</html>