$(document).ready(() => {
  function createProject(){
    if($('#add_name').val().length < 6)
      return exceptionHandler({message: "Name must be at least 6 characters"})
    $.ajax({
      type: 'POST',
      url: '/Projects/create',
      data: {
        projectname: $('#add_name').val()
      },
      success: function (result) {
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          window.location = '/Projects/' + result.projectid
        } catch (e) {
          exceptionHandler(e)
        }
      },
      error: function (xhr, status, error) {}
    })
  }
  function joinProject(){
    if($('#give_id').val().length != 6)
      return exceptionHandler({message: "Name must be at least 6 characters"})
    $.ajax({
      type: 'POST',
      url: '/Projects/join',
      data: {
        projectid: $('#give_id').val()
      },
      success: function (result) {
        try {
          console.log(result);
          result = JSON.parse(result)
          if(result.error)
            throw result
          window.location = '/Projects/' + result.projectid
        } catch (e) {
          exceptionHandler(e)
        }
      },
      error: function (xhr, status, error) {}
    })
  }

  $("#create").click(() => {
    createProject()
  })
  $("#createform").submit(() => {
    createProject()
  })
  $("#joinform").submit(() => {
    joinProject()
  })
  $("#join").click(() => {
    joinProject()
  })
  $('#history').click((e) => {
    if(document.getElementsByClassName('my-popover')[0] !== undefined){
        $('.my-popover').css("opacity", 0)
        $('.my-popover').remove()
        return
    }
    var cookies = document.cookie.split('; ')
    for (cookie of cookies) {
      var keyval = cookie.split("=")
      if((key = keyval[0]) == 'debter_projects')
        break;
    }
    if(keyval[0] != 'debter_projects') return
    var projects = decodeURIComponent(keyval[1]).split('&')
    $('body').append('<div class="my-popover"><div class="my-popover-header">'+ "Previous projects" +
    '</div><div class="my-popover-body"></div></div>')
    for (project of projects) {
      $('.my-popover-body').append(
        '<div class="project">'+project+'</div>'
      )
    }
    $('.my-popover').css({top: e.target.offsetTop + e.target.offsetParent.offsetTop - $('.my-popover').outerHeight() / 2 - 18, left: e.currentTarget.offsetLeft + e.target.offsetParent.offsetLeft + 27, opacity: 1})

  })
  $('body').on('click', '.project', (e) => {
    $("#give_id").val(e.target.innerHTML)
  })
})
