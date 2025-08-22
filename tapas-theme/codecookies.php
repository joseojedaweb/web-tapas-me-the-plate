<?php  $enlace_politica_cookies = "https://tapasmetheplate.com/newversion/politica-de-cookies/";  ?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
<!--
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script>


<script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous">
</script>
-->

<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 99999; 
  padding-top: 100px; 
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.closecookies {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  text: right;
}

.closecookies:hover,
.closecookies:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

#cajacookies {
  background-color: white;
  color: black;
  padding: 20px;
  position: fixed;
  bottom: 0px;
  width: 100%;
  z-index:10031;
  display: none;
}

#cajacookies a {
  color: black;
}

</style>

<div id="cajacookies">
    <div class="row">
        <div class="col-2 mx-auto">
        <i class="fas fa-cookie-bite fa-3x"></i>
        </div>
        <div class="col-10 mx-auto">
        <p>
        You would make us very happy if you accepted cookies. This way, we can offer you the best possible experience while using this website.
        If you want to know more about cookies, you can read the <a href="<?php echo $enlace_politica_cookies; ?>" class="text-dark"><em>cookie policy</em></a>.
        </p>
        </div>
        <div class="col-12 text-right">
            <a onclick="personalizarCookies()" class="btn btn-light text-dark mr-2 mb-3"><i class="fa fa-wrench"></i> Customise</a>

            <a onclick="aceptarCookies()" class="btn btn-dark text-white mr-2 mb-3"><i class="fa fa-check"></i>Accept and consent</a>
        </div>
    </div>    
</div>

<div id="cookiemodal" class="modal">
    <div class="modal-content">
        <span id="closecookies" class="closecookies">&times;</span>
        <br>
        <p class="mb-2"><b>Necessary cookies | Consent mode</b></p>
        <small>In these cookies we store information necessary for the functioning of the website and rejecting them will render the site unusable.</small>
        <br>

        <span>
        <input type="radio" id="" name="" value="" disabled>
        <label for="" class="mr-4">Reject</label>
        <input type="radio" id="" name="" value="" disabled checked>
        <label for="">Accept and consent</label>
        </span>
        
        <hr>

        <p class="mb-2"><b>Statistics cookies</b></p>
        <small>These cookies help us know how many people visit us, from which city, etc. We never collect personal information such as names, phone numbers or any other personal data.</small>
        <br>

        <span>
        <input type="radio" id="cookiesestadistica" name="cookiesestadistica" value="0" >
        <label for="" class="mr-4">Reject</label>
        <input type="radio" id="cookiesestadistica" name="cookiesestadistica" value="1" >
        <label for="">Accept</label>
        </span>
        <small id="errorcookies" class="text-danger" style="display:none">You must select an option</small>

        <hr>

        <span class="text-right">
            <a onclick="guardarCookies()" class="btn btn-light text-dark mr-2 mb-3"><i class="fa fa-wrench"></i> Save preferences</a>
            <a onclick="aceptarCookies()" class="btn btn-dark text-white mr-2 mb-3"><i class="fa fa-check"></i>Accept and consent</a>
        </span>
    </div>

</div>


<script>
    function borraCookiesEstadistica() {
        
        var decodedCookie = decodeURIComponent(document.cookie);
        var cookies = decodedCookie.split(';');

        if ( '' !== cookies ) {

            console.log(cookies);

            for(var i = 0; i <cookies.length; i++) {

                var c = cookies[i];

                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }

                comparacion = c.substring(0,3);
                nombre = c.split('=')[0];

                if(comparacion == "_ga" || comparacion == "_gi") {
                    cookierBorrar = nombre+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;Domain=."+window.location.hostname;
                    console.log(cookierBorrar);
                    document.cookie = cookierBorrar;
                }
                

            }

        }

        localStorage.aceptaCookies2 = 'false';
        
    }

    function compruebaaceptaCookies2() {

        if(localStorage.aceptaCookies2 == undefined){
            cajacookies.style.display = 'block';
        } else {
            if(localStorage.aceptaCookies2 == 'false'){
                borraCookiesEstadistica();
            }
        }
    }

    function consentGrantedAdStorage() {
        gtag('consent', 'update', {
          'ad_storage': 'granted',
          'ad_user_data': 'granted',
          'ad_personalization': 'granted',
          'analytics_storage': 'granted'
        });
      }

    function aceptarCookies() {
        var modal = document.getElementById("cookiemodal");
        modal.style.display = "none";

        localStorage.aceptaCookies2 = 'true';
        cajacookies.style.display = 'none';

        consentGrantedAdStorage();
    }

    function guardarCookies() {
        var radios = document.getElementsByName('cookiesestadistica');
        var cookiesestadistica = 0;
        var chequeado = 0;

        for (var i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                cookiesestadistica = radios[i].value;
                chequeado = 1;
            }
        }

        if(chequeado == 1) {

            if(cookiesestadistica == 0) {
                console.log("Entra");

                borraCookiesEstadistica();
            }
            
            var modal = document.getElementById("cookiemodal");
            modal.style.display = "none";

            cajacookies.style.display = 'none';

        } else {

            var errorcookies = document.getElementById('errorcookies');
            errorcookies.style.display = "block";

        }

    }

    function personalizarCookies() {
        var modal = document.getElementById("cookiemodal");
        modal.style.display = "block";
    }

    $('#closecookies').click(function(){
        var modal = document.getElementById("cookiemodal");
        modal.style.display = "none";
    });

    $(document).ready(function () {
        compruebaaceptaCookies2();
    });
</script>