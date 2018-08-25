$(document).ready(() => {
  var colors = [
    {r: 196, g: 27, b: 27},
    {r: 31, g: 155, b: 47},
    {r: 19, g: 109, b: 178},
    {r: 214, g: 149, b: 21},
    {r: 173, g: 24, b: 165},
    {r: 39, g: 21, b: 155},
    {r: 127, g: 232, b: 23}
  ]
  var userColors = {}
  var maxHeight = 0;
  $('#all').click(() => {
    $('.panels').css('display', 'none')
    $('.all').css('display', 'flex')
  })
  $('#sum').click(() => {
    $('.panels').css('display', 'none')
    $('.sum').css('display', 'flex')
  })
  $('#chart').click(() => {
    $('.panels').css('display', 'none')
    $('.charts').css('display', 'flex')
  })
  $('body').on('click', '.fa-plus, .fa-minus', (e) => {
    var details = $('#' + e.currentTarget.getAttribute('for'))
    if(details.hasClass('open')){
      details.css('max-height', 0)
      details.removeClass('open')
      e.currentTarget.classList.add('fa-plus')
      e.currentTarget.classList.remove('fa-minus')
    }
    else{
      details.css('max-height', '100vh')
      details.addClass('open')
      e.currentTarget.classList.remove('fa-plus')
      e.currentTarget.classList.add('fa-minus')
    }
  })
  $.ajax({
    method: 'POST',
    url: '/Payments/getPayments',
    async: false,
    data: {
      projectid: projectid
    },
    success: (result) => {
      try {
        result = JSON.parse(result)
        console.log(result);
        if(result.error)
          throw result
        for (var j = result.payments.length - 1; j >= 0; j--) {
          let payment = result.payments[j]

          var users = ''
          for (var userid in result.users) {
            users +=
            `<div class="person">
              <input type="checkbox" id="user${userid}" disabled ${ (!payment.excluded.includes(userid) ? 'checked' : '') }>
              <label for="user${userid}" class="form-check-label">${result.users[userid].name}</label>
            </div>`
          }

          $('.all .table-body').append(
            '<div class="table-row">'+
            '<i class="fa fa-plus" for=payment'+j+'></i>'+
              '<span class="table-cell">'+result.users[payment.userid].name+'</span>'+
            '  <span class="table-cell">'+payment_formatter(payment.value, payment.valuta)+'</span>'+
              '<span class="table-cell">'+time_formatter(payment.tme)+'</span>'+
              '<span class="table-cell">'+string_formatter(payment.note)+'</span>'+
            '</div>'+
            '<div class="details" id=payment'+j+'>'+
              `<div class="row flex-column">
                <div class="table-row table-head">
                  <div class="table-cell tableName">Name</div>
                  <div class="table-cell tableValue">Value</div>
                  <div class="table-cell tableCurrency">Currency</div>
                  <div class="table-cell tableDate">Date</div>
                </div>
                <div class="table-row flex-row align-items-center">
                  <div class="table-cell">${result.users[payment.userid].name}</div>
                  <div class="table-cell">${payment.value}</div>
                  <div class="table-cell">${payment.valuta}</div>
                  <div class="table-cell">${time_formatter(payment.tme)}</div>
                </div>
                <div class="table-row"><span>${payment.note}</span></div>
                <div class="table-row" style="flex-wrap: wrap; padding:5px 40px">${users}</div>
              </div>`+
            '</div>'
          )
        }
        let i = 0;
        canvas =  document.getElementById('helper');
        helper = helper.getContext('2d')
        for (var userid in result.users) {
          var user = result.users[userid]
          userColors[userid] = colors[i++ % colors.length]
          helper.font = '17px Arial'
          helper.fillStyle = 'rgb(' + userColors[userid].r + ',' + userColors[userid].g + ',' + userColors[userid].b + ')'
          helper.fillText(user.name, canvas.width / Object.keys(result.users).length * (i - 1) + 30, canvas.height / 2 + 6 )
          var details = ""
          for (payment of user.payments) {
            let excluded = ""
            for (ex of payment.excluded) {
              excluded += ex.username + ", "
            }
            if(!(excluded = excluded.slice(0, -2)))
              excluded = "Nobody was excluded"
            details = details.concat(
              '<div class="table-row">'+
                '<span class="table-cell">'+ time_formatter(payment.tme) +'</span>'+
              '  <span class="table-cell">'+ payment_formatter(payment.value, payment.valuta) +'</span>'+
                '<span class="table-cell">'+string_formatter(payment.note)+'</span>' +
            '  </div>'
            )
          }
          $('.sum .table-body').append(
            '<div class="table-row">'+
              '<span class="table-cell"><i class="fa fa-plus" for='+userid+'></i></span>'+
              '<span class="table-cell">'+ user.name +'</span>'+
              '<span class="table-cell">'+ payment_formatter(user.sum, result.maincurrency) +'</span>'+
            '</div>'+
            '<div class="details" id='+userid+'>'+
            '<div class="table-row table-head">'+
              '<span class="table-cell">'+content.tableDate+'</span>'+
            '  <span class="table-cell">'+content.tableValue+'</span>'+
              '<span class="table-cell">'+content.tableNote+'</span>'+
          '  </div>'+
              details +
            '</div>'
          )
        }
        for (var key in content) {
          $('.' + key).html(content[key])
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
    method: 'POST',
    url: '/Payments/getChart',
    async: false,
    data: {
      projectid: projectid
    },
    success: (result) => {
      try{
        result = JSON.parse(result)
      }
      catch(e){
        exceptionHandler(e)
      }
      let canvas = document.getElementById('my_chart')
      let canvas_h = canvas.height
      let canvas_w = canvas.width
      let ctx = canvas.getContext('2d')
      var db = result.chart.length > 10 ? 10 : result.chart.length
      maxHeight = result.values[db-1]
      var margin = 2
      var std_width = canvas_w / db - 2 * margin
      var left = 0
      for (var i = result.chart.length - db; i < db; i++) {
        left += margin
        drawColumn(result.chart[i].payments, left, std_width, result.maincurrency)
        ctx.fillStyle = "black"
        ctx.fillText(time_formatter(result.chart[i].time, true), left, canvas_h - 15)
        left += margin + std_width
      }
    },
    error: (xhr, status, error) => {

    }
  })
  function getRealHeight(canvasHeight, value){
    return (canvasHeight - 50) * (value / maxHeight)
  }
  function drawColumn(dayData, left, width, maincurr){
    var sum = 0
    let canvas = document.getElementById('my_chart')
    let canvas_h = canvas.height
    var bottom = canvas_h - 30
    let chart = canvas.getContext('2d')
    for (var userID in dayData) {
      sum += dayData[userID].value
      chart.fillStyle = 'rgb(' + userColors[userID].r + ',' + userColors[userID].g + ',' + userColors[userID].b + ')'
      var height = getRealHeight(canvas_h, dayData[userID].value)
      chart.fillRect(left, bottom - height, width, height)
      bottom -= height
    }
    chart.fillStyle = "black"
    chart.fillText(payment_formatter(sum, maincurr), left, bottom - 10)
  }
})
