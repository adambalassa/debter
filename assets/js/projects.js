$(document).ready(() => {
  $.ajax({
    method: 'POST',
    url: '/Payments/getUsers',
    data: {
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        if(result.error)
          throw result
        for (var user in result.users) {
          $('.table-body').append(
            '<div class="table-row">' +
              '<span class="table-cell">'+ result.users[user].name +'</span>' +
              '<span class="table-cell">'+ payment_formatter(result.users[user].sum, result.maincurrency) +'</span>' +
            '</div>'
          )
        }
        $('.table-body').append(
          '<div class="table-row table-head">' +
            '<span class="table-cell">&sum;</span>' +
            '<span class="table-cell">'+ payment_formatter(result.sum, result.maincurrency) +'</span>' +
          '</div>'
        )
      }
      catch (e) {
        exceptionHandler(e)
      }
    },
    error: (xhr, status, error) => {

    }
  })
})
