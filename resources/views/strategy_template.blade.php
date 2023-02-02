// the object used to access sandbox functionality is passed into the sandbox'd script

// Sandbox: A virtual environment where data can be passed in and data can be pulled out or remote actions taken in a semi-safe way
// NOTE: A sandbox is transpiled from typescript into es6 javascript, it's error handled and wrapped in a blank scope so that the standard API is hidden.
// FUTURE NOTE: A sandbox could be optmized/super charged if it could be serialized and passed off to a more low level application (using Rust or C++)

// Event Streams: How data is passed into the sandbox
// Event streams have a specific formatted name (for example ohlc!USDT-BTC:1m)
// ohlc is the channel, in this case candlestick data
// ! signifies the start of a channel's parameters
// in the case of ohlc, it takes two parameters symbol and interval
// : separates the parameters

(sandbox: any) => {

    // get inputs
    const tradingSymbol = sandbox.input('tradingSymbol');
    const interval      = sandbox.input('interval');
    const portfolioRisk = sandbox.input('portfolioRisk');

    console.log('external strategy - loaded from local file (cli)');
    console.log('tradingSymbol: ', tradingSymbol);
    console.log('interval: ', interval);
    console.log('portfolioRisk: ', portfolioRisk);

    let symbolName = tradingSymbol;
    if(typeof tradingSymbol === 'object') {
        symbolName = tradingSymbol.target_currency.name + '-' + tradingSymbol.base_currency.name;
    }

    // setup which event streams to listen for
    // default event streams
    // - each new candle at candle close for a given symbol/exchange or security
    // - each market tick for a given symbol/exchange or security (if available)
    // - level 3 market data (individual orders) for a given symbol/exchange (if available)
    // - sentiment analysis updates for a given symbol/exchange or security (if available)
    // - news articles for a given symbol/exchange or security (if available)
    // - order updates from the connected exchange account
    sandbox.on(`ohlc!${symbolName}:${interval}`, (data) => {
        // console.log('data: ', data);

        // check if indicators are ready
        if(!data.indicators['rsi-14'] || !data.indicators['rsi-14'].length) {
            console.log('indicators not ready');
            return;
        }

        const rsi14 = data.indicators['rsi-14'];
        // const atr = data.indicators['ATR'][0];

        // console.log('rsi: ', rsi14[0]);
        // console.log('close: ', data.close[0]);

        // if(data.price > rsi14) {
        //     sandbox.limitOrder();
        // }
    });

    /*sandbox.on(`ticker!${symbolName}`, (data) => {
        console.log('market tick: ', data);
    });

    sandbox.on(`order!${symbolName}`, (data) => {
        console.log('individual order update: ', data);
    });

    sandbox.on(`sentiment!${symbolName}`, (data) => {
        console.log('sentiment update: ', data);
    });

    sandbox.on(`news!${symbolName}`, (data) => {
        console.log('news article update: ', data);
    });

    sandbox.on('accountOrder', (data) => {
        console.log('account update: ', data);
    });*/

    // return a list of options (external settings)
    return {
        options: [
            {
                type: 'symbol',
                key: 'tradingSymbol',
                name: 'Trading symbol',
                defaultValue: 'BTC-USDT'
            },
            {
                type: 'string',
                key: 'interval',
                name: 'Interval',
                defaultValue: '1m'
            },
            {
                type: 'float',
                key: 'portfolioRisk',
                name: 'Portfolio risk',
                defaultValue: 0.1
            }
        ],
        indicators: [
            /*{
                name: 'EMA',
                options: {
                    periods: 2,
                    smoothing: 3
                }
            },
            {
                name: 'ATR',
                options: {
                    periods: 7
                }
            }*/
            {
                source: 'file', // 'file' or 'marketplace' (db) or 'remote'
                name: 'RSI', // name of the indicator script
                handle: 'rsi-14', // handle to get values later
                options: {
                    periods: 14
                }
            }
        ],
        // plugins: []
        // signals: []
    };
}