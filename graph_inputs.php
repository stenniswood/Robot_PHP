
<canvas id="myChart" class="graphs" width="500" height="200" style="background-color:#FFFFC1" ></canvas>

<!-- <script src="../Chart.js-2.7.2/dist/Chart.bundle.js"></script> -->
<script src="../node_modules/chart.js/dist/Chart.bundle.js"></script>

<script>
var ctx = document.getElementById("myChart").getContext('2d');
var newColorSet = [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ];

//	for (var index = 0; index < 10; ++index) {
//		newDataset.data.push(index*4);
//	}

var config = {
    type: 'line',
    data: {
        labels  : [],
        datasets: []
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
}

var myChart = new Chart(ctx, config);

function getDatasetIndex( setName )
{
	var retval=-1;
	config.data.datasets.forEach( (variable,index) => {
		if (variable.label==setName)
			retval = index;
	});
	return retval;
}
function hide_on_graph(varindex)
{
	var compare_str = "Dataset "+InputVars[varindex]['name'];
	var ds_index = getDatasetIndex( compare_str );
	if (ds_index==-1)
		return;
	config.data.datasets.splice(ds_index, 1);
	window.myChart.update();
}

function show_on_graph(showbox, varindex)
{
	if (showbox.checked)
	{
		config.data.datasets.push( InputVars[varindex]['dataset'] );
		window.myChart.update();
	} else {
		hide_on_graph(varindex);
	}
}
function init_Inputvars_datasets()
{
	InputVars.forEach( (variable,index) => {
		add_dataset( index );
	});
}

function add_dataset(InputVarIndex) 
{
	var newColor = newColorSet[InputVarIndex % newColorSet.length];
	var newDataset = {
		label: 'Dataset ' + InputVars[InputVarIndex]['name'],
		backgroundColor: newColor,
		borderColor: newColor,
		data: [],
		fill: false
	};

/*	for (var index = 0; index < config.data.labels.length; ++index) {
		newDataset.data.push(randomScalingFactor());
	}*/
	InputVars[InputVarIndex]['dataset'] = newDataset;	
}

function add_data(InputVarIndex, newValue)
{
	// Longest Dataset:
	var max = 0;
	config.data.datasets.forEach(function(dataset) {
		if (dataset.data.length > max)
			max = dataset.data.length;
	} );
	max = config.data.labels.length;
	
	// 
	if (config.data.datasets.length > 0) 
	{		
		InputVars[InputVarIndex]['dataset'].data.push(newValue);	
		var label = InputVars[InputVarIndex]['dataset'].data.length;
		if (label>max)
			config.data.labels.push(label);

		window.myChart.update();
	}
}
</script>
