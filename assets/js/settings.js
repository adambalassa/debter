class Settings{
    constructor(projectid){
      this.projectid = projectid
      this.maincurrency = ''
      this.email = []
      this.rounding = 1
      this.init()
    }

    async init(){
      var res = await this.getSettings()
      this.manageFrontend()
      this.setEventListeners()
    }

    getSettings (callback) {
      return new Promise((resolve, reject) =>{
        $.ajax({
          url: '/Projects/' + this.projectid + '/settings',
          type: 'POST',
          success: (result) => {
            try{
              result = JSON.parse(result)
              this.maincurrency = result.maincurrency
              this.rounding = result.rounding
              this.email = result.email
              resolve(true)
            }
            catch(e){
              exceptionHandler(e)
              reject(false)
            }
          }
        })
      })
    };

    manageFrontend () {
      $('#main').val(this.maincurrency)
      $('#rounding').val(this.rounding)
    }

    setEventListeners(){
      $('#save').click(() => {
        $.ajax({
          url: '/Projects/editSettings',
          type: 'POST',
          data: {
            projectid: this.projectid,
            maincurrency: $('#main').val(),
            rounding: $('#rounding').val(),
            email: '---'
          },
          success: (result) => {
            try {
              console.log(result);
              result = JSON.parse(result)
              if(result.error)
                throw result
              successHandler("Settings modified", true)
            }
            catch (e) {
              exceptionHandler(e)
            }
          },
          error: (xhr, status, error) => {

          }
        })
      })
    }

}

var settings
$(document).ready(() => {
  settings = new Settings(projectid)
})
