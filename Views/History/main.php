<main>
    <div class="holder">
      <h1><?php echo $this->parameters["projectname"]; ?></h1>
      <div class="row" style="flex: 1">
        <h2 class="hisHistory">History</h2>
        <div class="col-10 inner-container">
          <div class="panels">
            <div class="panel" id="all">
              <div class="panel-title">
                <span><i class="fa fa-history"></i></span>
                <span class="hisSeeAll">See all</span>
              </div>
            </div>
            <div class="panel" id="sum">
              <div class="panel-title">
                <span><i class="fa fa-list"></i></span>
                <span class="hisSeeSum">See sum</span>
              </div>
            </div>
            <div class="panel" id="chart">
              <div class="panel-title">
                <span><i class="fa fa-bar-chart"></i></span>
                <span class="hisSeeCharts">See charts</span>
              </div>
            </div>
          </div>
          <div class="all">
            <div class="table-holder">
              <div class="table-header">
                Full history
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell"></span>
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell tableAmount">Amount</span>
                  <span class="table-cell tableDate">Date</span>
                  <span class="table-cell tableNote">Note</span>
                </div>
                <div class="table-row d-none"></div>
              </div>
            </div>
          </div>
          <div class="sum">
            <div class="table-holder">
              <div class="table-header tableSummary">
                Summary
              </div>
              <div class="table-body">
                <div class="table-row table-head">
                  <span class="table-cell"></span>
                  <span class="table-cell tableName">Name</span>
                  <span class="table-cell">&sum;</span>
                </div>
              </div>
            </div>
          </div>
          <div class="charts">
            <canvas id="helper" width="600px" height="40px"></canvas>
            <canvas id="my_chart" width="600px" height="400px"></canvas>
          </div>
        </div>
      </div>
    </div>
</main>
