$(document).ready(() => {
  $.ajax({
  	url: '/Projects/clean',
  	type: 'POST',
  	success: (a) => { console.log("cleaned") },
  	error: () => {}
  })

  function joinProject(){
    let arr = []
    for (input of document.getElementsByClassName('add_name')) {
      if(input.value.length < 3)
        return exceptionHandler({message: 'Names must be at least 3 characters long'})
      for (name of arr) {
        if(input.value == name)
          return exceptionHandler({message: 'Names must differ'})
      }
      arr.push(input.value)
    }
    $.ajax({
      type: 'POST',
      url: '/Projects/enter',
      data: {
        usernames: arr
      },
      success: function (result) {
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          window.location = "/Projects/" + result.projectid + "/settings"
        } catch (e) {
          exceptionHandler(e)
        }
      },
      error: function (xhr, status, error) {}
    })
  }
  $("#join").click(() => {
    joinProject()
  })
  $('#joinform').submit(() => {
    joinProject()
  })
  $('#add').click(() => {
    $('.active_inputs').append('<div class="form-group"><input type="text" class="form-control add_name" placeholder="Name..."><button type="button" class="plus_button minus"><i class="fa fa-close"></i></button></div>')
  })
  $('body').on('click', '.minus', function(e) {
    e.currentTarget.parentElement.remove()
  });
})
