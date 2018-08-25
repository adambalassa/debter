<main>
    <div class="container holder">
      <h1>Debter</h1>
      <div class="row">
        <div class="col-md-6">
          <div class="create_project_container">
            <h2 class="homeCreateNewRoom">Create new room</h2>
            <form class="create_project" id="createform">
              <div class="form-group">
                <label class="NameYourNewRoom" for="add_name">Name your new room</label>
                <input type="text" id="add_name" class="form-control" placeholder="Skiing holiday...">
              </div>
              <div class="button-holder">
                <button type="button" id="create"class="btn btn-success button Create"></button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <div class="connect_to_project">
            <h2 class="homeJoinToRoom">Join to room</h2>
            <form class="create_project" id="joinform">
              <div class="form-group">
                <label for="give_id" class="RoomID">Enter room id</label>
                <div class="info">
                  <input type="text" id="give_id" class="form-control" placeholder="<?php $length = 6; echo substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )), 1 ,$length);?>...">
                  <button type="button" id = "history" class="plus_button minus popper"><i class="fa fa-history"></i></button>
                </div>
              </div>
              <div class="button-holder">
                <button type="button" id="join"  class="btn btn-success button Join"></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</main>
