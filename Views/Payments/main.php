<main>
    <div class="container holder">
      <h1><?php echo $this->parameters["projectname"]; ?></h1>
      <div class="row" style="flex: 1">
        <h2 class="payPayments">Payments</h2>
        <div class="col-10 inner-container">
          <div class="panels">
            <div class="panel" id="payed">
              <div class="panel-title">
                <span><i class="fa fa-money"></i></span>
                <span class="payPayed">Payed</span>
              </div>
            </div>
            <div class="panel" id="gotpayed">
              <div class="panel-title">
                <span><i class="fa fa-users"></i></span>
                <span class="payGotPayed">Got payed</span>
              </div>
            </div>
            <div class="panel" id="undo">
              <div class="panel-title">
                <span><i class="fa fa-undo"></i></span>
                <span class="payUndo">Undo</span>
              </div>
            </div>
          </div>
          <div class="payed" style="display: none">
            <form class="my_form">
              <div class="form-group">
                <label for="from" class="payWhoPayed">Who payed?</label>
                <select class="custom-select" id="from">
                  <option class="paySelectOne" value="default" selected>Select one...</option>
                </select>
              </div>
              <div class="d-flex">
                <div class="form-group" style="margin-right: 10px; flex: 3">
                  <label for="value" class="payHowMuch">How much?</label>
                  <input type="number" step="100" class="form-control" id="value" placeholder="Set amount...">
                </div>
                <div class="form-group" style="flex: 2" >
                  <label for="value" class="payCurrency">Currency</label>
                  <select class="form-control" value="HUF" id="currency">
                    <option value="HUF">HUF (Magyar Forint)</option>
                    <option value="USD">USD (US Dollar)</option>
                    <option value="CAD">CAD (Canadian Dollar)</option>
                    <option value="EUR">EUR (Euro)</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="note" class="tableSummary">Summary</label>
                <textarea class="form-control" placeholder="Note..." id="note"></textarea>
              </div>
              <div class="form-group">
                <input class="form-check-input" type="checkbox" id="allincluded" checked style="margin: 5px">
                <label class="form-check-label payEverybodyIncluded" for="allincluded">Everybody included</label>
                <div class="excluded">
                </div>
              </div>
              <div class="form-group">
                <button type="button" id="pay" class="btn btn-success payPayed"></button>
              </div>
            </form>
          </div>
          <div class="gotpayed" style="display: none">
            <form class="my_form">
              <div class="form-group">
                <label class="payWhoPayed" for="from">Who payed?</label>
                <select class="custom-select" id="gotfrom">
                  <option class="paySelectOne" value="default" selected>Select one...</option>
                </select>
              </div>
              <div class="form-group">
                <label class="payWhoGotPayed" for="from">Who got payed?</label>
                <select class="custom-select" id="gotto">
                  <option class="paySelectOne" value="default" selected>Select one...</option>
                </select>
              </div>
              <div class="d-flex">
                <div class="form-group" style="margin-right: 10px; flex: 3">
                  <label class="payHowMuch" for="value">How much?</label>
                  <input type="number" step="100" class="form-control" id="amount" placeholder="Set amount...">
                </div>
                <div class="form-group" style="flex: 2">
                  <label class="payCurrency" for="value">Currency</label>
                  <select class="form-control" value="HUF" id="valuta">
                    <option value="HUF">HUF (Magyar Forint)</option>
                    <option value="USD">USD (US Dollar)</option>
                    <option value="CAD">CAD (Canadian Dollar)</option>
                    <option value="EUR">EUR (Euro)</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="note" class="tableSummary">Summary</label>
                <textarea class="form-control" placeholder="Note..." id="summary"></textarea>
              </div>
              <div class="form-group">
                <button type="button" id="gotpay"class="btn btn-success payGotPayed"></button>
              </div>
            </form>
          </div>
          <div class="undo">
            <div class="table-holder">
              <div class="table-header tableDiscardedPayments">
                Discarded payments
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell tableAmount">Amount</span>
                  <span class="table-cell tableDate">Date</span>
                  <span class="table-cell tableNote">Note</span>
                </div>
              </div>
            </div>
            <div class="table-holder">
              <div class="table-header tableUndoPayments">
                Undo payments
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell tableAmount">Amount</span>
                  <span class="table-cell tableDate">Date</span>
                  <span class="table-cell tableNote">Note</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
