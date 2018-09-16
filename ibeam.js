

    var IbeamVertexPositionBuffer;
    var IbeamVertexColorBuffer;
    var m_IBO;
    
	var IBEAM_WIDTH = 5.0;
	var IBEAM_HEIGHT = 7.5;
	var IBEAM_THICKNESS = 1;
	var IBEAM_TUCK_IN = 2.0;
	
	var num_side_indices ;

    function init_ibeam_Buffers() {
        IbeamVertexPositionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, IbeamVertexPositionBuffer);
        var vertices = [
/* 0 */            0.0,  0.0,  0.0,
            IBEAM_WIDTH, 0.0,  0.0,
            IBEAM_WIDTH, IBEAM_THICKNESS,  0.0,            
            IBEAM_WIDTH-IBEAM_TUCK_IN, IBEAM_THICKNESS,  0.0,            
            IBEAM_WIDTH-IBEAM_TUCK_IN, IBEAM_HEIGHT,  0.0,    
/* 5 */        IBEAM_WIDTH, IBEAM_HEIGHT,  0.0,            
            IBEAM_WIDTH, +IBEAM_THICKNESS,  0.0,   
            0.0, IBEAM_HEIGHT+IBEAM_THICKNESS, 0.0,
            0.0, IBEAM_HEIGHT, 0.0,
            IBEAM_TUCK_IN, IBEAM_HEIGHT, 0.0,
/* 10 */            IBEAM_TUCK_IN, IBEAM_THICKNESS, 0.0,            
            0.0, IBEAM_THICKNESS, 0.0,
/* 12 */            0.0, 0.0, 0.0
        ];
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
        IbeamVertexPositionBuffer.itemSize =3;
        IbeamVertexPositionBuffer.numItems =13;

        IbeamVertexColorBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, IbeamVertexColorBuffer);
        var colors = [
            1.0, 0.0, 0.0, 1.0,
            0.0, 1.0, 0.0, 1.0,
            0.0, 0.0, 1.0, 1.0,
            1.0, 0.0, 0.0, 1.0,
            0.0, 1.0, 0.0, 1.0,
            0.0, 0.0, 1.0, 1.0,
            1.0, 0.0, 0.0, 1.0,
            0.0, 1.0, 0.0, 1.0,
            0.0, 0.0, 1.0, 1.0,
            1.0, 0.0, 0.0, 1.0,
            0.0, 1.0, 0.0, 1.0,
            0.0, 0.0, 1.0, 1.0,
            0.0, 0.0, 1.0, 1.0,
        ];
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(colors), gl.STATIC_DRAW);
        IbeamVertexColorBuffer.itemSize = 4;
        IbeamVertexColorBuffer.numItems = 13;
        
        m_IBO = gl.createBuffer();
		gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, m_IBO);        
        var indices = [
				0 ,	1, 2, 3, 4,
				11,	2,	
				10,	3,
				9 ,	4,
				8 ,	5,	
				7 ,	6
		        ];
//		var indices = [ 0,1,5, 5,0,8	 ];
		    
        gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Int8Array(indices), gl.STATIC_DRAW);        		        
		num_sides_indices = 5;
	}
	
	function draw_ibeam(gl)
	{
        gl.bindBuffer(gl.ARRAY_BUFFER, IbeamVertexPositionBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, IbeamVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);   


//		gl.vertexPointer(3, gl.FLOAT, 		 0, 0);
		//gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, m_IBO.itemSize, gl.BYTE, false, 0, 0);   

        gl.bindBuffer(gl.ARRAY_BUFFER, IbeamVertexColorBuffer);
        gl.vertexAttribPointer(shaderProgram.vertexColorAttribute, IbeamVertexColorBuffer.itemSize, gl.FLOAT, false, 0, 0);

		gl.bindBuffer( gl.ELEMENT_ARRAY_BUFFER, m_IBO );
		
        //setMatrixUniforms();
        //gl.drawArrays(gl.TRIANGLES, 0, IbeamVertexPositionBuffer.numItems);
	
		// Draw the sides:
		gl.drawElements(gl.TRIANGLE_STRIP, num_sides_indices, gl.UNSIGNED_BYTE,   0  );


	
	}