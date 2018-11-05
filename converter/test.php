<?php
/*~~~TODO:
- prepend $ or $row-> to all variable names, e.g. CURDATE to $CURDATE and ID to $row->ID
- pass row objects to functions if applicable, e.g. getitems() to getitems($row) and function getitems() to function getitems($parentrows)
- change table aliases to table names, e.g. $addressfields to $customer
~~~*/
if ($TIME60 === 0
) {
    $TIME60 = time();
    $COCID = 01; /* #59 */
    $CCODE = 'C'; /* #60 */
    $OCODE = 'O'; /* #61 */
    $BSTAT = ''; /* #62 */
} /* #63 end if */
while ($billpay !== $false
) {
    $billpay_rows = db::fetchRows($sql, 'DTSDATA.LFBP01'); /* #67 */
    if ($billpay
    ) {
        $GETPRD(); /* #69 */
        if ($billpay->BPTYPE === 'I'
            || $billpay->BPTYPE === 'A'
            || $billpay->BPTYPE === 'P'
        ) {
            $UPDIAR(); /* #74 */
        } /* #75 end if */
        if ($billpay->BPTYPE === 'S'
        ) {
            $UPDSAR(); /* #78 */
        } /* #79 end if */
        if ($billpay->BPTYPE === 'F'
        ) {
            $UPDFAR(); /* #82 */
        } /* #83 end if */
        if ($billpay->BPTYPE === 'C'
        ) {
            $UPDCAR(); /* #86 */
        } /* #87 end if */
        $billpay->BPSTAT = 'X'; /* #89 */
        $billpay->update(); /* #90 update record */
        $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #92 */
        $customer->CULRDT = $billpay->BPPDAT; /* #93 */
        $customer->CUREC->update(); /* #94 update record */
    } /* #96 end if */
} /* #97 end while */
$_INLR = $false; /* #99 */
function UPDIAR()
{
    $aropen_rows = db::fetchRows($sql, 'DTSDATA.AROPEN'); /* #103 */
    if ($aropen
    ) {
        if ($billpay->BPOIN_ > 0
        ) {
            $aropen->AROPPD += $billpay->BPPAY_; /* #108 */
            $aropen->ARONPD += $billpay->BPPAY_; /* #109 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #110 */
            $aropen->update(); /* #111 update record */
            $receipts->CRCID = $aropen->AROCID; /* #113 */
            $receipts->CRPER = $PERIOD; /* #114 */
            $receipts->CRCUST = $aropen->AROCUS; /* #115 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #116 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #117 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #118 */
            $receipts->CRINV_ = $aropen->AROINV; /* #119 */
            $receipts->CRTYPE = '1'; /* #120 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #121 */
            $receipts->CRGLDP = ''; /* #122 */
            $receipts->CRGLAC = ''; /* #123 */
            $receipts->CRDESC = ''; /* #124 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #125 */
            $receipts->CRDUS = 0; /* #126 */
            $receipts->CRSTCM = ''; /* #127 */
            $receipts->CRSTGL = ''; /* #128 */
            $receipts->CRSTS = ''; /* #129 */
            $receipts->CRDST = ''; /* #130 */
            $receipts->insert(); /* #131 insert record */
            $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #133 */
            $customer->CULRDT = $billpay->BPPDAT; /* #134 */
            $customer->CUREC->update(); /* #135 update record */
        } /* #136 end if */
        if ($billpay->BPOIN_ === 0
            && $billpay->BPPRV_ > 0
        ) {
            $aropen->AROAMT -= $billpay->BPPAY_; /* #143 */
            $aropen->ARONAM -= $billpay->BPPAY_; /* #144 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #145 */
            $aropen->update(); /* #146 update record */
            $receipts->CRCID = $aropen->AROCID; /* #148 */
            $receipts->CRPER = $PERIOD; /* #149 */
            $receipts->CRCUST = $aropen->AROCUS; /* #150 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #151 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #152 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #153 */
            $receipts->CRINV_ = $aropen->AROINV; /* #154 */
            $receipts->CRTYPE = '1'; /* #155 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #156 */
            $receipts->CRGLDP = ''; /* #157 */
            $receipts->CRGLAC = ''; /* #158 */
            $receipts->CRDESC = ''; /* #159 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #160 */
            $receipts->CRDUS = 0; /* #161 */
            $receipts->CRSTCM = ''; /* #162 */
            $receipts->CRSTGL = ''; /* #163 */
            $receipts->CRSTS = ''; /* #164 */
            $receipts->CRDST = ''; /* #165 */
            $receipts->insert(); /* #166 insert record */
        } /* #167 end if */
        $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #169 */
        $customer->CULRDT = $billpay->BPPDAT; /* #170 */
        $customer->CUREC->update(); /* #171 update record */
    } /* #173 end if */
    if ($aropen
        && $billpay->BPRTN_ === 0
        && $billpay->BPOIN_ === 0
        && $billpay->BPPRV_ < 0
    ) {
        $aropen->AROAMT -= $billpay->BPPAY_; /* #180 */
        $aropen->ARONAM -= $billpay->BPPAY_; /* #181 */
        $aropen->AROPDT = $billpay->BPPDAT; /* #182 */
        $aropen->update(); /* #183 update record */
        $receipts->CRCID = $aropen->AROCID; /* #185 */
        $receipts->CRPER = $PERIOD; /* #186 */
        $receipts->CRCUST = $aropen->AROCUS; /* #187 */
        $receipts->CRSLMN = $aropen->AROSLM; /* #188 */
        $receipts->CRDTPD = $billpay->BPPDAT; /* #189 */
        $receipts->CRDTIN = $aropen->AROIDT; /* #190 */
        $receipts->CRINV_ = $aropen->AROINV; /* #191 */
        $receipts->CRTYPE = '1'; /* #192 */
        $receipts->CRCHCK = $billpay->BPCONF; /* #193 */
        $receipts->CRGLDP = ''; /* #194 */
        $receipts->CRGLAC = ''; /* #195 */
        $receipts->CRDESC = ''; /* #196 */
        $receipts->CRPPD = $billpay->BPPAY_; /* #197 */
        $receipts->CRDUS = 0; /* #198 */
        $receipts->CRSTCM = ''; /* #199 */
        $receipts->CRSTGL = ''; /* #200 */
        $receipts->CRSTS = ''; /* #201 */
        $receipts->CRDST = ''; /* #202 */
        $receipts->insert(); /* #203 insert record */
        $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #205 */
        $customer->CULRDT = $billpay->BPPDAT; /* #206 */
        $customer->CUREC->update(); /* #207 update record */
    } /* #209 end if */
    if ($aropen
        && $billpay->BPRTN_ === 0
        && $billpay->BPOIN_ < 0
        && $billpay->BPPRV_ < 0
    ) {
        $aropen->AROPPD += $billpay->BPPAY_; /* #216 */
        $aropen->ARONPD += $billpay->BPPAY_; /* #217 */
        $aropen->AROPDT = $billpay->BPPDAT; /* #218 */
        $aropen->update(); /* #219 update record */
        $receipts->CRCID = $aropen->AROCID; /* #221 */
        $receipts->CRPER = $PERIOD; /* #222 */
        $receipts->CRCUST = $aropen->AROCUS; /* #223 */
        $receipts->CRSLMN = $aropen->AROSLM; /* #224 */
        $receipts->CRDTPD = $billpay->BPPDAT; /* #225 */
        $receipts->CRDTIN = $aropen->AROIDT; /* #226 */
        $receipts->CRINV_ = $aropen->AROINV; /* #227 */
        $receipts->CRTYPE = '1'; /* #228 */
        $receipts->CRCHCK = $billpay->BPCONF; /* #229 */
        $receipts->CRGLDP = ''; /* #230 */
        $receipts->CRGLAC = ''; /* #231 */
        $receipts->CRDESC = ''; /* #232 */
        $receipts->CRPPD = $billpay->BPPAY_; /* #233 */
        $receipts->CRDUS = 0; /* #234 */
        $receipts->CRSTCM = ''; /* #235 */
        $receipts->CRSTGL = ''; /* #236 */
        $receipts->CRSTS = ''; /* #237 */
        $receipts->CRDST = ''; /* #238 */
        $receipts->insert(); /* #239 insert record */
        $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #241 */
        $customer->CULRDT = $billpay->BPPDAT; /* #242 */
        $customer->CUREC->update(); /* #243 update record */
    } /* #245 end if */
} /* #247 end function */
function UPDSAR()
{
    $aropen_rows = db::fetchRows($sql, 'DTSDATA.AROPEN'); /* #251 */
    if ($aropen
    ) {
        if ($billpay->BPPAY_ < $billpay->BPNET_
            && $billpay->BPRTN_ === 0
            && $billpay->BPOIN_ > 0
        ) {
            $aropen->AROPPD += $billpay->BPPAY_; /* #258 */
            $aropen->ARONPD += $billpay->BPPAY_; /* #259 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #260 */
            $aropen->update(); /* #261 update record */
            $receipts->CRCID = $aropen->AROCID; /* #263 */
            $receipts->CRPER = $PERIOD; /* #264 */
            $receipts->CRCUST = $aropen->AROCUS; /* #265 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #266 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #267 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #268 */
            $receipts->CRINV_ = $aropen->AROINV; /* #269 */
            $receipts->CRTYPE = '1'; /* #270 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #271 */
            $receipts->CRGLDP = ''; /* #272 */
            $receipts->CRGLAC = ''; /* #273 */
            $receipts->CRDESC = ''; /* #274 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #275 */
            $receipts->CRDUS = 0; /* #276 */
            $receipts->CRSTCM = ''; /* #277 */
            $receipts->CRSTGL = ''; /* #278 */
            $receipts->CRSTS = ''; /* #279 */
            $receipts->CRDST = ''; /* #280 */
            $receipts->insert(); /* #281 insert record */
            $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #283 */
            $customer->CULRDT = $billpay->BPPDAT; /* #284 */
            $customer->CUREC->update(); /* #285 update record */
        } /* #286 end if */
    } /* #288 end if */
} /* #289 end function */
function UPDFAR()
{
    $PAY_ = $billpay->BPPAY_; /* #293 */
    while ($aropen !== $false
    ) {
        $aropen_rows = db::fetchRows($sql, 'DTSDATA.AROPEN'); /* #296 */
        if ($aropen
            && $PAY_ > 0
        ) {
            $PBAL = 0; /* #299 */
            if ($aropen->AROCIC === 'I'
                || $aropen->AROCIC === 'F'
            ) {
                if ($PAY_ > 0
                    && $aropen->AROAMT > 0
                ) {
                    $PBAL = $aropen->AROAMT - $aropen->AROPPD; /* #305 */
                    if ($PBAL <= $PAY_
                        && $PBAL !== 0
                    ) {
                        $PAY_ -= $PBAL; /* #308 */
                        $aropen->AROPPD += $PBAL; /* #309 */
                        $aropen->ARONPD += $PBAL; /* #310 */
                        $aropen->AROPDT = $billpay->BPPDAT; /* #311 */
                        $aropen->update(); /* #312 update record */
                        $receipts->CRCID = $aropen->AROCID; /* #314 */
                        $receipts->CRPER = $PERIOD; /* #315 */
                        $receipts->CRCUST = $aropen->AROCUS; /* #316 */
                        $receipts->CRSLMN = $aropen->AROSLM; /* #317 */
                        $receipts->CRDTPD = $billpay->BPPDAT; /* #318 */
                        $receipts->CRDTIN = $aropen->AROIDT; /* #319 */
                        $receipts->CRINV_ = $aropen->AROINV; /* #320 */
                        $receipts->CRTYPE = '1'; /* #321 */
                        $receipts->CRCHCK = $billpay->BPCONF; /* #322 */
                        $receipts->CRGLDP = ''; /* #323 */
                        $receipts->CRGLAC = ''; /* #324 */
                        $receipts->CRDESC = ''; /* #325 */
                        $receipts->CRPPD = $PBAL; /* #326 */
                        $receipts->CRDUS = 0; /* #327 */
                        $receipts->CRSTCM = ''; /* #328 */
                        $receipts->CRSTGL = ''; /* #329 */
                        $receipts->CRSTS = ''; /* #330 */
                        $receipts->CRDST = ''; /* #331 */
                        $receipts->insert(); /* #332 insert record */
                    } /* #333 end if */
                } /* #334 end if */
            } /* #335 end if */
            if ($aropen->AROCIC === 'I'
                || $aropen->AROCIC === 'F'
            ) {
                $PBAL = 0; /* #339 */
                $PBAL = $aropen->AROAMT - $aropen->AROPPD; /* #340 */
                if ($PBAL > $PAY_
                    && $PAY_ > 0
                    && $aropen->AROAMT > 0
                ) {
                    $aropen->AROPPD += $PAY_; /* #344 */
                    $aropen->ARONPD += $PAY_; /* #345 */
                    $aropen->AROPDT = $billpay->BPPDAT; /* #346 */
                    $aropen->update(); /* #347 update record */
                    $receipts->CRCID = $aropen->AROCID; /* #349 */
                    $receipts->CRPER = $PERIOD; /* #350 */
                    $receipts->CRCUST = $aropen->AROCUS; /* #351 */
                    $receipts->CRSLMN = $aropen->AROSLM; /* #352 */
                    $receipts->CRDTPD = $billpay->BPPDAT; /* #353 */
                    $receipts->CRDTIN = $aropen->AROIDT; /* #354 */
                    $receipts->CRINV_ = $aropen->AROINV; /* #355 */
                    $receipts->CRTYPE = '1'; /* #356 */
                    $receipts->CRCHCK = $billpay->BPCONF; /* #357 */
                    $receipts->CRGLDP = ''; /* #358 */
                    $receipts->CRGLAC = ''; /* #359 */
                    $receipts->CRDESC = ''; /* #360 */
                    $receipts->CRPPD = $PAY_; /* #361 */
                    $receipts->CRDUS = 0; /* #362 */
                    $receipts->CRSTCM = ''; /* #363 */
                    $receipts->CRSTGL = ''; /* #364 */
                    $receipts->CRSTS = ''; /* #365 */
                    $receipts->CRDST = ''; /* #366 */
                    $PAY_ -= $PBAL; /* #367 */
                    $receipts->insert(); /* #368 insert record */
                } /* #369 end if */
            } /* #370 end if */
            if ($aropen->AROCIC === 'C'
            ) {
                $WRKBAL = 0; /* #373 */
                $PBAL = 0; /* #374 */
                $PBAL = $aropen->AROAMT - $aropen->AROPPD; /* #375 */
                if ($PBAL < 0
                ) {
                    $WRKBAL = $PBAL * -1; /* #378 */
                    $PAY_ += $WRKBAL; /* #379 */
                } /* #380 end if */
                if ($PBAL <= $PAY_
                    && $PBAL !== 0
                ) {
                    $aropen->AROPPD += $PBAL; /* #384 */
                    $aropen->ARONPD += $PBAL; /* #385 */
                    $aropen->AROPDT = $billpay->BPPDAT; /* #386 */
                    $aropen->update(); /* #387 update record */
                    $receipts->CRCID = $aropen->AROCID; /* #389 */
                    $receipts->CRPER = $PERIOD; /* #390 */
                    $receipts->CRCUST = $aropen->AROCUS; /* #391 */
                    $receipts->CRSLMN = $aropen->AROSLM; /* #392 */
                    $receipts->CRDTPD = $billpay->BPPDAT; /* #393 */
                    $receipts->CRDTIN = $aropen->AROIDT; /* #394 */
                    $receipts->CRINV_ = $aropen->AROINV; /* #395 */
                    $receipts->CRTYPE = '1'; /* #396 */
                    $receipts->CRCHCK = $billpay->BPCONF; /* #397 */
                    $receipts->CRGLDP = ''; /* #398 */
                    $receipts->CRGLAC = ''; /* #399 */
                    $receipts->CRDESC = ''; /* #400 */
                    $receipts->CRPPD = $PBAL; /* #401 */
                    $receipts->CRDUS = 0; /* #402 */
                    $receipts->CRSTCM = ''; /* #403 */
                    $receipts->CRSTGL = ''; /* #404 */
                    $receipts->CRSTS = ''; /* #405 */
                    $receipts->CRDST = ''; /* #406 */
                    $receipts->insert(); /* #407 insert record */
                } /* #408 end if */
            } /* #409 end if */
            $WRKBAL = 0; /* #412 */
            if ($aropen->AROCIC === 'I'
                || $aropen->AROCIC === 'O'
            ) {
                if ($PAY_ > 0
                    && $aropen->AROAMT === 0
                ) {
                    $PBAL = 0; /* #417 */
                    $PBAL += $aropen->AROPPD; /* #418 */
                    $WRKBAL = $PBAL * -1; /* #419 */
                    if ($WRKBAL <= $PAY_
                        && $PBAL !== 0
                    ) {
                        $PAY_ -= $PBAL; /* #422 */
                        $aropen->AROAMT += $PBAL; /* #423 */
                        $aropen->ARONAM += $PBAL; /* #424 */
                        $aropen->AROPDT = $billpay->BPPDAT; /* #425 */
                        $aropen->update(); /* #426 update record */
                        $receipts->CRCID = $aropen->AROCID; /* #428 */
                        $receipts->CRPER = $PERIOD; /* #429 */
                        $receipts->CRCUST = $aropen->AROCUS; /* #430 */
                        $receipts->CRSLMN = $aropen->AROSLM; /* #431 */
                        $receipts->CRDTPD = $billpay->BPPDAT; /* #432 */
                        $receipts->CRDTIN = $aropen->AROIDT; /* #433 */
                        $receipts->CRINV_ = $aropen->AROINV; /* #434 */
                        $receipts->CRTYPE = '1'; /* #435 */
                        $receipts->CRCHCK = $billpay->BPCONF; /* #436 */
                        $receipts->CRGLDP = ''; /* #437 */
                        $receipts->CRGLAC = ''; /* #438 */
                        $receipts->CRDESC = ''; /* #439 */
                        $receipts->CRPPD = $PBAL; /* #440 */
                        $receipts->CRDUS = 0; /* #441 */
                        $receipts->CRSTCM = ''; /* #442 */
                        $receipts->CRSTGL = ''; /* #443 */
                        $receipts->CRSTS = ''; /* #444 */
                        $receipts->CRDST = ''; /* #445 */
                        $receipts->insert(); /* #446 insert record */
                    } /* #447 end if */
                } /* #448 end if */
            } /* #449 end if */
            if ($aropen->AROCIC === 'I'
                || $aropen->AROCIC === 'O'
            ) {
                $PBAL = 0; /* #454 */
                $PBAL = $aropen->AROAMT - $aropen->AROPPD; /* #455 */
                $PBAL *= -1; /* #456 */
                if ($PBAL > $PAY_
                    && $PAY_ > 0
                    && $aropen->AROAMT === 0
                ) {
                    $aropen->AROAMT -= $PAY_; /* #460 */
                    $aropen->ARONAM -= $PAY_; /* #461 */
                    $aropen->AROPDT = $billpay->BPPDAT; /* #462 */
                    $PAY_ -= $PBAL; /* #463 */
                    $aropen->update(); /* #464 update record */
                    $receipts->CRCID = $aropen->AROCID; /* #466 */
                    $receipts->CRPER = $PERIOD; /* #467 */
                    $receipts->CRCUST = $aropen->AROCUS; /* #468 */
                    $receipts->CRSLMN = $aropen->AROSLM; /* #469 */
                    $receipts->CRDTPD = $billpay->BPPDAT; /* #470 */
                    $receipts->CRDTIN = $aropen->AROIDT; /* #471 */
                    $receipts->CRINV_ = $aropen->AROINV; /* #472 */
                    $receipts->CRTYPE = '1'; /* #473 */
                    $receipts->CRCHCK = $billpay->BPCONF; /* #474 */
                    $receipts->CRGLDP = ''; /* #475 */
                    $receipts->CRGLAC = ''; /* #476 */
                    $receipts->CRDESC = ''; /* #477 */
                    $receipts->CRPPD = -($PAY_); /* #478 */
                    $receipts->CRDUS = 0; /* #479 */
                    $receipts->CRSTCM = ''; /* #480 */
                    $receipts->CRSTGL = ''; /* #481 */
                    $receipts->CRSTS = ''; /* #482 */
                    $receipts->CRDST = ''; /* #483 */
                    $receipts->insert(); /* #484 insert record */
                } /* #485 end if */
            } /* #486 end if */
            if ($aropen->AROCIC === 'C'
            ) {
                $WRKBAL = 0; /* #489 */
                $PBAL = 0; /* #490 */
                $PBAL = $aropen->AROAMT - $aropen->AROPPD; /* #491 */
                if ($PBAL < 0
                ) {
                    $WRKBAL = $PBAL * -1; /* #494 */
                    $PAY_ += $WRKBAL; /* #495 */
                } /* #496 end if */
                if ($PBAL <= $PAY_
                    && $PBAL !== 0
                ) {
                    $aropen->AROPPD += $PBAL; /* #500 */
                    $aropen->ARONPD += $PBAL; /* #501 */
                    $aropen->AROPDT = $billpay->BPPDAT; /* #502 */
                    $aropen->update(); /* #503 update record */
                    $receipts->CRCID = $aropen->AROCID; /* #505 */
                    $receipts->CRPER = $PERIOD; /* #506 */
                    $receipts->CRCUST = $aropen->AROCUS; /* #507 */
                    $receipts->CRSLMN = $aropen->AROSLM; /* #508 */
                    $receipts->CRDTPD = $billpay->BPPDAT; /* #509 */
                    $receipts->CRDTIN = $aropen->AROIDT; /* #510 */
                    $receipts->CRINV_ = $aropen->AROINV; /* #511 */
                    $receipts->CRTYPE = '1'; /* #512 */
                    $receipts->CRCHCK = $billpay->BPCONF; /* #513 */
                    $receipts->CRGLDP = ''; /* #514 */
                    $receipts->CRGLAC = ''; /* #515 */
                    $receipts->CRDESC = ''; /* #516 */
                    $receipts->CRPPD = $PBAL; /* #517 */
                    $receipts->CRDUS = 0; /* #518 */
                    $receipts->CRSTCM = ''; /* #519 */
                    $receipts->CRSTGL = ''; /* #520 */
                    $receipts->CRSTS = ''; /* #521 */
                    $receipts->CRDST = ''; /* #522 */
                    $receipts->insert(); /* #523 insert record */
                } /* #524 end if */
            } /* #525 end if */
            $customer_rows = db::fetchRows($sql, 'DTSDATA.MFCUST'); /* #527 */
            $customer->CULRDT = $billpay->BPPDAT; /* #528 */
            $customer->CUREC->update(); /* #529 update record */
            EXCPT('', 'DET', '_IN', '');
        } /* #532 end if */
    } /* #533 end while */
} /* #534 end function */
function UPDCAR()
{
    $aropen_rows = db::fetchRows($sql, 'DTSDATA.AROPEN'); /* #539 */
    if ($aropen
    ) {
        if ($billpay->BPOIN_ < 0
        ) {
            $aropen->AROPPD += $billpay->BPPAY_; /* #544 */
            $aropen->ARONPD += $billpay->BPPAY_; /* #545 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #546 */
            $aropen->update(); /* #547 update record */
            $receipts->CRCID = $aropen->AROCID; /* #549 */
            $receipts->CRPER = $PERIOD; /* #550 */
            $receipts->CRCUST = $aropen->AROCUS; /* #551 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #552 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #553 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #554 */
            $receipts->CRINV_ = $aropen->AROINV; /* #555 */
            $receipts->CRTYPE = '1'; /* #556 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #557 */
            $receipts->CRGLDP = ''; /* #558 */
            $receipts->CRGLAC = ''; /* #559 */
            $receipts->CRDESC = ''; /* #560 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #561 */
            $receipts->CRDUS = 0; /* #562 */
            $receipts->CRSTCM = ''; /* #563 */
            $receipts->CRSTGL = ''; /* #564 */
            $receipts->CRSTS = ''; /* #565 */
            $receipts->CRDST = ''; /* #566 */
            $receipts->insert(); /* #567 insert record */
        } /* #568 end if */
        if ($billpay->BPOIN_ === 0
            && $billpay->BPPAY_ < 0
            && $billpay->BPPRV_ > 0
        ) {
            $billpay->BPPAY_ *= -1; /* #573 */
            $aropen->AROAMT += $billpay->BPPAY_; /* #574 */
            $aropen->ARONAM += $billpay->BPPAY_; /* #575 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #576 */
            $aropen->update(); /* #577 update record */
            $billpay->BPPAY_ *= -1; /* #579 */
            $receipts->CRCID = $aropen->AROCID; /* #580 */
            $receipts->CRPER = $PERIOD; /* #581 */
            $receipts->CRCUST = $aropen->AROCUS; /* #582 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #583 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #584 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #585 */
            $receipts->CRINV_ = $aropen->AROINV; /* #586 */
            $receipts->CRTYPE = '1'; /* #587 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #588 */
            $receipts->CRGLDP = ''; /* #589 */
            $receipts->CRGLAC = ''; /* #590 */
            $receipts->CRDESC = ''; /* #591 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #592 */
            $receipts->CRDUS = 0; /* #593 */
            $receipts->CRSTCM = ''; /* #594 */
            $receipts->CRSTGL = ''; /* #595 */
            $receipts->CRSTS = ''; /* #596 */
            $receipts->CRDST = ''; /* #597 */
            $receipts->insert(); /* #598 insert record */
        } /* #599 end if */
        if ($billpay->BPOIN_ === 0
            && $billpay->BPPAY_ > 0
            && $billpay->BPPRV_ < 0
        ) {
            $billpay->BPPAY_ *= -1; /* #604 */
            $aropen->AROAMT += $billpay->BPPAY_; /* #605 */
            $aropen->ARONAM += $billpay->BPPAY_; /* #606 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #607 */
            $aropen->update(); /* #608 update record */
            $billpay->BPPAY_ *= -1; /* #610 */
            $receipts->CRCID = $aropen->AROCID; /* #611 */
            $receipts->CRPER = $PERIOD; /* #612 */
            $receipts->CRCUST = $aropen->AROCUS; /* #613 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #614 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #615 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #616 */
            $receipts->CRINV_ = $aropen->AROINV; /* #617 */
            $receipts->CRTYPE = '1'; /* #618 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #619 */
            $receipts->CRGLDP = ''; /* #620 */
            $receipts->CRGLAC = ''; /* #621 */
            $receipts->CRDESC = ''; /* #622 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #623 */
            $receipts->CRDUS = 0; /* #624 */
            $receipts->CRSTCM = ''; /* #625 */
            $receipts->CRSTGL = ''; /* #626 */
            $receipts->CRSTS = ''; /* #627 */
            $receipts->CRDST = ''; /* #628 */
            $receipts->insert(); /* #629 insert record */
        } /* #630 end if */
        if ($billpay->BPOIN_ > 0
        ) {
            $aropen->AROPPD += $billpay->BPPAY_; /* #633 */
            $aropen->ARONPD += $billpay->BPPAY_; /* #634 */
            $aropen->AROPDT = $billpay->BPPDAT; /* #635 */
            $aropen->update(); /* #636 update record */
            $receipts->CRCID = $aropen->AROCID; /* #638 */
            $receipts->CRPER = $PERIOD; /* #639 */
            $receipts->CRCUST = $aropen->AROCUS; /* #640 */
            $receipts->CRSLMN = $aropen->AROSLM; /* #641 */
            $receipts->CRDTPD = $billpay->BPPDAT; /* #642 */
            $receipts->CRDTIN = $aropen->AROIDT; /* #643 */
            $receipts->CRINV_ = $aropen->AROINV; /* #644 */
            $receipts->CRTYPE = '1'; /* #645 */
            $receipts->CRCHCK = $billpay->BPCONF; /* #646 */
            $receipts->CRGLDP = ''; /* #647 */
            $receipts->CRGLAC = ''; /* #648 */
            $receipts->CRDESC = ''; /* #649 */
            $receipts->CRPPD = $billpay->BPPAY_; /* #650 */
            $receipts->CRDUS = 0; /* #651 */
            $receipts->CRSTCM = ''; /* #652 */
            $receipts->CRSTGL = ''; /* #653 */
            $receipts->CRSTS = ''; /* #654 */
            $receipts->CRDST = ''; /* #655 */
            $receipts->insert(); /* #656 insert record */
        } /* #657 end if */
    } /* #659 end if */
} /* #660 end function */
function GETPRD()
{
    $CYMMD1 = $billpay->BPPDAT; /* #664 */
    $PERIOD = 0; /* #666 */
    $acctgper_rows = db::fetchRows($sql, 'DTSDATA.ATGREC'); /* #669 */
    while ($acctgper === $true
    ) {
        if ($CYMMD1 >= $acctgper->ATGSTR
            && $CYMMD1 <= $acctgper->ATGEND
        ) {
            $PERIOD = $acctgper->ATGPER; /* #675 */
            $acctgper = $false; /* #676 */
        } else {
            $acctgper_rows = db::fetchRows($sql, 'DTSDATA.ATGREC'); /* #679 */
        } /* #680 end if */
    } /* #682 end while */
} /* #683 end function */
