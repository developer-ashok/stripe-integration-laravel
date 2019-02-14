<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

* {
  box-sizing: border-box;
}

.columns {
  float: left;
  width: 33.3%;
  padding: 8px;
}

.price {
  list-style-type: none;
  border: 1px solid #eee;
  margin: 0;
  padding: 0;
  -webkit-transition: 0.3s;
  transition: 0.3s;
}

.price:hover {
  box-shadow: 0 8px 12px 0 rgba(0,0,0,0.2)
}

.price .header {
  background-color: #111;
  color: white;
  font-size: 25px;
}

.price li {
  border-bottom: 1px solid #eee;
  padding: 20px;
  text-align: center;
}

.price .grey {
  background-color: #eee;
  font-size: 20px;
}

.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  font-size: 18px;
cursor:pointer;
}

@media only screen and (max-width: 600px) {
  .columns {
    width: 100%;
  }
}
        </style>

<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h2>
                    Laravel Stripe Payment Gateway Integration
                </h2>
		<form action="" method="POST" id="subscribe-form" onsubmit="return submitpayment()" style="padding: 0px; text-align: left; margin-top: 17px;">
		<div class="columns" style="width:100%">
		  <ul class="price">
		    <li class="header">Basic</li>
		    <li class="grey">$ 9.99 / year</li>
		    <li>10GB Storage</li>
		    <li>10 Emails</li>
		    <li>10 Domains</li>
		    <li>1GB Bandwidth</li>
		    <li class="grey">
				<input type="submit" id="submit-btn" name="submit-btn" value="Pay" class="button" />

			</li>
		  </ul>
		</div>
	</form>
                
            </div>
        </div>
    </body>

<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script> 


<script type="text/javascript">

                                        function showProcessing() {
                                            $('.subscribe-process').show();
                                        }
                                        function hideProcessing() {
                                            $('.subscribe-process').hide();
                                        }

                                        // Handling and displaying error during form submit.
                                        function subscribeErrorHandler(jqXHR, textStatus, errorThrown) {
                                            try {
                                                var resp = JSON.parse(jqXHR.responseText);
                                                if ('error_param' in resp) {
                                                    var errorMap = {};
                                                    var errParam = resp.error_param;
                                                    var errMsg = resp.error_msg;
                                                    errorMap[errParam] = errMsg;
                                                    //$("#subscribe-form").validate().showErrors(errorMap);
                                                } else {
                                                    var errMsg = resp.error_msg;
                                                    $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(errMsg);
                                                }
                                            } catch (err) {
                                                $("#alert-danger").show().text("Error while processing your request");
                                            }
                                        }

                                        // Forward to thank you page after receiving success response.
                                        function subscribeResponseHandler(responseJSON) {
                                            //window.location.replace(responseJSON.successMsg);
//                                          alert(responseJSON.message);
//                                          alert(responseJSON.state);
                                            if(responseJSON.state == 'success'){
                                                //$(".alert-success").show().text(responseJSON.message);
                                                $("#alert-success").addClass('alert alert-success').removeClass('d-none').text(responseJSON.message);
                                                $("#alert-danger").addClass('d-none');
                                            }
                                            if(responseJSON.state == 'error'){
                                                $("#alert-danger").addClass('alert alert-danger').removeClass('d-none').text(responseJSON.message);
                                                $("#alert-success").addClass('d-none');
                                            }
                                            
                                        }
                                        var handler = StripeCheckout.configure({
                                            //Replace it with your stripe publishable key
                                            key: 'pk_test_tzo2iEgfI9AfUKkNwhA8Fwio',
                                            image: 'https://scpillai.com/frontend/img/logo.png',
                                            allowRememberMe: false,
                                            token: handleStripeToken
                                        });

                                        function submitpayment() {
//$("#submit-btn").click(function () {
                                            var form = $("#subscribe-form");
                                            if (parseInt($("#amount").val()) <= 0) {
                                                return false;
                                            }
                                            handler.open({
                                                name: 'SCPILLAI.COM',
                                                description: 'INNOVATING FOR GROWTH',
                                                amount: (parseInt($("#amount").val()) * 100)
                                            });
                                            return false;
//});
                                        }

                                        function handleStripeToken(token, args) {
                                            form = $("#subscribe-form");
                                            $("input[name='stripeToken']").val(token.id);
                                            var options = {
                                                beforeSend: showProcessing,
                                                // post-submit callback when error returns
                                                error: subscribeErrorHandler,
                                                // post-submit callback when success returns
                                                success: subscribeResponseHandler,
                                                complete: hideProcessing,
                                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                dataType: 'json'
                                            };
                                            // Doing AJAX form submit to your server.
                                            //form.ajaxSubmit(options);
                                            form.ajaxSubmit(options);
//                                            $('#subscribe-form').submit(function () {
//                                                alert
//                                                $(this).ajaxSubmit(options);
//                                                //return false;
//                                            });

                                            return false;
                                        }
</script>
</html>
