<?php 
$headerData["pageTitle"] = $pageTitle;
echo $this->load->view('header', $headerData, true); ?>
<div id='leftSide'>
  <header id='header' class='bigYellowContainer'>
   <?= $mainContent; ?>
  </header>
</div>
<div id='rightSide'>
  <div class='bigYellowContainer'>
    <h2 class='epGreedy'>Why It's Better Than Epsilon Greedy...</h2>
      <ul>
        <li>Considers the true value of a conversion, it transforms the nominal into <i>interval</i>.</li>
      </ul>
  </div>
  <div class='bigYellowContainer'>
      <h2 class='abTesting'>How It's Better Than A/B Testing...</h2>
      <ul>
        <li>You maximize performance <i>while</i> testing</li>
        <li>Multiple variants can be tested at once</li>
      </ul>
  </div>
</div>
</div>
</div>
<?php $this->load->view('footer'); ?>