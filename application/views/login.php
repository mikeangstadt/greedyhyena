<?php 
$headerData["pageTitle"] = "User Login | Greedy Hyena";
echo $this->load->view('header', $headerData, true); ?>
<div id='leftSide'>
  <header id='header' class='bigYellowContainer'>
    <h1 class='mainH1'>Split Testing Performance</h1>

  </header>
<div id="container" class='bigYellowContainer'>	   
 <p class='formMessage'>
<?php if(!isset($formMessage)): ?>
Hi There, Login Below.
<?php else: 
	echo $formMessage;
 endif; ?>
 </p>
<form id='loginForm' name='loginForm' method="post" action="http://www.epsilongreedy.com/login" >
    <font class="red"><?php if(isset($error_msg)) { echo $error_msg; } ?></font><br/><br/>
<span class='formLabel'>Login&nbsp;</span><input type="input" name="access_login" /><span class='formLabel'>Password&nbsp;</span>
    <input type="password" name="access_password" />
    <input class='submit' type="submit" name="Submit" value="Login" />
  </form>
</div>
</div>
<div id='rightSide'>
  <div class='bigYellowContainer'>
      <h2 class='abTesting'>Make Your Website Perform Better</h2>
      <ul>
        <li>You maximize performance <i>while</i> testing</li>
        <li>Multiple variants can be tested at once</li>
      </ul>
  </div>
  <div class='bigYellowContainer'>
    <h2 class='epGreedy'>Earn More Money Online</h2>
      <ul>
        <li>Considers the true value of a conversion</li>
      </ul>
  </div>
</div>
</div>
</div>
<?php $this->load->view('footer'); ?>