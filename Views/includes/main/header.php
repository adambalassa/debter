<header style="z-index: 5">
  <div class="title">
    <a id="home" href="#"><?php echo $this->parameters["projectid"]; ?></a>
  </div>
  <div class="toggle-button" id="navbarToggle">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </div>
  <div class="navbar">
    <nav class="nav-item" id="history">
      <div class="nav">
        <i class="fa fa-history"></i>
        <span class="headerHistory">History</span>
      </div>
    </nav>
    <nav class="nav-item" id="payments">
      <div class="nav">
        <i class="fa fa-money"></i>
        <span class="headerPayments">Payments</span>
      </div>
    </nav>
    <nav class="nav-item" id="debts">
      <div class="nav">
        <i class="fa fa-credit-card"></i>
        <span class="headerDebts">Debts</span>
      </div>
    </nav>
    <nav class="nav-item" id="settings">
      <div class="nav">
        <i class="fa fa-cog"></i>
        <span class="headerSettings">Settings</span>
      </div>
    </nav>
  </div>
</header>

<div class="navbar-phone">
  <div class="navigation-title">
    NAVIGÁCIÓ
  </div>
  <div class="navigation">
    <nav class="nav-item-phone">
      <i class="fa fa-home"></i>
      <span class="home">Home</span>
    </nav>
    <nav class="nav-item-phone">
      <i class="fa fa-history"></i>
      <span class="headerHistory"></span>
    </nav>
    <nav class="nav-item-phone">
      <i class="fa fa-money"></i>
      <span class="headerPayments"></span>
    </nav>
    <nav class="nav-item-phone">
      <i class="fa fa-credit-card"></i>
      <span class="headerDebts"></span>
    </nav>
    <nav class="nav-item-phone">
      <i class="fa fa-cog"></i>
      <span class="headerSettings"></span>
    </nav>
  </div>
</div>

<script type="text/javascript">
  let projectid = window.location.pathname.split('/')[2]
  let page = window.location.pathname.split('/')[3]
  $('#history, .headerHistory').click(() => {
    window.location = '/Projects/' + projectid + '/history'
  })
  $('#payments, .headerPayments').click(() => {
    window.location = '/Projects/' + projectid + '/payments'
  })
  $('#debts, .headerDebts').click(() => {
    window.location = '/Projects/' + projectid + '/debts'
  })
  $('#home, .home').click(() => {
    window.location = '/Projects/' + projectid
  })
  $('#settings, .headerSettings').click(() => {
    window.location = '/Projects/' + projectid + '/settings'
  })
  if(page !== ''){
    $('#' + page).find('i').css('transform', 'rotate(0deg)')
    $('#' + page).css('color', '#006e98')
  }
</script>

<div class="header-fade" id="headerFade"></div>
