$(document).ready(() => {

  $('.undo').on('click', '.fa-close, .fa-refresh', (e) => {
    let discard = e.currentTarget.getAttribute('data-discard')
    if(discard == "1")
      if(!confirm('Are you sure you want this payment to be discarded?'))
        return
    let user = e.currentTarget.getAttribute('data-user')
    let tme = e.currentTarget.getAttribute('data-tme')
    let value = e.currentTarget.getAttribute('data-value')
    $.ajax({
      type: 'POST',
      url: '/Payments/undo',
      data: {
        projectid: projectid,
        user: user,
        tme: tme,
        value: value,
        discard: discard
      },
      success: (result) => {
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          successHandler("Payment "+ (discard == "1" ? "discarded" : "revived") +" successfully", true)
        }
        catch (e) {
          exceptionHandler(e)
        }
      },
      error: (xhr, status, error) => {

      }
    })
  })
  let users = []
  loadMyPayments()
  $("#payed").click(() => {
    $('.panels').css('display', 'none');
    $('.payed').css('display', 'flex');
  })
  $("#undo").click(() => {
    $('.panels').css('display', 'none');
    $('.undo').css('display', 'flex');
  })
  $("#gotpayed").click(() => {
    $('.panels').css('display', 'none');
    $('.gotpayed').css('display', 'flex');
  })
  $('#allincluded').change(() => {
    if($('#allincluded').prop( "checked" )){
      $('.excluded').css('max-height', 0)
      $('#allincluded').parent().find('label').first().html('Everybody included')
    }

    else{
      $('.excluded').css('max-height', 150)
      $('#allincluded').parent().find('label').first().html('Select included')
    }
  })
  $('#pay').click(() => {
    $('#pay').css('z-index', -2)

    if($("#from").val() == 'default')
      return exceptionHandler({message: 'Payer must be selected'})
    if(!($("#value").val() > 0))
      return exceptionHandler({message: 'Invalid value for amount'})
    if($("#note").val() == '')
      return exceptionHandler({message: 'Please enter some notes about this payment'})
    let excluded = []
    if(!$('#allincluded').prop('checked')){
      for (user of users) {
        if(!$('#' + user.id).prop('checked'))
          excluded.push(user.id)
      }
    if(excluded.length == users.length)
      return exceptionHandler({message: "Somebody must be inlcuded"})
    }
    $.ajax({
      url: "/Payments/pay",
      type: 'POST',
      data: {
        projectid: projectid,
        from: $('#from').val(),
        value: $('#value').val(),
        note: $('#note').val(),
        excluded: excluded,
        currency: $('#currency').val()
      },
      success: (result) => {
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          successHandler("Payment upload successful", true)
        }
        catch (e) {
          exceptionHandler(e)
        }
      },
      error: (xhr, status, error) => {

      }
    })
  })
  $('#gotpay').click(() => {

    $('#gotpay').css('z-index', -2)

    if($("#gotfrom").val() == 'default')
      return exceptionHandler({message: 'Payer must be selected'})
    if(!($("#amount").val() > 0))
      return exceptionHandler({message: 'Invalid value for amount'})
    if($("#summary").val() == '')
      return exceptionHandler({message: 'Please enter some notes about this payment'})
    let excluded = []
    if($("#gotto").val() == 'default')
      return exceptionHandler({message: 'Payer must be selected'})
    $.ajax({
      url: "/Payments/gotpayed",
      type: 'POST',
      data: {
        projectid: projectid,
        from: $('#gotfrom').val(),
        value: $('#amount').val(),
        note: $('#summary').val(),
        to: $('#gotto').val(),
        currency: $('#valuta').val()
      },
      success: (result) => {
        $('body').css('z-index', 'auto')
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          successHandler("Payment upload successful", true)
        }
        catch (e) {
          exceptionHandler(e)
        }
      },
      error: (xhr, status, error) => {

      }
    })
  })
  $.ajax({
    url: '/Payments/getUsers',
    type: 'POST',
    data: {
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        if(result.error)
          throw result
        for (var userid in result.users) {
          let user = result.users[userid]
          users.push({id: userid, name: user.name})
          $('#from').append(
            '<option value="' + userid +'">'+ user.name +'</option>'
          )
          $('#gotfrom').append(
            '<option value="' + userid +'">'+ user.name +'</option>'
          )
          $('#gotto').append(
            '<option value="' + userid +'">'+ user.name +'</option>'
          )
          $('.excluded').append(
            '<div class="person">'+
              '<input type="checkbox" id="'+ userid +'">' +
              '<label for="'+userid+'" class="form-check-label">'+user.name+'</label>'+
            '</div>'
          )
        }
        //loadMyPayments()
      }
      catch (e) {
        exceptionHandler(e)
      }
    },
    error: (xhr, status, error) => {

    }
  })
})
function loadMyPayments() {
  $.ajax({
    url: '/Payments/getPayments',
    type: 'POST',
    data:{
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        if(result.error)
          throw result
        for (payment of result.payments) {
          $('.undo .table-body').last().append(
            '<div class="table-row">'+
              '<span class="table-cell">'+result.users[payment.userid].name+'</span>'+
            '  <span class="table-cell">'+payment_formatter(payment.value, payment.valuta)+'</span>'+
              '<span class="table-cell">'+time_formatter(payment.tme)+'</span>'+
              '<span class="table-cell">'+string_formatter(payment.note)+'</span><span '+
              'class=""><i class="fa-close fa" data-discard = "1" data-tme = "'+payment.tme+'" data-user = "'+payment.userid+'" data-value="'+payment.value+'"></i></span>'+
            '</div>'
          )
        }
      }
      catch (e) {
        exceptionHandler(e)
      }
    },
    error: (xhr, status, error) => {

    }
  })
  $.ajax({
    url: '/Payments/getDiscarded',
    type: 'POST',
    data:{
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        if(result.error)
          throw result
        for (var id in result.payments) {
          var payment = result.payments[id]
          $('.undo .table-body').first().append(
            '<div class="table-row">'+
              '<span class="table-cell">'+payment.username+'</span>'+
            '  <span class="table-cell">'+payment_formatter(payment.value, payment.valuta)+'</span>'+
              '<span class="table-cell">'+time_formatter(payment.tme)+'</span>'+
              '<span class="table-cell">'+ string_formatter(payment.value > 0 ? payment.note : result.payments[payment.note].note)+'</span><span '+
              'class=""><i class="fa-refresh fa" data-discard ="0" data-tme = "'+payment.tme+'" data-user = "'+payment.userid+'" data-value="'+payment.value+'"></i></span>'+
            '</div>'
          )
        }
      }
      catch (e) {
        exceptionHandler(e)
      }
    },
    error: (xhr, status, error) => {

    }
  })
}
