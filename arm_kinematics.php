

<script>
var LENGTH_SHOULDER =	4.75;
var LENGTH_ELBOW	=	4.75;
var LENGTH_WRIST	=	6.0;
var BASE_HEIGHT		= 	2.5;

/*****************************************************************************
Name		:   INV_Calc_BaseRotation()
Parameters	:	X,Y given in inches
Return		:   Base angle in radians
Description	:   This function performs the inverse calculations for finding
				the base rotation which will position the griper towards the
				specified position.				
*****************************************************************************/
function INV_Calc_BaseRotation( mX, mY )
{
	// atan2 takes care of the quadrant issue - good for 0 to 180 degrees.
	return Math.atan2(mY,mX);
}

/*****************************************************************************
Name		:   INV_Calc_RadiusProjection()
Parameters	:	X,Y,Z given in inches.
Return		:   Projection of the radius in the XY plane
Description	:   This function performs the inverse calculations for finding
				the servo motor positions which will position the griper at
				the specified X,Y,Z position.
*****************************************************************************/
function INV_Calc_RadiusProjection( mX, mY)
{
	return Math.sqrt(mX*mX + mY*mY);
}

/*****************************************************************************
Name		:   INV_Subtract_BaseHeight()
Parameters	:	mZ the Z height in inches
Return		:   mZ - origin is now even with the top of the base servo.
Description	:   Subtracts the stored height of the base.
*****************************************************************************/
function INV_Subtract_BaseHeight( mZ )
{
	return (mZ - BASE_HEIGHT);
}

/**********************************************************************************
Name            :   CT_Convert_FlipDirection()
Parameters      :       mRadians
Returns         :   Degrees
Description     :   Switch between ClockWise and CounterClockWise
************************************************************************************/
function CT_Convert_FlipDirection( mRadians)
{
        var Angle = Math.PI - mRadians;
        return Angle;
}

/*****************************************************************************
Name		:   INV_Calc_ShoulderAndElbow_Angles()
Parameters	:	X,Y,Z given in inches.
Return		:   Fills in S2 and S3 of the mResult structure (radians)
Description	:   This function performs the inverse calculations for finding
				the servo motor positions which will position the griper at
				the specified X,Y,Z position.
*****************************************************************************/
function INV_Calc_ShoulderAndElbow_Angles( mRadius,  mHeight, mResult )
{
	var t1,t2,t3;
	var Direct_SW_Angle1,Angle2;
	var Numerator;
	var Direct_SW_LineLength;
	
	Direct_SW_LineLength = Math.sqrt(mRadius*mRadius + mHeight*mHeight);		// Spherical Radius.
	Direct_SW_Angle1     = Math.atan2( mHeight, mRadius );						// Elevation angle

	// Law of Cosines : 
	t1 = (LENGTH_SHOULDER * LENGTH_SHOULDER);
	t2 = (LENGTH_ELBOW * LENGTH_ELBOW);
	t3 = (Direct_SW_LineLength * Direct_SW_LineLength);
	var numerator = (t1-t2+t3);
	var denominator = (2*LENGTH_SHOULDER*Direct_SW_LineLength);
	
	if (numerator>denominator)
	{
		return "POSITION OUT OF REACH!";
	}
//	printf("Law of Cosines:\n  a=%6.3f\n  b=%6.3f\n  c=%6.3f\n", LENGTH_SHOULDER, LENGTH_ELBOW, Direct_SW_LineLength);
//	printf("INV_Calc_ShoulderAndElbow_Angles:  Numerator=%6.3f;  Denom=%6.3f\n",(t1-t2+t3),
//	  		(2*LENGTH_SHOULDER*Direct_SW_LineLength) );

	Angle2 = Math.acos( numerator / denominator );

	// Shoulder Angle : 
	mResult['s2'] = Direct_SW_Angle1 + Angle2;		// in radians

	// Elbow_Angle - Law of Cosines:
	mResult['s3'] = Math.acos( (t1+t2-t3)/(2*LENGTH_SHOULDER*LENGTH_ELBOW) );
	//printf("INV_Calc_ShoulderAndElbow_Angles:  Elbow Angle=%6.3f degs;\n", CT_Convert_Radians_to_Degrees(mResult->s3) );
	mResult['s3'] = - CT_Convert_FlipDirection( mResult['s3'] );
}

/*****************************************************************************
Name		:   INV_Calc_Wrist()
Parameters	:	Approach_Angle (radians)
			:	Shoulder and Elbow angles must already be properly filled in 
			:   the mAngleSet structure (radians)
Return		:   mAngleSet->s4 (Wrist Angle in radians)
Description	:   The coordinate frame is on the table top.  So whatever Z above
				table top is desired, we need to subtract the height that the 
				base contributes.
*****************************************************************************/
function INV_Calc_WristAngle( Approach_Angle, mAngleSet)
{
	mAngleSet['s4'] = (Approach_Angle - mAngleSet['s2'] - mAngleSet['s3']);
}


/*****************************************************************************
Name		:   INV_Find_XYZ()
Parameters	:	X,Y,Z given in inches.
			:   mApproach in radians.
Return		:   mResult Structure is filled in. (the Servo Angles Set)
			:   TRUE if arm position can be Reached with the arm 
			:	FALSE if Unreachable
Description	:   This function performs the inverse calculations for finding
				the servo motor positions which will position the griper at
				the specified X,Y,Z position.
*****************************************************************************/
//uint8_t INV_Find_XYZ(float mX, float mY, float mZ, float mApproach_degrees, struct stServoAnglesSet* mResult)
function INV_XYZ_To_Angles( XYZ, mServoAngles)
{
	// Begin Calculations:
	var Radius;
	mServoAngles['s1']	= INV_Calc_BaseRotation    ( XYZ['y'], XYZ['z'] );
	Radius 		 		= INV_Calc_RadiusProjection( XYZ['y'], XYZ['z'] );
	
	// Subtract the gripper segment to find the Wrist XYZ
	//INV_Subtract_Gripper_RH( &(mResult->s1), &Radius, &(XYZ->Z), XYZ->Approach );			
	//INV_Subtract_BaseHeight( &(XYZ->Z) );

	INV_Calc_ShoulderAndElbow_Angles( Radius, XYZ['x'], mServoAngles );
	INV_Calc_WristAngle				( XYZ['Approach'], mServoAngles  );

	//Feasibility = INV_Determine_Feasibility( Radius, XYZ['Z'], mResult );
	//if (Feasibility > 0)			// if error
	//	return FALSE;				// not reachable!

	// At this point, only *_Angles have been updated.  No intermediate servo positions
	// or actual servos.  Call Actuate if this is desired.
	return 1;		// Position is feasible
}


</script>

