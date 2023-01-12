class IndicatorStrategy extends IndicatorAlgorithm
{
    private _symbol: any;
    private _periods: number;

    /**
     * Initialize the indicator
     */
    initialize()
    {
        // set the chart series
        // this.setSeries('histogram');
        // this.setValueType('single');

        // get the symbol for the indicator
        this._symbol = this.getSymbol();

        // get the parameter for the periods used with the indicator
        this._periods = this.getParameter('integer', 'periods'); 
    }

    /**
     * OnTick (individual price movement)
     * - when a symbol's price is moved
     */
    onTick(candle)
    {

        console.log('periods: ' + this._periods);

        const previousData = this.getHistoricData(this._periods);

        console.log('previous data: ' + previousData.length);

        // send indicator as ready when enough previous data is loaded
        if(previousData.length == this._periods) {
            this.setReady(true);
        }

        let sumGain = 0;
        let sumLoss = 0;
        for(let i = 1; i < previousData.length; i++) {
            let diff = previousData[i].close - previousData[i - 1].close;
            if(diff >= 0) {
                sumGain += diff;
            }
            else {
                sumLoss -= diff;
            }
        }

        if(sumGain == 0) {
            return;
        }

        const relativeStrength = sumGain / sumLoss;

        const relativeStrengthIndex = 100.0 - (100.0 / (1 + relativeStrength)); 

        // if the indicator has more than one value, set each value by name
        this.setValues(relativeStrengthIndex);
    }
}