

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
	t1 = (arm_sizes.upper_arm_length * arm_sizes.upper_arm_length);		// b
	t2 = (arm_sizes.lower_arm_length * arm_sizes.lower_arm_length);		// a 
	//t1 = (LENGTH_SHOULDER * LENGTH_SHOULDER);
	//t2 = (LENGTH_ELBOW * LENGTH_ELBOW);
	t3 = (Direct_SW_LineLength * Direct_SW_LineLength);					// c
	var numerator = (-t2+t1+t3);
	var denominator = (2*arm_sizes.upper_arm_length * Direct_SW_LineLength);
	
	if (numerator>denominator)
	{
		return 0; //"POSITION OUT OF REACH!";
	}
//	printf("Law of Cosines:\n  a=%6.3f\n  b=%6.3f\n  c=%6.3f\n", LENGTH_SHOULDER, LENGTH_ELBOW, Direct_SW_LineLength);
//	printf("INV_Calc_ShoulderAndElbow_Angles:  Numerator=%6.3f;  Denom=%6.3f\n",(t1-t2+t3),
//	  		(2*LENGTH_SHOULDER*Direct_SW_LineLength) );

	// Shoulder Angle : 
	Angle2 = Math.acos( numerator / denominator );		// partial shoulder angle
	mResult.Shoulder = Direct_SW_Angle1 + Angle2;		// in radians

	// Elbow_Angle - Law of Cosines:
	mResult.Elbow = Math.acos( (t1+t2-t3)/(2*arm_sizes.upper_arm_length * arm_sizes.lower_arm_length) );
	mResult.Elbow = - CT_Convert_FlipDirection( mResult.Elbow );
	return 1;
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
	mAngleSet.Wrist = (Approach_Angle - mAngleSet.Shoulder - mAngleSet.Elbow);
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
	if ((typeof XYZ.x == 'undefined') || (typeof XYZ.y == 'undefined') || (typeof XYZ.z == 'undefined'))
		return 0;

	// Begin Calculations:
	var Radius;
	mServoAngles.Base	= INV_Calc_BaseRotation    ( XYZ.y, XYZ.z );
	Radius 		 		= INV_Calc_RadiusProjection( XYZ.y, XYZ.z );
	
	var result = INV_Calc_ShoulderAndElbow_Angles( Radius, XYZ.x, mServoAngles );
	if (result==0)		return "out_of_reach";		// Not Reachable!
	
	INV_Calc_WristAngle ( XYZ.Approach, mServoAngles  );

	mServoAngles.unit = "Radians";
	
	//Feasibility = INV_Determine_Feasibility( Radius, XYZ['Z'], mResult );
	//if (Feasibility > 0)			// if error
	//	return FALSE;				// not reachable!

	// At this point, only *_Angles have been updated.  No intermediate servo positions
	// or actual servos.  Call Actuate if this is desired.
	return "success";		// Position is feasible
}

var GRIP_TIP    = 3;
var GRIP_CENTER = 2;
var GRIP_BACK   = 1;
var GRIP_NONE   = 0;
function getGripper_length( GripTip )
{
	// COMPUTE LENGTH OF INCLUDED GRIPPER :
	var full_length = arm_sizes.wrist_length + grip_sizes.wrist_length + grip_sizes.joiner_width;
	if (GripTip==GRIP_TIP)
		full_length += grip_sizes.grip1_length;
	else if (GripTip==GRIP_CENTER)
		full_length += grip_sizes.grip1_length/2;		// center of grip
	else if (GripTip==GRIP_BACK)
		full_length += 0;		// center of grip
	else if (GripTip==GRIP_NONE)
		full_length = 0;		// center of grip
	return full_length;	
}
function INV_Grip_XYZ_To_Angles( XYZ, Approach, mServoAngles, GripTip)
{
	// NEED BASE ANGLE to subtract the gripper vector:
	mServoAngles.Base	= INV_Calc_BaseRotation    ( XYZ.y, XYZ.z );
	
	// COMPUTE LENGTH OF INCLUDED GRIPPER :
	var full_length = getGripper_length( GripTip );
/*	var full_length = arm_sizes.wrist_length + grip_sizes.wrist_length + grip_sizes.joiner_width;
	if (GripTip==GRIP_TIP)
		full_length += grip_sizes.grip1_length;
	else if (GripTip==GRIP_CENTER)
		full_length += grip_sizes.grip1_length/2;		// center of grip
	else if (GripTip==GRIP_BACK)
		full_length += 0;		// center of grip
	else if (GripTip==GRIP_NONE)
		full_length = 0;		// center of grip
*/
	// GET COMPONENTS OF APPROACH VECTOR IN WRIST SPACE : 
	var x_approach = full_length * Math.sin(Approach);
	var y_approach = full_length * Math.cos(Approach);
	var approach_vector = new THREE.Vector3( x_approach, y_approach, 0 );

	// ROTATE WRIST SPACE VECTOR THRU BASE ROTATION :
	// (no need to rotate other angles b/c Approach by definition is the wrist wrt to horizon):
	var x_axis = new THREE.Vector3( 1,0,0 );
	approach_vector.applyAxisAngle (x_axis, mServoAngles.Base );

	XYZ.x -= approach_vector.x;
	XYZ.y -= approach_vector.y;
	XYZ.z -= approach_vector.z;	

	var status = INV_XYZ_To_Angles( XYZ, mServoAngles );
	return status;
}

/* This is up to the wrist! */
function forward_equations( arm_sizes, angle_set, XYZ )
{
	// First go in the Plane of Shoulder/Elbow:
	var x_comp1	= arm_sizes.shoulder_length * Math.cos( angle_set.Shoulder );
	var y_comp1	= arm_sizes.shoulder_length * Math.sin( angle_set.Shoulder );
	
	// Now the fore arm projections : 
	var elbow_angle = angle_set.Shoulder + angle_set.elbow_angle);
	var x_comp2	= arm_sizes.lower_arm_length * Math.cos( elbow_angle );
	var y_comp2	= arm_sizes.lower_arm_length * Math.sin( elbow_angle );
		
	var x_comp = x_comp1 + x_comp2;
	XYZ.x = y_comp1 + y_comp2;			// Height!
	
	// 
	XYZ.y = x_comp * Math.sin( angle_set.Base );
	XYZ.z = x_comp * Math.cos( angle_set.Base );
	
	var debug_str = "forward_equations() = <"+XYZ.x+", "+XYZ.y+", "+XYZ.z+">";	
	console.log(debug_str);	
}

function forward_equations_gripper( arm_sizes, angle_set, XYZ, GripTip )
{
	forward_equations( arm_sizes, angle_set, XYZ );

	var full_length = getGripper_length( GripTip );
	
	// In Base Rotated Plane : 
	var w_height = full_length * Math.sin( approach );
	var w_x_comp = full_length * Math.cos( approach );
	
	// Now map thru Base Angle : 
	XYZ.x += w_height;
	XYZ.y += w_x_comp * Math.sin( angle_set.Base );
	XYZ.z += w_x_comp * Math.cos( angle_set.Base );	

	var debug_str = "forward_equations_gripper() = <"+XYZ.x+", "+XYZ.y+", "+XYZ.z+">";	
	console.log(debug_str);	

}

</script>

