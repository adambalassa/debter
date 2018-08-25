<main>
    <div class="holder">
      <h1><?php echo $this->parameters["projectname"]; ?></h1>
      <div class="row" style="flex: 1">
        <h2 class="debDebts">Debts</h2>
        <div class="col-10 inner-container">
          <div class="panels">
            <div class="panel" id="debt">
              <div class="panel-title">
                <span><i class="fa fa-history"></i></span>
                <span class="debSeeDebts">See debts</span>
              </div>
            </div>
            <div class="panel" id="arrangement">
              <div class="panel-title">
                <span><i class="fa fa-list"></i></span>
                <span class="debArrangements">Arrangements</span>
              </div>
            </div>
          </div>
          <div class="debts">
            <div class="table-holder">
              <div class="table-header debDebts">
                Debts
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell tableSum">Sum</span>
                  <span class="table-cell tableDebt">Debt</span>
                </div>
              </div>
            </div>
          </div>
          <div class="arrangements">
            <div class="table-holder">
              <div class="table-header debArrangements">
                Arrangements
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell"></span>
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell tableDebt">Debt</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
