$(document).ready(() => {
  $('#debt').click(() => {
    $('.panels').css('display', 'none')
    $('.debts').css('display', 'flex')
  })
  $('#arrangement').click(() => {
    $('.panels').css('display', 'none')
    $('.arrangements').css('display', 'flex')
  })
  $('body').on('click', '.fa-plus, .fa-minus', (e) => {
    var details = $('#' + e.currentTarget.getAttribute('for'))
    if(details.hasClass('open')){
      details.removeClass('open')
      e.currentTarget.classList.add('fa-plus')
      e.currentTarget.classList.remove('fa-minus')
    }
    else{
      details.addClass('open')
      e.currentTarget.classList.remove('fa-plus')
      e.currentTarget.classList.add('fa-minus')
    }
  })
  $('body').on('click', '.fa-close', (e) => {
    if(!confirm('Are you sure this debt has been arranged?'))
      return
    let to = e.currentTarget.parentElement.getAttribute('id')
    let from = e.currentTarget.parentElement.parentElement.parentElement.getAttribute('id')
    let val = e.currentTarget.parentElement.parentElement.children[2].getAttribute('data-value')
    $.ajax({
      url: '/Payments/gotpayed',
      type: 'POST',
      data: {
        projectid: projectid,
        to: to,
        from: from,
        value: val,
        note: 'Arrangment of debt between ' + e.currentTarget.parentElement.parentElement.children[1].innerHTML + " and "+ e.currentTarget.parentElement.parentElement.children[1].getAttribute('data-from')
      },
      success: (result) => {
        try {
          result = JSON.parse(result)
          if(result.error)
            throw result
          successHandler('Debt cleared', true)
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
    method: 'POST',
    url: '/Debts/getDebts',
    data: {
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        console.log(result);
        if(result.error)
          throw result
        for (var debt in result.debts.users) {
          var debt = result.debts.users[debt]
          $('.debts .table-body').last().append(
            '<div class="table-row">'+
              '<span class="table-cell">'+debt.username+'</span>'+
            '  <span class="table-cell">'+payment_formatter(debt.visiblesum, result.maincurrency)+'</span>'+
              '<span class="table-cell '+ (debt.debt < 0 ? 'red': 'green') +'"><b>'+payment_formatter(-1 * debt.debt, result.maincurrency)+'</b></span>'+
            '</div>'
          )
        }
        for (arrangement of result.arrangements) {
          if(!arrangement.arrange.length) continue
          var details = ""
          for (a of arrangement.arrange) {
            details = details.concat(
              '<div class="table-row">'+
                '<span class="table-cell"></span>'+
                '<span class="table-cell" data-from="'+arrangement.username+'">'+ a.username +'</span>'+
              '  <span class="table-cell" data-value="'+a.value+'">'+ payment_formatter(a.value, result.maincurrency) +'</span>'+
              '  <span class="table-cell" id= "'+a.userid+'"><i class="fa '+(a.done ? 'fa-check' : 'fa-close')+'"></i></span>'+
            '  </div>'
            )
          }
          $('.arrangements .table-body').append(
            '<div class="table-row">'+
              '<span class="table-cell"><i class="fa fa-minus" for='+arrangement.userid+'></i></span>'+
              '<span class="table-cell">'+ arrangement.username +'</span>'+
              '<span class="table-cell">'+ payment_formatter(result.debts.users[arrangement.userid].debt, result.maincurrency) +'</span>'+
            '</div>'+
            '<div class="details open" id='+arrangement.userid+'>'+
            '<div class="table-row table-head">'+
              '<span class="table-cell"></span>'+
              '<span class="table-cell">'+content.tableTo+'</span>'+
            '  <span class="table-cell">'+content.tableValue+'</span>'+
              '<span class="table-cell"></span>'+
          '  </div>'+
              details +
            '</div>'
          )
        }
      }
      catch (e) {
        console.log(result)
        exceptionHandler(e)
      }
    },
    error: (xhr, status, error) => {

    }
  })

})
