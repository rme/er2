<?php
/**
 * class.er2.pmFunctions.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.
 * *
 */

////////////////////////////////////////////////////
// er2 PM Functions
//
// Copyright (C) 2007 COLOSA
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

function er2_getMyCurrentDate()
{
	return G::CurDate('Y-m-d');
}

function er2_getMyCurrentTime()
{
	return G::CurDate('H:i:s');
}
