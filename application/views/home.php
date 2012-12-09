<?php 
$headerData["pageTitle"] = "Greedy Hyena: Epsilon Greedy Strategy Evolved";
echo $this->load->view('header', $headerData, true); ?>
<div id='leftSide'>
  <header id='header' class='bigYellowContainer'>
    <h1 class='mainH1'>Epsilon Greedy Strategy vs A/B Testing</h1>
    <p>Greedy Hyena extends the epsilon greedy strategy, a better alternative to traditional a/b testing algorithms.  <i>greedy-hyena</i> considers the actual payback received from each conversion,
allowing results to be weighted by their true business return.</p>
  </header>
<div id="container" class='bigYellowContainer'>
<p class='formMessage'>
<?php if(!isset($formMessage)): ?>
Please provide your username (email) and password below to login <b>or</b> sign up.
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
  <form id='signUpForm' method='post' name='signUpForm' onsubmit='signUpPostback(); return false;' >
    <font class="red"><?php if(isset($error_msg)) { echo $error_msg; } ?></font><br/><br/>
<span class='formLabel'>Email*</span><input type="input" name="access_login" />
<span class='formLabel'>Username</span><input type='input' name='username' /><span class='formLabel'>Password*</span>
    <input type="password" name="access_password" /><span class='formLabel'>ReType Password*</span>
    <input type="password" name="access_password_verification" />
    <input class='submit' type="submit" name="Submit" value="Sign-Up" />
  </form>
</div>
</div>
<div id='rightSide'>
  <div class='bigYellowContainer'>
      <h2 class='abTesting'>How It's Better Than A/B Testing...</h2>
      <ul>
        <li>You maximize performance <i>while</i> testing</li>
        <li>Multiple variants can be tested at once</li>
      </ul>
  </div>
  <div class='bigYellowContainer'>
    <h2 class='epGreedy'>Why It's Better Than Epsilon Greedy...</h2>
      <ul>
        <li>Considers the true value of a conversion</li>
      </ul>
  </div>
</div>
</div>
</div>
<?php $this->load->view('footer'); ?>