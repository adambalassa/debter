var contents = {
  en: {
    "tableUsers" : "Users",
    "tableName" : "Name",
    "tablePayed" : "Payed",
    "headerAbout" : "About",
    "headerContact" : "Contact",
    "headerHistory" : "History",
    "headerPayments" : "Upload",
    "headerDebts" : "Debts",
    "headerSettings" : "Settings",

    "hisHistory" : "History",
    "hisSeeAll" : "See all",
      "tableFullHistory" : "Full history",
      "tableAmount" : "Amount",
      "tableDate" : "Date",
      "tableNote" : "Note",
      "tableCurrency" : "Currency",
      "tableValue" : "Value",
    "hisSeeSum" : "See sum",
      "tableSummary" : "Summary",
    "hisSeeCharts" : "See charts",

    "payPayments" : "Payments",
    "payPayed" : "Payed",
      "payWhoPayed" : "Who payed?",
      "payHowMuch" : "How much?",
      "paySummary" : "Summary",
      "payEverybodyIncluded" : "Everybody included",
      "payPayed" : "Payed!",
    "payGotPayed" : "Got payed",
      "payWhoGotPayed" : "Who got payed?",
      "payGotPayed" : "Got payed!",
    "payUndo" : "Undo",
      "tableDiscardedPayments" : "Discarded payments",
      "tableUndoPayments" : "Undo payments",
    "payCurrency" : "Currency",
    "paySelectOne" : "Select one...",

    "debDebts" : "Debts",
    "debSeeDebts" : "See debts",
      "tableBasics" : "Basics",
      "tableSummary" : "Summary",
      "tableForOne" : "For One",
      "tableDebts" : "Debts",
      "tableSum" : "Sum",
      "tableDebt" : "Debt",
    "debArrangements" : "Arrangements",
      "tableArrangements" : "Arrangements",
      "tableTo" : "To",
    "changeLang" : "Change language",
    "homeCreateNewRoom" : "Create new room",
      "NameYourNewRoom" : "Name your new room",
      "Create" : "Create!",
    "homeJoinToRoom" : "Join to room",
      "RoomId" : "Enter room ID",
      "Join" : "Join!",
  },
  hu: {
    "tableUsers" : "Felhasználók",
    "tableName" : "Név",
    "tablePayed" : "Fizetett",

    "headerHistory" : "Előzmények",
    "headerPayments" : "Feltöltés",
    "headerDebts" : "Tartozások",
    "headerSettings" : "Beállítások",

    "hisHistory" : "Előzmények",
    "hisSeeAll" : "Összes",
      "tableFullHistory" : "Összes előzmény",
      "tableAmount" : "Összeg",
      "tableDate" : "Időpont",
      "tableNote" : "Megjegyzés",
    "hisSeeSum" : "Összegzés",
      "tableSummary" : "Összefoglalás",
      "tableValue" : "Összeg",
      "tableCurrency" : "Pénznem",
    "hisSeeCharts" : "Grafikonok",

    "payPayments" : "Fizetések",
    "payPayed" : "Fizettem",
      "payWhoPayed" : "Ki fizetett?",
      "payHowMuch" : "Mennyit fizetett?",
      "paySummary" : "Mit fizetett?",
      "payEverybodyIncluded" : "Mindenkinek",
      "payPayed" : "Fizetve!",
    "payGotPayed" : "Nekem fizettek",
      "payWhoGotPayed" : "Kinek fizettek?",
      "payGotPayed" : "Kifizetve!",
    "payUndo" : "Visszavonás",
      "tableDiscardedPayments" : "Visszavont kifizetések",
      "tableUndoPayments" : "Kifizetések visszavonása",
    "payCurrency" : "Pénznem",
    "paySelectOne" : "Válasszon egyet...",

    "debDebts" : "Tartozások",
    "debSeeDebts" : "Tartozások",
      "tableBasics" : "Alapok",
      "tableSummary" : "Összesen",
      "tableForOne" : "Egy főre",
      "tableDebts" : "Tartozások",
      "tableSum" : "Összesen",
      "tableDebt" : "Tartozás",
    "debArrangements" : "Rendezés",
      "tableArrangements" : "Rendezés",
      "tableTo" : "Kinek?",
      "changeLang" : "Nyelv váltása",
      "headerAbout" : "Leírás",
      "headerContact" : "Kapcsolat",
      "homeCreateNewRoom" : "Új szoba létrehozása",
        "NameYourNewRoom" : "Az új szoba neve:",
        "Create" : "Létrehozás!",
      "homeJoinToRoom" : "Csatlakozás egy szobához",
        "RoomId" : "A szoba ID-ja ",
        "Join" : "Csatlakozás!",
  }
}
var content = contents[$('html').attr('lang')]

$(document).ready(() => {

  $('#navbarToggle, #headerFade').click(function(e){
    let header = $('.navbar-phone')
    if(header.hasClass('navToggled')){
      header.removeClass('navToggled').addClass('untoggled')
      $('#headerFade').css('opacity', 0).css('display', 'none')
    }
    else{
      header.removeClass('untoggled').addClass('navToggled')
      $('#headerFade').css({
        display: "block",
        opacity: 1
      })
    }
  })


  $('.all').on('focus', '.popper', (e) => {
    $('body').append('<div class="my-popover"><div class="my-popover-header">'+ e.currentTarget.getAttribute('data-title') +
    '</div><div class="my-popover-body">'+e.currentTarget.getAttribute('data-content')+'</div></div>')
    $('.my-popover').css({top: e.currentTarget.offsetTop + 138, left: e.currentTarget.offsetLeft + $('.all').offset().left - 6, opacity: 1})
  })
  $('.all').on('focusout', '.popper', (e) => {
    $('.my-popover').css("opacity", 0)
    $('.my-popover').remove()
  })
  $('.sum').on('focus', '.popper', (e) => {
    $('body').append('<div class="my-popover"><div class="my-popover-header">'+ e.currentTarget.getAttribute('data-title') +
    '</div><div class="my-popover-body">'+e.currentTarget.getAttribute('data-content')+'</div></div>')
    $('.my-popover').css({top: e.currentTarget.offsetTop + 138, left: e.currentTarget.offsetLeft + $('.sum').offset().left * 0.77 - 25, opacity: 1})
  })
  $('.sum').on('focusout', '.popper', (e) => {
    $('.my-popover').css("opacity", 0)
    $('.my-popover').remove()
  })
  $('#changeLang').click(function(){
    var lang = location.pathname.substr(-2)
    if(location.pathname.indexOf('Index') != -1)
      return window.location = "/Index/" + (lang == 'hu' ? 'en' : 'hu')
    $.ajax({
      type: 'POST',
      url: '/Projects/changeLang',
      success: (res) => {
        location.reload()
      },
      err: () => {}
    })
  })

  content = contents[$('html').attr('lang')]
  for (var key in content) {
    $('.' + key).html(content[key])
  }
})

function time_formatter(timestamp, bool = false){
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var days =  ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  var time = new Date(timestamp * 1000);
  var now = new Date()
  var year = time.getFullYear();
  var month = months[time.getMonth()];
  var date = time.getDate();
  var day = days[time.getDay()];
  var hour = time.getHours();
  var min = time.getMinutes();
  if(year < now.getFullYear() || bool)
    return year + " " + month + " " + date + "."
  if(month != months[now.getMonth()] || date - now.getDate() > 7 || date - now.getDate() > -24 && date - now.getDate() < 0)
    return month + " " + date + ". " + hour + ":" + ('0' + min).slice(-2)
  return day + " " + hour + ":" + ('0' + min).slice(-2)
}
function payment_formatter(int, valuta){
  int = Math.round(int * 100) / 100
  var str = "" + int
  switch (valuta) {
    case "HUF":
      if(int >= 1000000)
        return str.slice(0, -6) + " " + str.slice(-6, -3) + " " + str.slice(-3) + " " + 'Ft'
      if(int >= 10000)
        return str.slice(0, -3) + " " + str.slice(-3) + " " + 'Ft'
      return str + " " + 'Ft'
    case "EUR":
      if(int >= 1000000)
        return '€ ' + str.slice(0, -6) + " " + str.slice(-6, -3) + " " + str.slice(-3)
      if(int >= 10000)
        return '€ ' + str.slice(0, -3) + " " + str.slice(-3)
      return '€ ' + str
      break;
    case "USD":
      if(int >= 1000000)
        return '$ ' + str.slice(0, -6) + " " + str.slice(-6, -3) + " " + str.slice(-3)
      if(int >= 10000)
        return '$ ' + str.slice(0, -3) + " " + str.slice(-3)
      return '$ ' + str
      break;
    default:
      if(int >= 1000000)
        return str.slice(0, -6) + " " + str.slice(-6, -3) + " " + str.slice(-3) + " " + valuta
      if(int >= 10000)
        return str.slice(0, -3) + " " + str.slice(-3) + " " + valuta
      return str + " " + valuta
  }


}
function string_formatter(str){
  if(str.length > 15)
    return str.slice(0, 13) + '...'
  return str
}
