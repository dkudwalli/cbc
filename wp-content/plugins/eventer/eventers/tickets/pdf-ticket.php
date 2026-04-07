<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
		* {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			outline:none;
			font-family: firefly, DejaVu Sans, sans-serif;
		}
        .eventer-ticket-printable {
            margin: 10px auto 50px;
            width: 300px;
			font-family: "DejaVu", Helvetica, Arial, "sans-serif"
        }

        .eventer-ticket-image_create .eventer-ticket-printable {
            width: 600px
        }

        .eventer-ticket-printable-top,
        .eventer-ticket-printable-bottom {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 5px 35px rgba(0, 0, 0, .06)
        }

        .eventer-ticket-printable-top {
            border-bottom: 0;
            text-align: center
        }

        .eventer-ticket-printable-bottom {
            border-top: 1px dashed #ccc
        }

        .eventer-ticket-printable label {
            font-size: 12px !important;
            margin: 0 0 5px !important;
            padding: 0 !important;
            color: #ccc
        }

        .eventer-print-window .eventer-ticket-printable label {
            color: #999
        }

        .eventer-ticket-printable {
            font-size: 13px !important
        }

        .eventer-ticket-printable p {
            margin: 0 0 5px !important;
            padding: 0 !important
        }

        .eventer-ticket-printable h3 {
            font-size: 15px !important;
            margin: 0 0 10px !important;
            padding: 0 !important;
            font-weight: 700 !important;
            margin-top: 4px
        }

        .eventer-on-ticket-qr img {
            width: 100px !important
        }

        .eventer-ticket-c-logo img {
            height: auto;
            max-width: 100%
        }

        .eventer-ticket-c-info {
            padding: 15px 0 0;
            margin-top: 15px;
            border-top: 1px solid #eee
        }

        .eventer-ticket-image_create .eventer-qrcode img {
            width: 200px !important
        }

        .eventer-ticket-image_create .eventer-ticket-printable label {
            font-size: 20px !important;
            margin-bottom: 5 !important
        }

        .eventer-ticket-image_create .eventer-ticket-printable {
            font-size: 24px !important
        }

        .eventer-ticket-image_create .eventer-ticket-printable p {
            margin-bottom: 10px !important;
            margin-top: 0 !important;
            line-height: 30px !important
        }

        .eventer-ticket-image_create .eventer-ticket-printable h3 {
            font-size: 30px !important;
            margin-bottom: 30px !important
        }

        .eventer-ticket-image_create .eventer-ticket-printable-top,
        .eventer-ticket-image_create .eventer-ticket-printable-bottom {
            padding: 20px;
            border-color: #ccc;
            border-radius: 15px
        }

        .eventer-ticket-image_create .eventer-ticket-printable-bottom {
            padding-bottom: 20px
        }
		.eventer-row{
			margin-left: -15px;
			margin-right: -15px
		}
		.eventer-row .eventer-col1,.eventer-row .eventer-col2,.eventer-row .eventer-col3,.eventer-row .eventer-col4,.eventer-row .eventer-col5,.eventer-row .eventer-col6,.eventer-row .eventer-col7,.eventer-row .eventer-col8,.eventer-row .eventer-col9,.eventer-row .eventer-col10,.eventer-row .eventer-col1by3,.eventer-row .eventer-col1by4{
			padding-left: 15px;
			padding-right: 15px;
			float: left;
			vertical-align: middle
		}
		.eventer-row .eventer-col1{
			width: 10%
		}
		.eventer-row .eventer-col2{
			width: 20%
		}
		.eventer-row .eventer-col3{
			width: 30%
		}
		.eventer-row .eventer-col4{
			width: 40%
		}
		.eventer-row .eventer-col5{
			width: 50%
		}
		.eventer-row .eventer-col6{
			width: 60%
		}
		.eventer-row .eventer-col7{
			width: 70%
		}
		.eventer-row .eventer-col8{
			width: 80%
		}
		.eventer-row .eventer-col9{
			width: 90%
		}
		.eventer-row .eventer-col10{
			width: 100%
		}
		.eventer-row .eventer-col1by3{
			width: 33.33333333%
		}
		.eventer-row .eventer-col1by4{
			width: 25%
		}
		.eventer-spacer-10{
			height: 10px;
			width: 100%;
			clear: both
		}
		.eventer-spacer-20{
			height: 20px;
			width: 100%;
			clear: both
		}
		.eventer-spacer-30{
			height: 30px;
			width: 100%;
			clear: both
		}
		/* Clearing Floats */
		.eventer-row:before, .eventer-row:after,.spacer-10:before,.spacer-10:after,.spacer-30:before,.spacer-30:after,.eventer-cfloat:before,.eventer-cfloat:after{
			content: "";
			display: table
		}
		.eventer-row:after,.spacer-30:after,.eventer-cfloat:before,.eventer-cfloat:after{
			clear: both
		}
    </style>
</head>

<body>
    <div class="eventer-ticket-final-tickets">
        <div class="eventer-ticket-printable">
            <div class="eventer-ticket-printable-top">
                <?php if ( isset( $barcode ) && $barcode ) : ?>
                    <div class="eventer-qrcode eventer-on-ticket-qr" data-qr-content=""><img src="<?php echo $barcode; ?>">
                    </div>
                <?php endif; ?>
                <?php if ( isset( $code ) && $code ) : ?>
                    <label class="eventer-ticket-reg-code">
                        <?php echo $code; ?>
                    </label>
                <?php endif; ?>
            </div>
            <div class="eventer-ticket-printable-bottom">
                <?php if ( isset( $attendee_name ) && $attendee_name ) : ?>
                    <label>Attendee</label>
                    <h3>
                        <?php echo $attendee_name; ?>
                    </h3>
                <?php endif; ?>
                <?php if ( isset( $event_name ) && $event_name ) : ?>
                    <label>Event</label>
                    <p class="eventer-woo-title">
                        <?php echo $event_name; ?>
                    </p>
                <?php endif; ?>
                <?php if ( isset( $ticket ) && $ticket ) : ?>
                    <label>Ticket</label>
                    <p class="registrant-ticket">
                        <?php echo $ticket; ?>
                    </p>
                <?php endif; ?>
                    <?php if ( isset( $location ) && $location ) : ?>
                            <label>Venue Location</label>
                            <p class="eventer-woo-location">
                                <?php echo $location; ?>
                            </p>
                    <?php endif; ?>
                        <?php if ( isset( $time ) && $time ) : ?>
                            <label>Date &amp; Time</label>
                            <p class="eventer-woo-datetime">
                                <?php echo $time; ?>
                                <br>
                                <?php if ( $event_cdate ) : ?>
                                    <?php echo $event_cdate; ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                <?php if ( isset( $notes ) && $notes) { ?>
                    <div class="eventer-spacer-10"></div>
                    <div class="eventer-row">
                        <div class="eventer-col10 eventer-pt-instructions">
                            <label>Instructions</label>
                            <p>
                                <?php echo $notes; ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
                <div class="eventer-spacer-10"></div>
                <div class="eventer-row eventer-ticket-c-info">
                    <div class="eventer-col10 eventer-pt-cominfo">
                        <?php
                        if ($company_image) {
                            echo '<p class="eventer-ticket-c-logo"><img src="' . $company_image . '"></p>';
                        }
                        if ($company_name) {
                            echo '<p class="eventer-ticket-c-address">' . $company_name . '</p>';
                        }
                        if ($company_address) {
                            echo '<p class="eventer-ticket-c-address">' . $company_address . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>