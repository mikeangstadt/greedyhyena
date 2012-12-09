<?php 
$headerData["pageTitle"] = "The Multi-arm Bandit &amp; Epsilon Greedy Strategy | Greedy Hyena";
$this->load->view('header', $headerData); ?>
<div id='leftSide'>
  <header id='header' class='bigYellowContainer'>
    <h1 class='theoryH1'>DIY Experience Testing &amp; eCommerce Strategy</h1>
<p>The epsilon greedy strategy is a semi-uniform algorithmic solution to solving <a href='http://en.wikipedia.org/wiki/Multi-armed_bandit' rel='nofollow' target='_blank'><i>the multi-arm bandit</i></a> problem - whereas hypothesizing that one (or more) of a number of slot machines in a row pay out more frequently than others you seek to identify the 'winningest' machine.</p>
<p>Following the multi-arm bandit slot machine analogy and incorporating pricing strategy - consider that instead of each machine paying out equally for each winning pull, some machines may pay out at different rates per win.  That is, any given "win" on any given machine may have a varying reward value for the bandit.</p><p>Rationally, this is more accurate to the real-life performance of playing slot machines and over time yields greater overall returns.  <b>It seeks to maximize total reward insteads of number of wins</b></p></header>
<div id="container" class='bigYellowContainer'>
	<form id='loginForm' name='loginForm' method="post" action="http://www.basecampitinerary.com/cleanearthmobile/index.php/login" >
    <p>Please provide your username and password below to login.</p>
    <font class="red"><?php if(isset($error_msg)) { echo $error_msg; } ?></font><br/><br/>
Login:<br /><input type="input" name="access_login" /><br />Password:<br />
    <input type="password" name="access_password" />
    <input type="submit" name="Submit" value="Login" />
  </form>
</div>
</div>
<div id='rightSide'>
  <div class='bigYellowContainer'>
      <h2 class='abTesting'>Outperform A/B Testing</h2>
      <ul>
        <li>You maximize performance <i>while</i> testing</li>
        <li>Multiple variants can be tested at once</li>
      </ul>
  </div>
  <div class='bigYellowContainer'>
    <h2 class='epGreedy'>Design Testing eCommerce</h2>
      <ul>
        <li>Considers the true value of a conversion</li>
      </ul>
  </div>
</div>
</div>
</div>
<?php $this->load->view('footer'); ?>