<?php

// JUST FOR DEBUGGING
// error_reporting (E_ALL);

// JUST FOR THE LOCALHOST	
// date_default_timezone_set('America/Los_Angeles');

session_start();

require_once('$_.php');

$_("init");

function index ($args)
{
	/* 
	 * Example of using the index file
	
	global $_;
	
	$res = $_("assoc: SELECT COUNT(*) as c FROM counter WHERE `date` > '?' AND `ip` like '?'", ['2015-1-1 12:00:00', '77.%']);
	
	$results = ["OUTPUT" => $res["c"]];
	
	$_("render", $results);

	*/
	
	
	$a = ["a", "b", "c"];
	$b = $a;
	$b[0] = "d";
	
	var_dump($a);
	var_dump($b);
	
	
}

function ajax ($args) 
{
	die("ajax");
}


/****/

function try1 ($args) 
{	
	// $jsonData = json_decode(file_get_contents('http://dimecubawow.loc/async/'), true);

	// print_r($jsonData);
	
	// $d = date("Y-m-d H:i:s ", 1527191984);
	// echo $d . "<br>";
	// echo date_default_timezone_get();

	phpinfo();
} 

function try2 ($args) 
{
	$digits = 6;
	echo rand(pow(10, $digits-1), pow(10, $digits)-1);
	
	// echo sha1("a714c884522f639b" . sha1("12345678"));

	// yamel
	// id 401
	// old 
	// bb97c5d5fcd6adf08b4a61fcce357a2e255db263
	// new (123456)
	// 9e21e031d73307bc426965562f4cfa40accf12f8

	// ezequiel
	// id 530
	// old
	// 9f4592d1d0b490516e284d92424c096035abbfd7
	// new (12345678)
	// 62e6eee237d72ce927941b0df9b5f103621f686d
}

/* 
en el config.json: 
		
"provincia": {
	"action": "provincia"
}
 
function provincia($args) 
{
	global $_;
	
	$results = ["HEADER" => "Output"];
	$province_id = 16;
	
	$_(": SET NAMES 'utf8'");
	
	if (! empty($args['all'])) {
		$outputHTML = "";
		$lines = explode("\n", $args['all']);
		foreach ($lines as $l) {
			$parts = explode("\t", $l);
			$outputHTML .= "code : " . $parts[0];
			$outputHTML .= "<br>";
			$outputHTML .= "name: " . $parts[1];
			$outputHTML .= "<br>";
			
			$_(": INSERT INTO municipios (province_id, municipio, code) VALUES (?, '?', '?')", [$province_id, urldecode($parts[1]), $parts[0]]);
		} 
		$outputHTML .= '<a href="/provincia">Back</a>';
		$results["OUTPUT"] = $outputHTML;
	} 
	else {
		$results["OUTPUT"] = '<form action="/provincia"><textarea name="all"></textarea><br><input type="submit" value="submit"></form>';
	}
	
	$_("render", $results);
} 
*/


function try3 () 
{
	$msg = <<<EOT
			
<!doctype html>
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Recuperar password</title>
        
    <style type="text/css">
		p{
			margin:10px 0;
			padding:0;
		}
		table{
			border-collapse:collapse;
		}
		h1,h2,h3,h4,h5,h6{
			display:block;
			margin:0;
			padding:0;
		}
		img,a img{
			border:0;
			height:auto;
			outline:none;
			text-decoration:none;
		}
		body,#bodyTable,#bodyCell{
			height:100%;
			margin:0;
			padding:0;
			width:100%;
		}
		.mcnPreviewText{
			display:none !important;
		}
		#outlook a{
			padding:0;
		}
		img{
			-ms-interpolation-mode:bicubic;
		}
		table{
			mso-table-lspace:0pt;
			mso-table-rspace:0pt;
		}
		.ReadMsgBody{
			width:100%;
		}
		.ExternalClass{
			width:100%;
		}
		p,a,li,td,blockquote{
			mso-line-height-rule:exactly;
		}
		a[href^=tel],a[href^=sms]{
			color:inherit;
			cursor:default;
			text-decoration:none;
		}
		p,a,li,td,body,table,blockquote{
			-ms-text-size-adjust:100%;
			-webkit-text-size-adjust:100%;
		}
		.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
			line-height:100%;
		}
		a[x-apple-data-detectors]{
			color:inherit !important;
			text-decoration:none !important;
			font-size:inherit !important;
			font-family:inherit !important;
			font-weight:inherit !important;
			line-height:inherit !important;
		}
		.templateContainer{
			max-width:600px !important;
		}
		a.mcnButton{
			display:block;
		}
		.mcnImage,.mcnRetinaImage{
			vertical-align:bottom;
		}
		.mcnTextContent{
			word-break:break-word;
		}
		.mcnTextContent img{
			height:auto !important;
		}
		.mcnDividerBlock{
			table-layout:fixed !important;
		}
	
		body,#bodyTable{
			 background-color:#ffffff;
		}
	
		#bodyCell{
			 border-top:0;
		}

		h1{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:48px;
			 font-style:normal;
			 font-weight:bold;
			 line-height:150%;
			 letter-spacing:normal;
			 text-align:left;
		}
	
		h2{
			 color:#333333;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:30px;
			 font-style:normal;
			 font-weight:normal;
			 line-height:100%;
			 letter-spacing:normal;
			 text-align:left;
		}
	
		h3{
			 color:#333333;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:20px;
			 font-style:normal;
			 font-weight:normal;
			 line-height:125%;
			 letter-spacing:normal;
			 text-align:left;
		}
	
		h4{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:18px;
			 font-style:normal;
			 font-weight:bold;
			 line-height:125%;
			 letter-spacing:normal;
			 text-align:left;
		}
	
		#templatePreheader{
			 background-color:#ffffff;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:9px;
			 padding-bottom:9px;
		}
	
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			 color:#656565;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:12px;
			 line-height:150%;
			 text-align:left;
		}
	
		#templatePreheader .mcnTextContent a,#templatePreheader .mcnTextContent p a{
			 color:#656565;
			 font-weight:normal;
			 text-decoration:underline;
		}
	
		#templateHeader{
			 background-color:#ffffff;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:9px;
			 padding-bottom:0;
		}
	
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:16px;
			 line-height:150%;
			 text-align:left;
		}
	
		#templateHeader .mcnTextContent a,#templateHeader .mcnTextContent p a{
			 color:#2BAADF;
			 font-weight:normal;
			 text-decoration:underline;
		}
	
		#templateUpperBody{
			 background-color:#ffffff;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:0;
			 padding-bottom:0;
		}
	
		#templateUpperBody .mcnTextContent,#templateUpperBody .mcnTextContent p{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:16px;
			 line-height:150%;
			 text-align:left;
		}
	
		#templateUpperBody .mcnTextContent a,#templateUpperBody .mcnTextContent p a{
			 color:#2BAADF;
			 font-weight:normal;
			 text-decoration:none;
		}
	
		#templateColumns{
			 background-color:#ffffff;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:0;
			 padding-bottom:0;
		}
	
		#templateColumns .columnContainer .mcnTextContent,#templateColumns .columnContainer .mcnTextContent p{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:16px;
			 line-height:150%;
			 text-align:left;
		}
	
		#templateColumns .columnContainer .mcnTextContent a,#templateColumns .columnContainer .mcnTextContent p a{
			 color:#1189d9;
			 font-weight:normal;
			 text-decoration:none;
		}
	
		#templateLowerBody{
			 background-color:#ffffff;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:0;
			 padding-bottom:9px;
		}
	
		#templateLowerBody .mcnTextContent,#templateLowerBody .mcnTextContent p{
			 color:#202020;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:16px;
			 line-height:150%;
			 text-align:left;
		}
	
		#templateLowerBody .mcnTextContent a,#templateLowperBody .mcnTextContent p a{
			 color:#2BAADF;
			 font-weight:normal;
			 text-decoration:none;
		}
	
		#templateFooter{
			 background-color:#fafafa;
			 background-image:none;
			 background-repeat:no-repeat;
			 background-position:center;
			 background-size:cover;
			 border-top:0;
			 border-bottom:0;
			 padding-top:9px;
			 padding-bottom:9px;
		}
	
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			 color:#656565;
			 font-family:'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
			 font-size:12px;
			 line-height:150%;
			 text-align:center;
		}
	
		#templateFooter .mcnTextContent a,#templateFooter .mcnTextContent p a{
			 color:#656565;
			 font-weight:normal;
			 text-decoration:underline;
		}
	@media only screen and (min-width:768px){
		.templateContainer{
			width:600px !important;
		}

}	@media only screen and (max-width: 480px){
		body,table,td,p,a,li,blockquote{
			-webkit-text-size-adjust:none !important;
		}

}	@media only screen and (max-width: 480px){
		body{
			width:100% !important;
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		#bodyCell{
			padding-top:10px !important;
		}

}	@media only screen and (max-width: 480px){
		.columnWrapper{
			max-width:100% !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnRetinaImage{
			max-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImage{
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
			max-width:100% !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnBoxedTextContentContainer{
			min-width:100% !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupContent{
			padding:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
			padding-top:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
			padding-top:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardBottomImageContent{
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockInner{
			padding-top:0 !important;
			padding-bottom:0 !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageGroupBlockOuter{
			padding-top:9px !important;
			padding-bottom:9px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnTextContent,.mcnBoxedTextContentColumn{
			padding-right:18px !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
			padding-right:18px !important;
			padding-bottom:0 !important;
			padding-left:18px !important;
		}

}	@media only screen and (max-width: 480px){
		.mcpreview-image-uploader{
			display:none !important;
			width:100% !important;
		}

}	@media only screen and (max-width: 480px){
	
		h1{
			 font-size:26px !important;
			 line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	
		h2{
			 font-size:25px !important;
			 line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	
		h3{
			 font-size:16px !important;
			 line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	
		h4{
			 font-size:16px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		.mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
			 font-size:14px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templatePreheader{
			 display:none !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templatePreheader .mcnTextContent,#templatePreheader .mcnTextContent p{
			 font-size:14px !important;
			 line-height:125% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templateHeader .mcnTextContent,#templateHeader .mcnTextContent p{
			 font-size:16px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templateUpperBody .mcnTextContent,#templateUpperBody .mcnTextContent p{
			 font-size:14px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templateColumns .columnContainer .mcnTextContent,#templateColumns .columnContainer .mcnTextContent p{
			 font-size:16px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templateLowerBody .mcnTextContent,#templateLowerBody .mcnTextContent p{
			 font-size:13px !important;
			 line-height:150% !important;
		}

}	@media only screen and (max-width: 480px){
	
		#templateFooter .mcnTextContent,#templateFooter .mcnTextContent p{
			 font-size:12px !important;
			 line-height:150% !important;
		}

}
	</style>
	</head>
    <body>
		<center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
								<td align="center" valign="top" id="templatePreheader">
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
									<tr>
										<td align="center" valign="top" width="600" style="width:600px;">
											<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
												<tr>
													<td valign="top" class="preheaderContainer">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
															<tbody class="mcnDividerBlockOuter">
																<tr>
																	<td class="mcnDividerBlockInner" style="min-width: 100%; padding: 0px 18px 20px;">
																		<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 1px none #E6E6E6;">
																			<tbody>
																				<tr>
																					<td>
																					<span></span>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									</table>
								</td>
                            </tr>
							<tr>
								<td align="center" valign="top" id="templateHeader">
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
										<tr>
											<td align="center" valign="top" width="600" style="width:600px;">
											<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
												<tr>
													<td valign="top" class="headerContainer">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
															<tbody class="mcnImageBlockOuter">
																<tr>
																	<td valign="top" style="padding:0px" class="mcnImageBlockInner">
																		<table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
																			<tbody>
																				<tr>
																					<td class="mcnImageContent" valign="top" style="padding-right: 0px; padding-left: 0px; padding-top: 0; padding-bottom: 0; text-align:center;">
																						<a href="https://www.recarguita.com/descargas/" title="" class="" target="_blank">
																							<img align="center" alt="" src="https://gallery.mailchimp.com/7ab07f3e867ec9cb09df8f06d/images/a9466939-251f-4033-8369-8396ddd0f2de.png" width="119" style="max-width:238px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnRetinaImage">
																						</a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
														<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
															<tbody class="mcnDividerBlockOuter">
																<tr>
																	<td class="mcnDividerBlockInner" style="min-width: 100%; padding: 15px 18px;">
																		<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top: 1px none #E6E6E6;">
																			<tbody>
																				<tr>
																					<td>
																						<span></span>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</table>
											</td>
										</tr>
									</table>
								</td>
                            </tr>
							<tr>
								<td align="center" valign="top" id="templateUpperBody">
									<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
										<tr>
											<td align="center" valign="top" width="600" style="width:600px;">
												<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
													<tr>
														<td valign="top" class="bodyContainer">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
																<tbody class="mcnTextBlockOuter">
																	<tr>
																		<td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
																			<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
																				<tr>
																					<td valign="top" width="600" style="width:600px;">
																						<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
																							<tbody>
																								<tr>
																									<td valign="top" class="mcnTextContent" style="padding: 0px 18px 9px; font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; line-height: 125%;">
																										<h3 class="null" style="text-align: center;">Los tiempos cambian, las recargas también...</h3>					
		<h1 class="null" style="text-align: center;"><span style="font-size:48px">Solicito su password a la Recarguita?</span></h1>

		<p style="text-align: center; font-family: Roboto, &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; line-height: 125%;"><span style="font-size:16px">Haga click <a href="http://dimecubawow.loc/autologin/?q=319_5b98114f14a667.44144480">aqui</a> para recuperar su password</span></p>																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
EOT;
	
	sendEmail("test", $msg, "info@eldiletante.com");
}