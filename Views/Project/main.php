<main style="display: block">
    <div class="container holder">
      <h1><?php echo $this->parameters["projectname"]; ?></h1>
      <div class="row" style="flex: 1">
        <div class="col-10 inner-container" style="width: 60%;">
          <div class="table-holder">
            <div class="table-header tableUsers">
              Users
            </div>
            <div class="table-body">
              <div class="table-row table-head">
                <span class="table-cell tableName">Name</span>
                <span class="table-cell tablePayed">Payed</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
<style media="screen">
  .table-cell:nth-child(2n){
    justify-content: flex-end;
  }
</style>
