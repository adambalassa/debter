<main>
    <div class="container holder">
      <h1>Room <?php echo $this->parameters["projectid"]; ?></h1>
      <div class="row">
        <div class="col-md-8">
          <form class="join_project" id="joinform">
            <div class="active_inputs">
              <div class="form-group">
                <input type="text" class="form-control add_name" placeholder="Name...">
                <button type="button" class="plus_button minus"><i class="fa fa-close"></i></button>
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Name..." disabled>
              <button type="button" id="add" class="plus_button"><i class="fa fa-plus"></i></button>
            </div>
            <div class="button-holder">
              <input type="button" id="join" value="Join!" class="btn btn-success button">
            </div>
          </form>
        </div>
      </div>
    </div>
</main>
