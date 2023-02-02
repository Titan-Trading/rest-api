(sandbox: any) => {
    // get inputs
    const periods = sandbox.input('periods');

    // setup event streams
    sandbox.on(`update`, (update) => {
        console.log('update from within indicator', update);

        const closes = update.close;

        if(!closes || closes.length < periods) {
            sandbox.setValue(0);
            return;
        }

        let sumGain = 0;
        let sumLoss = 0;
        for(let i = 1; i < closes.length; i++) {
            let diff = closes[i] - closes[i - 1];
            if(diff >= 0) {
                sumGain += diff;
            }
            else {
                sumLoss -= diff;
            }
        }

        if(sumGain == 0) {
            sandbox.setValue(0);
            return;
        }

        const relativeStrength = sumGain / sumLoss;

        const relativeStrengthIndex = 100.0 - (100.0 / (1 + relativeStrength));

        // if the indicator has more than one value, set each value by name
        sandbox.setValue(relativeStrengthIndex);
    });

    // return the indicators config
    return [
        {
            type: 'integer',
            key: 'periods',
            name: 'Periods',
            defaultValue: 14
        }
    ];
};