<main>
    <div class="container holder">
      <h1>Room <?php echo $this->parameters["projectid"]; ?></h1>
      <div class="row">
        <div class="col-md-11">
          <form class="currency_form" id="joinform">
            <div class="maincurrency form-group flex-column">
              <h5>Main currency</h5>
              <div class="form-group w-100">
                <select class="form-control w-100" id="main">
                  <option value="EUR">EUR (Euro)</option>
                  <option value="HUF">HUF (Hungarian Forint)</option>
                  <option value="USD">USD (US Dollar)</option>
                  <option value="CAD">CAD (Canadian Dollar)</option>
                </select>
              </div>
            </div>
            <div class="maincurrency form-group flex-column">
              <h5>Rounding</h5>
              <div class="form-group w-100">
                <select class="form-control w-100" id="rounding">
                  <option value="0.01">0.01</option>
                  <option value="0.1">0.1</option>
                  <option value="1">1</option>
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="1000">1000</option>
                </select>
              </div>
            </div>
            <div class="button-holder form-group">
              <input type="button" id="save" value="Save" class="btn btn-success button">
            </div>
          </form>
      </div>
    </div>
</main>
