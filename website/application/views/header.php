    <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <a class="navbar-brand" href="<?php site_url() ?>"><?php echo SITE_NAME ?></a>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php echo $content == 'home' ? 'active' : '' ?>"><a href="<?php echo site_url() ?>">Home</a></li>
            <li class="<?php echo $content == 'alberti' ? 'active' : '' ?>"><a href="<?php echo site_url('alberti') ?>">Alberti Cypher Disk</a></li>
            <li class="<?php echo $content == 'algotithms' ? 'active' : '' ?>"><a href="<?php echo site_url('algorithms') ?>">Algorithm</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>