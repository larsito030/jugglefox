<?php session_start();
ob_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include('overall_header.php');

error_reporting(0);
activate();
redirect_to_change_password_page($db);
$username = $_SESSION['username'];
?>
    <?php include(dirname(__FILE__).DIRECTORY_SEPARATOR.templates.DIRECTORY_SEPARATOR.'login_modal.html'); ?>
		<div class="container" id="container">
  			<div class="panel panel-default row">
          <a class='col-xs-4 col-sm-2' href="<?php login_or_out('href'); ?>" id="log_in"><?php login_or_out('text'); ?></a>
          <a id="hall_of_fame" class='col-xs-4 col-sm-2' href="../Juggleflash/templates/hall_of_fame.php">Hall of fame</a>
          <a id="help" class='col-xs-4 col-sm-5' href="#">Help</a>
        <span id="e_msg"></span>
        <ul class="col-xs-12 row" id="results">
            <li id="login_error" class="col-xs-9"></li>
            <li class="col-sm-3 col-sm-12 pull-right"><?php get_highscore($db, 'ov_highscore'); ?></li>
            <li class="col-sm-9"></li>
            <li id="personal_hs" class="col-sm-3"><?php get_highscore($db, 'ps_highscore'); ?></li>
            <li class="col-sm-9"></li>
            <li class="col-sm-3" id="ranking"><?php get_highscore($db, 'ranking'); ?></li>  
        </ul>
  				<div class="col-sm-4 panel panel-body">
  					<ul id="tabs">
  						  <?php get_tab_html();?>
  					</ul>
  					<ul id="operators">
  						<li class ="red operator" id="plus">+</li>
  						<li class="blue operator" id="minus">-</li>
  						<li class="green operator" id="multiply">x</li>
  						<li class="yellow operator" id="divide">/</li>
  					</ul>
  				</div>
          <div class="col-xs-12 col-sm-8 panel panel-body" id="righthand_panel">
            <?php include(dirname(__FILE__).DIRECTORY_SEPARATOR.templates.DIRECTORY_SEPARATOR.'righthand_panel.php');  ?>
          </div>
          
		</div>		
	</body>
</html>
