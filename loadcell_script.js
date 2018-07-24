	var lc_canvas = document.getElementById("loadcellCanvas");
	var lc_ctx = lc_canvas.getContext("2d");

	var force1 = 0;
	var force2 = 0;
	var force3 = 0;
	var force4 = 0;

function draw_one_cell(left, bottom, force ) 
{
	var width  = 20;
	var height = 30;
	
	var colorR = ( force );
	var colorB = 100;//( 255-force );
	
	lc_ctx.fillStyle   = "rgba( " + colorR + ", 0, "+colorB+", 1.0 )"; 
	lc_ctx.strokeStyle = '#00330';

	lc_ctx.fillRect(left, bottom, width, height);
}
function draw_cells()
{
	draw_one_cell(20, 20, force1 );
	draw_one_cell(20, 60, force2 );
	draw_one_cell(50, 60, force3 );
	draw_one_cell(50, 20, force4 );
}

function update_cell_forces( $event )
{
	force2 = 50;
	force3 = 150;
	force4 = 255;
}

draw_cells();