<div class="top-notification-container notification-error" id="notification-top" style="display: none;">
    <div class="notification-message-conatiner">
        <strong><span class="notification-message" id="notification-title"></span></strong>
        <span class="notification-message" id="notification-message"></span>
    </div>
</div>
<style media="screen">
.top-notification-container {
    position: fixed;
    top: 0;
    height: 10vh;
    width: 100vw;
    opacity: 0.98;
    justify-content: center;
    align-items: center;
    display: none;
    z-index: 1000;
}

.notification-message-container {
    width: 50%;
}

.notification-error {
    background-color: #b30944!important;
    color: white;
}

.notification-success {
    background-color: #1a8e1a!important;
    color: white;
}
</style>
<script>
function exceptionHandler(exception){
  console.log(exception)
  $("#notification-message").html(exception.message)
  $("#notification-title").html("Error: ")
  $("#notification-top").slideToggle("fast").css({"display": "flex"}).addClass("notification-error").removeAttr("notification-success")
  setTimeout(() => {
      $("#notification-top").slideToggle("fast")
  }, 3500)
  return false;
}
function successHandler(message, reload = false){
  $("#notification-message").html(message)
  $("#notification-title").html("Success: ")
  $("#notification-top").slideToggle("fast").css({"display": "flex"}).addClass("notification-success").removeClass("notification-error")
  setTimeout(() => {
      $("#notification-top").slideToggle("fast")
      if(reload) location.reload()
  }, 3500)
}
</script>
