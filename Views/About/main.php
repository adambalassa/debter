<main>
    <div class="container holder">
      <h1>About</h1>
      <div class="row" style="margin-top: 30px;">
        <div class="col-md-12">
          <style media="screen">
            h2{
              justify-content: flex-start;
            }
            p{
              text-align: justify;
            }
            .container{
              margin: 15px 0 30px 0;
            }
          </style>
          <h2>What is Debter?</h2>
          <p>
            Debter is a free online software to help you manage your debts between friends and family.
            Using Debter you can create groups without registration and upload payments that have been dealt between any of the members.
            Debter remembers all your payments, summarizes them, gives you an access to modify them and plans the optimal arrangements.
            Using Debter, you may never worry about lending for a friend or the awkward situation when you to failed to bring enough money with yourself.
            You can forget about trying to distribute the expenses of numerous bills, and arguing who might have payed them.
            This is a convenient solution for everyone, e.g. on holidays with friends and family, where dealing with money for distributing and
            arranging payments only cause tension and takes valuable time that could be spent with enjoying each others' company.
          </p>
          <h2>How to use Debter?</h2>
          <ol>
            <li>Create a room</li>
            <li>Add members</li>
            <li>Share the room-key with friends</li>
            <li>Manage payments: </li>
          </ol>
          <div class="navbar" style="max-width: fit-content;">
            <p style="margin: 0;">
              Debter has 3 surfaces:
            </p>
            <nav class="nav-item" id="history">
                <div class="nav">
                  <i class="fa fa-history"></i>
                  <span>History</span>
                </div>
            </nav>
            <nav class="nav-item" id="payments">
              <div class="nav">
                <i class="fa fa-money"></i>
                <span>Payments</span>
              </div>
            </nav>
            <nav class="nav-item" id="debts">
              <div class="nav">
                <i class="fa fa-credit-card"></i>
                <span>Debts</span>
              </div>
            </nav>
          </div>
          <div class="div" style="display: flex; align-items: center; margin: 10px;">
          <div class="panels">
            <div class="panel" id="debt">
              <div class="panel-title">
                <span><i class="fa fa-history"></i></span>
                <span>See debts</span>
              </div>
            </div>
            <div class="panel" id="arrangement">
              <div class="panel-title">
                <span><i class="fa fa-list"></i></span>
                <span>Arrangements</span>
              </div>
            </div>
          </div>
            <p style="margin: 0">
                In "History", you have an opportunity to check the uploaded payments.
                When you clicked "History", by clicking the panel "See all" Debter lists all the payments in a chronological order.
                By clicking "See summary" Debter reveals all the payments grouped by the members.
            </p>
          </div>

          <p>
            To manage your payments you may want to navigate to the menu "Payments".
            In payments you have 3 panels:<br>
            <div style="display: flex; justify-content: center;">
              <div class="panels" style="max-width: fit-content">
              <div class="panel" id="payed">
                <div class="panel-title">
                  <span><i class="fa fa-money"></i></span>
                  <span>Payed</span>
                </div>
              </div>
              <div class="panel" id="gotpayed">
                <div class="panel-title">
                  <span><i class="fa fa-users"></i></span>
                  <span>Got payed</span>
                </div>
              </div>
              <div class="panel" id="undo">
                <div class="panel-title">
                  <span><i class="fa fa-undo"></i></span>
                  <span>Undo</span>
                </div>
              </div>
            </div>
            </div>
            <ul>
              In "Payed" you can upload a new payment. If the payment doesn't concern every member of your group, after disabling "Everybody included"
              you may exclude a few members. To clarify the reason of the payment, a short summary must be added for every payment.<br>
              In "Got payed" you can upload a payment that have been exchanged between two members of the group.<br>
              In "Undo" you can delete a wrongly uploaded payment or revive a wrongly deleted one.
            </ul>
          </p>
          <div class="div" style="display: flex; align-items: center; margin: 10px;">
            <p>
              Debter also provides you the optimal arrangement of debts in your room. To access this information, navigate to "Debts".
              "See debts" only lists the amount of debts for each and every member of the group, in case you don't want to follow the arrangement
              Debter has found for you.
              In "Arrangements" Debter lists the paying-back algorythm. If a debt has been completely arranged between two members, you can upload the
              exact amount in Payments > Got payed, or you can simply click on the "x" button next to the debt.<br>
              <b>Beware:</b> if you upload any kind of a new payment or upload a payment in "Got payed" that does not equal of the value of a debt,
              the program recalculates how all the debts should be arranged.
            </p>
            <div class="panels">
            <div class="panel" id="debt">
              <div class="panel-title">
                <span><i class="fa fa-history"></i></span>
                <span>See debts</span>
              </div>
            </div>
            <div class="panel" id="arrangement">
              <div class="panel-title">
                <span><i class="fa fa-list"></i></span>
                <span>Arrangements</span>
              </div>
            </div>
          </div>
          </div>

          <h2>How does Debter work?</h2>
          <p>
            Debter uses a mathemathical algorythm to find the optimal arrangement of your debts. Considering that you can exclude members from
            a payment, what Debter considers to be you debt might not match the simple addition of the payments you see in History. When someone is
            excluded from a payment, Debter automatically uploads an invisible payment in their names with the amount of money that would concern them
            if they weren't excluded. This technique provides a perfectly fair way to arrange debts, but the way the program calculated some debts
            may cause misunderstanding.
          </p>
        </div>
      </div>
    </div>
</main>
<style media="screen">
  @media screen and (max-device-width: 800px){
    .panels{
      display: none;
    }
    main{
      font-size: 22px;
    }
  }
  @media screen and (max-width: 992px){
    .panels{
      display: none;
    }
  }
</style>
