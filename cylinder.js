

    var CylinderVertexPositionBuffer;
    var CylinderVertexColorBuffer;
//    var _IBO;
    
    var m_vertices = [];
    var m_layer_one_vertices=0;
	var num_side_indices ;

    function create_circle(radius, num_samples, Z) 
    {
		var TwoPi     = 2.*Math.PI;
		var increment = (TwoPi/num_samples);
		var  i;
		var  a=0.;
		var x,y;

		for (i=0; i<num_samples; i++)
		{
			x =  radius * Math.sin(a);		m_vertices.push( x );
			y =  radius * Math.cos(a);		m_vertices.push( y );
											m_vertices.push( Z );
			a+=increment;
		}
		m_layer_one_vertices = m_vertices.length;
    }
    function create_sides()
    {
    
    }
    function create_colors()
    {
    	var colors = [];
    	var i;
		for (i=0; i<m_vertices.length; i++)
		{
			colors.push(1.0);
			colors.push(0.0);
			colors.push(0.0);
			colors.push(1.0);			
		}    	
		// COLOR BUFFER : 
        CylinderVertexColorBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, CylinderVertexColorBuffer);

        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(colors), gl.STATIC_DRAW);
        CylinderVertexColorBuffer.itemSize = 4;
        CylinderVertexColorBuffer.numItems = 13;		
    }
    
    function create_cylinder(radius, height, num_samples ) 
    {
		create_circle(radius, num_samples, height);
		create_circle(radius, num_samples, 0.0   );

		// VERTEX BUFFER (VBO)
        CylinderVertexPositionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, CylinderVertexPositionBuffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(m_vertices), gl.STATIC_DRAW);
        CylinderVertexPositionBuffer.itemSize =3;
        CylinderVertexPositionBuffer.numItems =m_vertices.length;

		create_colors();

		create_sides();

        CylinderIBO = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, CylinderIBO);        
        var indices = [];
        for (i=0; i<m_layer_one_vertices; i++)
		{
			indices.push(i);
		}
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Int8Array(indices), gl.STATIC_DRAW);        		        
		//num_sides_indices = 5;
    }
	
	function draw_cylinder(gl)
	{
        gl.bindBuffer(gl.ARRAY_BUFFER, CylinderVertexPositionBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, CylinderVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);   

//		gl.vertexPointer(3, gl.FLOAT, 		 0, 0);
		//gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, m_IBO.itemSize, gl.BYTE, false, 0, 0);   

        gl.bindBuffer(gl.ARRAY_BUFFER, CylinderVertexColorBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexColorAttribute, CylinderVertexColorBuffer.itemSize, gl.FLOAT, false, 0, 0);

		//gl.bindBuffer( gl.ELEMENT_ARRAY_BUFFER, CylinderIBO );
		
        //setMatrixUniforms();
        var sample_points = CylinderVertexPositionBuffer.numItems/2;
        gl.drawArrays(gl.POLYGON, 0, 			 sample_points );
       // gl.drawArrays(gl.POLYGON, sample_points, sample_points );
	
		// Draw the sides:
		//gl.drawElements(gl.POLYGON, num_sides_indices, gl.UNSIGNED_BYTE,   0  );
	
	}
	
	