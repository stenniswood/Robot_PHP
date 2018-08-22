
<canvas id="myChart" class="graphs" width="500" height="200" style="background-color:#FFFFC1" ></canvas>

<script src="../Chart.js-2.7.2/dist/Chart.bundle.js"></script>

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

/*var newDataset = {
				label: 'Load cell 1',
				backgroundColor: newColorSet,
				borderColor: newColorSet,
				data: [],
				fill: false
			};
*/
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
	config.data.datasets.forEach( (variable,index) => {
		if (variable.label=="setName")
			return index;
	});
	
}
function hide_on_graph(varindex)
{
	var ds_index = getDatasetIndex( InputVars[varindex]['name'] );
	
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
	
	if (config.data.datasets.length > 0) 
	{		
		var label = InputVars[InputVarIndex]['dataset'].data.length;
		if (label>=max)
			config.data.labels.push(label);

		InputVars[InputVarIndex]['dataset'].data.push(newValue);
		window.myChart.update();
	}
}
</script>
