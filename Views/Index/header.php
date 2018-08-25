<header style="z-index: 5">
  <div class="title">
    <a id="home" href="#"></a>
  </div>
  <div class="navbar">
    <nav class="nav-item" id="debter-header" style="color: #006e98">
      <div class="nav">
        <span>Debter</span>
      </div>
    </nav>
    <nav class="nav-item" id="about-header">
      <div class="nav">
        <span class="headerAbout">About</span>
      </div>
    </nav>
    <nav class="nav-item" id="contact-header">
      <div class="nav">
        <span class="headerContact">Contact</span>
      </div>
    </nav>
    <!--<nav class="nav-item" id="settings">
      <div class="nav">
        <i class="fa fa-cog"></i>
        <span>Settings</span>
      </div>
    </nav> -->
  </div>
</header>
<script type="text/javascript">
  let projectid = window.location.pathname.split('/')[2]
  let page = window.location.pathname.split('/')[3]
  $('#debter-header').click(() => {
    window.location = "/Index"
  })
  $('#about-header').click(() => {
    window.location = "/Index/About"
  })
  $('#contact-header').click(() => {
    window.location = "/Index/Contact"
  })
  $('#home').click(() => {
  })
  if(page !== ''){
    $('#' + page).find('i').css('transform', 'rotate(0deg)')
    $('#' + page).css('color', '#006e98')
  }
</script>
