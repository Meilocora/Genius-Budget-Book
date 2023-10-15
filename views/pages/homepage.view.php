<section class="homepage-container">
    <div class="charts-container">
        <!-- #TODO: Button um Jahr auszuwählen + Button um Goals anzupassen -->
        <div class="charts-container-row">
            <h1>Total Wealth</h1>
        </div>
        <div class="charts-container-row">
            <span>Your total assets are currently worth: <?php echo number_format($currentTotalWealth, '0', ',', '.') . ' €'; ?></span>
        </div>
        <div class="charts-container-row">
            <canvas id="wdChartActualC" width="800px" height="350px"></canvas>
            <canvas id="wdChartActualP" width="500px" height="350px"></canvas>
        </div>  
            <?php if($currentGoalSharesC['Missing wealth'] !== 0): ?>
                <div class="charts-container-row">
                    <span>You have <?php echo e($daysleft); ?> days to reach your personal wealth goal of <?php echo number_format($goalsArray["totalwealthgoal"], '0', ',', '.') . '€'; ?>...</span>
                </div> 
                <div class="charts-container-row">
                    <span>Just accumulate <?php echo number_format(($currentGoalSharesC['Missing wealth']/$daysleft), '0', ',', '.') . '€'; ?> each day und you will be there!</span>
                </div>
            <?php else: ?>
                <div class="charts-container-row">
                    <span>Congratulations, you have reached your personal wealth goal of <?php echo number_format($goalsArray["totalwealthgoal"], '0', ',', '.') . '€'; ?> already!!!</span>
                </div> 
                <div class="charts-container-row">
                    <span>Make sure to reach your donation goal aswell...</span>
                </div>
            <?php endif; ?>             
        <div class="charts-container-row"> 
            <canvas id="wdWealthGoalC" width="800px" height="350px"></canvas>
            <canvas id="wdWealthGoalP" width="500px" height="350px"></canvas>
        </div>
        <div class="charts-container-row">
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="startDate" value="<?php echo date('Y') . '-01-01'; ?>">
                <input type="submit" value="YTD" 
                <?php if($startDate === date('Y') . '-01-01') echo 'disabled'; ?>
                class="<?php if($startDate === date('Y') . '-01-01') echo 'startDateChosen'; ?>">
            </form>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="startDate" value="<?php echo date('Y-m',strtotime("-11 month")) . '-01'; ?>">
                <input type="submit" value="YoY" 
                <?php if($startDate === date('Y-m',strtotime("-11 month")) . '-01') echo 'disabled'; ?>
                class="<?php if($startDate === date('Y-m',strtotime("-11 month")) . '-01') echo 'startDateChosen'; ?>">
            </form>
            <form action="./?route=homepage" method="post" class="chartScopeForm">
                <input type="hidden" name="startDate" value="1970-01-01">
                <input type="submit" value="All" 
                <?php if($startDate === '1970-01-01') echo 'disabled'; ?>
                class="<?php if($startDate === '1970-01-01') echo 'startDateChosen'; ?>">
            </form>
        </div>
        <div class="charts-container-row">
            <canvas id="wdTrendChartActualC" width="1100px" height="450px"></canvas>
        </div>
        <div class="charts-container-row">
            <canvas id="wdTrendChartActualTargetC" width="1100px" height="450px"></canvas>
        </div>  
    </div>
    <div class="charts-container">
        <div class="charts-container-row">
            <h1>Donation Goal</h1>
        </div>
        <div class="charts-container-row">
            <div class="dummy-diagramm"></div>
            <p>Your donation goal für XXXX is XXX</p>
            <p>You need to donate xxx more to reach the goal!</p>
        </div>
    </div>  
    <div class="charts-container">
        <div class="charts-container-row">
            <h1>Saving Goal</h1>
        </div>
        <div class="charts-container-row">
            <div class="dummy-diagramm"></div>
            <p>Your saving goal für XXXX is XXX</p>
            <p>You need to donate xxx more to reach the goal!</p>
        </div>
    </div>  
</section>

<script type="module">
    "use strict";
    import ChartGenerator from "./src/JS/Classes/ChartGenerator.js";
    let chartGenerator = new ChartGenerator();
    let backgroundColor10 = [<?php foreach($backgroundColor10 AS $color) echo "'$color', "; ?>]
    let backgroundColor2 = [<?php foreach($backgroundColor2 AS $color) echo "'$color', "; ?>]
    let backgroundColorTransp10 = [<?php foreach($backgroundColorTransp10 AS $color) echo "'$color', "; ?>]
    let backgroundColorTransp2 = [<?php foreach($backgroundColorTransp2 AS $color) echo "'$color', "; ?>]
    let wdLabelArray = [<?php foreach($currentWDActualArrayC AS $key => $value) echo "'$key'" . ", "; ?>];
    let wdDataArrayCurrency = [<?php foreach($currentWDActualArrayC AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generatePieChart('wdChartActualC', wdLabelArray, wdDataArrayCurrency, '€', true, backgroundColor10);
    
    let wdDataArrayPercentages = [<?php foreach($currentWDActualArrayP AS $key => $value) echo "$value" . ", "; ?>];
    chartGenerator.generatePieChart('wdChartActualP', wdLabelArray, wdDataArrayPercentages, '%', false, backgroundColor10);
    
    let wGoalLabelArray = [<?php foreach($currentGoalSharesC AS $key => $value) echo "'$key'" . ", "; ?>];
    let wGoalDataArrayCurrency = [<?php foreach($currentGoalSharesC AS $data) echo "'$data', "; ?>];
    chartGenerator.generatePieChart('wdWealthGoalC', wGoalLabelArray, wGoalDataArrayCurrency, '€', true, backgroundColor2);
    
    let wGoalDataArrayPercentages = [<?php foreach($currentGoalSharesP AS $data) echo "'$data', "; ?>];
    chartGenerator.generatePieChart('wdWealthGoalP', wGoalLabelArray, wGoalDataArrayPercentages, '%', false, backgroundColor2);

    let wdTrendYLabels = [<?php foreach(end($wdYC) AS $date) echo "'$date', "; ?>];
    let wdTrendYCategoryLabels = [<?php for($x=0; $x<sizeof($wdYC)-1; $x++) echo "'{$wdYC[$x][0]}', "; ?>];
    let wdGoalData = [<?php for($x=1; $x<sizeof($wdYC[0]); $x++) echo "{$goalsArray["totalwealthgoal"]}, "; ?>];
    let wdTrendYDataCat1 = [<?php if(isset($wdYC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[0][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "{$wdYC[0][$x]}, "; ?>];
    let wdTrendYDataCat2 = [<?php if(isset($wdYC[1][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[1][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[1][$x]}', "; ?>];
    let wdTrendYDataCat3 = [<?php if(isset($wdYC[2][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[2][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[2][$x]}', "; ?>];
    let wdTrendYDataCat4 = [<?php if(isset($wdYC[3][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[3][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[3][$x]}', "; ?>];
    let wdTrendYDataCat5 = [<?php if(isset($wdYC[4][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[4][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[4][$x]}', "; ?>];
    let wdTrendYDataCat6 = [<?php if(isset($wdYC[5][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[5][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[5][$x]}', "; ?>];
    let wdTrendYDataCat7 = [<?php if(isset($wdYC[6][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[6][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[6][$x]}', "; ?>];
    let wdTrendYDataCat8 = [<?php if(isset($wdYC[7][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[7][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[7][$x]}', "; ?>];
    let wdTrendYDataCat9 = [<?php if(isset($wdYC[8][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[8][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[8][$x]}', "; ?>];
    let wdTrendYDataCat10 = [<?php if(isset($wdYC[9][0]) && !preg_match('/^(.*\s+.*)+$/', $wdYC[9][1])) for($x=1; $x<sizeof($wdYC[0]); $x++) echo "'{$wdYC[9][$x]}', "; ?>];
    chartGenerator.generateLineChart('wdTrendChartActualC', 'Cumulative trend of wealth distributions', backgroundColorTransp10, wdGoalData, wdTrendYLabels, wdTrendYCategoryLabels, wdTrendYDataCat1, wdTrendYDataCat2, wdTrendYDataCat3, wdTrendYDataCat4, wdTrendYDataCat5, wdTrendYDataCat6, wdTrendYDataCat7, wdTrendYDataCat8, wdTrendYDataCat9, wdTrendYDataCat10);

    let wdTrendYTALabels = [<?php for($x=0; $x<sizeof($wdYTargetActualC)-1; $x++) echo "'{$wdYTargetActualC[$x][0]}', "; ?>];
    let wdTrendYTData = [<?php for($x=1; $x<sizeof($wdYTargetActualC[0]); $x++) echo "{$wdYTargetActualC[0][$x]}, "; ?>];
    let wdTrendYAData = [<?php for($x=1; $x<sizeof($wdYTargetActualC[0]); $x++) echo "{$wdYTargetActualC[1][$x]}, "; ?>];
    chartGenerator.generateLineChart('wdTrendChartActualTargetC', 'Cumulative trend of wealth distributions', backgroundColorTransp2, wdGoalData, wdTrendYLabels, wdTrendYTALabels.reverse(), wdTrendYAData, wdTrendYTData);
</script>